<?php

namespace App\Http\Controllers;

use App\Repositories\MasterRepository;
use App\Repositories\AuthRepository;
use App\Repositories\PostFacebookDetailRepository;
use App\Repositories\PostInstagramDetailRepository;
use App\Repositories\PostTwitterDetailRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\PageRepository;
use App\Repositories\PageDetailRepository;
use App\Http\Requests;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Facebook\Facebook as Facebook;
use Facebook\Exceptions\FacebookResponseException as FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException as FacebookSDKException;
use Abraham\TwitterOAuth\TwitterOAuthException;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Common\Common;
use Illuminate\Support\Facades\Response;

class ServiceController extends Controller
{
    protected $repMaster;
    protected $repUser;
    protected $repAuth;
    protected $repService;
    protected $repPage;
    protected $repPageDetail;
    protected $repPostFbDetail;
    protected $repPostTwDetail;
    protected $repPostIgDetail;
    protected $services;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(
        MasterRepository $master,
        UserRepository $user,
        AuthRepository $auth,
        ServiceRepository $service,
        PageRepository $page,
        PageDetailRepository $pageDetail,
        PostFacebookDetailRepository $postFbDetail,
        PostTwitterDetailRepository $postTwDetail,
        PostInstagramDetailRepository $postIgDetail
    )
    {
        $this->repMaster        = $master;
        $this->repUser          = $user;
        $this->repAuth          = $auth;
        $this->repPage          = $page;
        $this->repPageDetail    = $pageDetail;
        $this->repService       = $service;
        $this->repPostFbDetail  = $postFbDetail;
        $this->repPostTwDetail  = $postTwDetail;
        $this->repPostIgDetail  = $postIgDetail;
        $this->middleware(['auth']);

        //get all services
        foreach (config('constants.service') as $code => $val) {
            $this->services[$val] = $code;
        }
    }

    public function dashboard(Request $request, $service_code, $user_id = null)
    {
        $user_current   = Auth::user();
        $authority      = config('constants.authority');
        if((!$user_id || $user_current->id == $user_id) && $user_current->authority == $authority['admin']) {
            return redirect('user');
        }
        if($user_current->authority == $authority['client']){
            if($user_id){
                abort(404);
            }
            $user_id = $user_current->id;
        }
        $user            = $this->repUser->getById($user_id);
        if($user) {
            $inputs      = $request->all();
            $date        = $request->cookie('date_search');

            $inputs['to']   = $request->get("to", isset($date['to']) ? $date['to'] : date('Y/m/d' ,strtotime('-1 day')));
            $inputs['from'] = $request->get("from", isset($date['from']) ? $date['from'] : date('Y/m/d', strtotime($inputs['to']." -2 weeks")));

            if($inputs['from'] > $inputs['to']) {
                return redirect()->back()->with('alert-danger', trans('message.error_date_ranger'));
            }

            if(!$service_code) {
                $service_code = config('constants.service.facebook');
            } else {
                //check available service
                $check = false;
                foreach (config('constants.service') as $name => $code) {
                    if($service_code == $code) {
                        $check = true;
                    }
                }
                if(!$check) {
                    abort(404);
                }
            }
            //check available access token
            foreach ($user->auth as $auth) {
                if($auth->service_code == $service_code) {
                    $check_name = 'checkAccessToken'.ucfirst($this->services[$auth->service_code]);
                    $this->$check_name($auth);
                }
            }

            $pageList = $authAccount = $pageCompetitor = $totalPage = [];
            //get data pages by date
            $auths          = $this->repAuth->getUserAuth($user_id, $service_code);
            foreach ($auths as $auth) {
                if($auth->rival_flg == 0 && !$auth->access_token) {
                    continue;
                }
                $authAccount[$auth->service_code][$auth->id] = $auth->email ? $auth->account_name.' ('.$auth->email.')' : $auth->account_name;
                foreach ($auth->page as $page) {
                    //info page
                    if($auth->rival_flg == 0){
                        $pageList[$page->id] = $page;
                    }else{
                        $pageCompetitor[$page->id] = $page;
                    }
                    //get total from page detail
                    $pageDetail = $this->repPageDetail->getLastDate($page->id);
                    $totalPage[$page->id] = [
                        'friends_count'     => @$pageDetail->friends_count ? $pageDetail->friends_count : 0,
                        'posts_count'       => @$pageDetail->posts_count ? $pageDetail->posts_count : 0,
                        'followers_count'   => @$pageDetail->followers_count ? $pageDetail->followers_count : 0,
                        'favourites_count'  => @$pageDetail->favourites_count ? $pageDetail->favourites_count : 0
                    ];
                }
            }
            $service_data['service_code'] = $service_code;
            $service_data['service_name'] = $this->services[$service_code];
            return response(view('service.dashboard')->with([
                'service_data'      => $service_data,
                'authAccount'       => $authAccount,
                'dates'             => $inputs,
                'pageList'          => $pageList,
                'totalPage'         => $totalPage,
                'user'              => $user,
                'pageCompetitor'    => $pageCompetitor,
            ]))->withCookie(cookie()->make('date_search', ['from' => $inputs['from'], 'to' => $inputs['to']]));
        } else {
            return redirect('user')->with('alert-danger', trans('message.exiting_error', ['name' => trans('default.user')]));
        }
    }

    public function getGraphData(Request $request, $service_code, $user_id) {
        if($request->has('dateFrom') && $request->has('dateTo') && $service_code && $user_id){
            $startDate  = $request->get('dateFrom');
            $endDate    = $request->get('dateTo');
            if(strtotime($endDate) < strtotime($startDate)) {
                return Response::json(array(
                    'success' => false,
                    'message' => trans('message.error_date_ranger')), 200);
            }
            $postByDay = $maxPost = [];
            //get data pages by date
            $user_current   = Auth::user();
            $user           = $this->repUser->getById($user_id);
            if($user_current->id == $user_id || ($user_current->authority == config('constants.authority.admin') && $user)) {
                $startDate  = date('Y-m-d', strtotime($startDate));
                $endDate    = date('Y-m-d', strtotime($endDate));
                $auths      = $this->repAuth->getUserAuth($user_id, $service_code);
                foreach ($auths as $auth) {
                    if($auth->rival_flg == 0 && !empty($auth->access_token)) {
                        switch ($auth->service_code) {
                            case config('constants.service.facebook'):
                                $repPostDetail  = $this->repPostFbDetail;
                                break;
                            case config('constants.service.twitter'):
                                $repPostDetail  = $this->repPostTwDetail;
                                break;
                            case config('constants.service.instagram'):
                                $repPostDetail  = $this->repPostIgDetail;
                                break;
                            default: $request->session()->flash('alert-danger', trans('message.exiting_service'));
                        }
                        if(isset($repPostDetail)) {
                            foreach ($auth->page as $page) {
                                //get info post of pages of account, competitor page will load ajax to page controller
                                //post data by page
                                $beforeDate = date('Y-m-d', strtotime("-1 day", strtotime($startDate)));
                                $postDetail = $repPostDetail->getPostEngagementByDate($page->id, null, $beforeDate, $endDate);
                                $postByDay[$page->id]   = $this->getData($page, $postDetail, $beforeDate, $endDate);
                                $maxPost[$page->id]     = Common::getMaxGraph($postByDay[$page->id]);
                            }
                        }
                    }
                }
                return Response::json(array('success' => true,
                    'postByDay'         => $postByDay,
                    'maxGraph'          => $maxPost
                ), 200)->withCookie(cookie()->make('date_search', ['from' => $startDate, 'to' => $endDate]));
            }
            return Response::json(array('success' => false, 'message' => trans('message.exiting_error', ['name' => trans('default.user')])), 200);
        }
        return Response::json(array('success' => false, 'message' => trans('message.common_error')), 200);
    }

    public function getData($page, $listDetail, $startDate, $endDate){
        $data       = [];
        $days       = floor((strtotime($endDate) - strtotime($startDate)) / (60*60*24));
        for($i=0; $i <= $days; $i++) {
            $day = date('Y-m-d', strtotime("+".$i." day", strtotime($startDate)));
            $data[$day]['total']    = 0;
            $data[$day]['compare']  = 0;
        }
        $page_create_at = date('Y-m-d' ,strtotime($page->created_at));
        foreach ($listDetail as $i => $postDetail) {
            $data[$postDetail->date]['total'] = $postDetail->post_engagement;
            if($page_create_at < $postDetail->date && $startDate != $postDetail->date) {
                $beforeDate     = date('Y-m-d' ,strtotime("-1 day", strtotime($postDetail->date)));
                $beforeDayVal   = $data[$beforeDate]['total'];
                $thisDayVal     = $data[$postDetail->date]['total'];
                $compare        = $thisDayVal - $beforeDayVal;
                $data[$postDetail->date]['compare'] = ($compare > 0) ? $compare : 0;
            }
        }
        if(@$data[$startDate]) {
            unset($data[$startDate]);
        }
        return $data;
    }

    public function checkAccessTokenFacebook($auth) {
        $result = true;
        $fb     = new Facebook([
            'app_id'                => config('services.facebook.client_id'),
            'app_secret'            => config('services.facebook.client_secret'),
            'default_graph_version' => 'v2.8',
            'grant_type'            => 'fb_exchange_token',
        ]);
        try{
            $accessToken    = $auth['access_token'];
            $fb->getRedirectLoginHelper();
            $response       = $fb->get('/me?fields=id,name,email', $accessToken);
            $user           = $response->getGraphUser();
            if(!isset($user['accounts'])){
                $result     = false;
            }
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            $this->repAuth->resetAccessToken($auth->id);
            $result = false;
        } catch(FacebookSDKException $e) {
            Log::info('Dashboard controller check accesstoken Facebook, auth ID '.$auth->id.': '. $e->getMessage());
        }

        return $result;
    }

    public function checkAccessTokenTwitter($auth) {
        $result = true;
        $client_id          = config('services.twitter.client_id');
        $client_secret      = config('services.twitter.client_secret');
        $connection         = new TwitterOAuth($client_id, $client_secret, $auth->access_token, $auth->refresh_token);
        $connection->get("account/verify_credentials");
        try{
            if (200 != $connection->getLastHttpCode()){
                $this->repAuth->resetAccessToken($auth->id);
                $result = false;
            }
        } catch (TwitterOAuthException $e) {
            Log::info('Dashboard controller check accesstoken Twitter, auth ID '.$auth->id.': '. $e->getMessage());
        }
        return $result;
    }

    public function checkAccessTokenInstagram($auth) {
        $result     = true;
        $url        = str_replace('{id}',$auth->account_id,config('instagram.url.user_info')).'?access_token='.$auth->access_token;
        $dataGet    = Common::getContent($url);
        if(!$dataGet){
            $this->repAuth->resetAccessToken($auth->id);
            $result = false;
        }
        return $result;
    }
}
