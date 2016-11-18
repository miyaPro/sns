<?php

namespace App\Http\Controllers;

use App\Repositories\MasterRepository;
use App\Repositories\AuthRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\PageRepository;
use App\Repositories\PageDetailRepository;
use App\Repositories\PostFacebookRepository;
use App\Repositories\PostTwitterRepository;
use App\Repositories\PostInstagramRepository;
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

class ServiceController extends Controller
{
    protected $repMaster;
    protected $repUser;
    protected $repAuth;
    protected $repService;
    protected $repPage;
    protected $repPageDetail;
    protected $repPostFb;
    protected $repPostTw;
    protected $repPostIg;
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
        PostFacebookRepository $postFb,
        PostTwitterRepository $postTw,
        PostInstagramRepository $postIg
    )
    {
        $this->repMaster        = $master;
        $this->repUser          = $user;
        $this->repAuth          = $auth;
        $this->repPage          = $page;
        $this->repPageDetail    = $pageDetail;
        $this->repService       = $service;
        $this->repPostFb        = $postFb;
        $this->repPostTw        = $postTw;
        $this->repPostIg        = $postIg;
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
            if(!$user_id){
                $user_id = $user_current->id;
            } elseif ($user_id != $user_current->id) {
                abort(404);
            }
        }
        $user            = $this->repUser->getById($user_id);
        if($user) {
            $inputs      = $request->all();
            if(!@$inputs['to']) {
                $inputs['to']   = date('Y/m/d' ,strtotime('-1 day'));
            }
            if(!@$inputs['from']) {
                $inputs['from'] = date('Y/m/d', strtotime($inputs['to']." -2 weeks"));
            }
            $fromDate   = date('Y-m-d' ,strtotime($inputs['from']));
            $toDate     = date('Y-m-d' ,strtotime($inputs['to']));

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

            $pageList = $postByDay = $totalPage = $authAccount = $pageCompetitor = [];
            //get data pages by date
            $auths          = $this->repAuth->getUserAuth($user_id, $service_code);
            $service_data['service_exist']      = true;
            if(!$auths || count($auths) <= 0) {
                $service_data['service_exist']  = false;
            }
            foreach ($auths as $auth) {
                if($auth->rival_flg == 0 && !$auth->access_token) {
                    continue;
                }
                $authAccount[$auth->service_code][$auth->id] = $auth->email ? $auth->account_name.' ('.$auth->email.')' : $auth->account_name;
                switch ($auth->service_code) {
                    case config('constants.service.facebook'): {
                        $repPost    = $this->repPostFb;
                        $columns    = ['like_count', 'comment_count', 'share_count'];
                    } break;
                    case config('constants.service.twitter'): {
                        $repPost    = $this->repPostTw;
                        $columns    = ['retweet_count', 'favorite_count'];
                    } break;
                    case config('constants.service.instagram'): {
                        $repPost    = $this->repPostIg;
                        $columns    = ['like_count', 'comment_count'];
                    } break;
                    default: $request->session()->flash('alert-danger', trans('message.exiting_service'));
                }

                foreach ($auth->page as $page) {
                    //info page
                    if($auth->rival_flg == 0){
                        $pageList[$page->id] = $page;
                    }else{
                        $pageCompetitor[$page->id] = $page;
                    }
                    if(isset($repPost)) {
                        //post data by page
                        $beforeDate = date('Y-m-d' ,strtotime("-1 day", strtotime($fromDate)));
                        $postDetail = $repPost->getListPostByDate($page->id, null, $beforeDate, $toDate);
                        $postByDay[$page->id] = $this->getData($page, $postDetail, $columns, $beforeDate, $toDate);
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
                'postByDay'         => $postByDay,
                'totalPage'         => $totalPage,
                'user'              => $user,
                'pageCompetitor'    => $pageCompetitor,
            ]))->withCookie(cookie()->forever('date_search', [$inputs['from'], $inputs['to']]));
        } else {
            return redirect('user')->with('alert-danger', trans('message.exiting_error', ['name' => trans('default.user')]));
        }
    }

    public function getData($page, $listDetail, $columns = [], $startDate, $endDate){
        $data       = [];
        $days       = floor((strtotime($endDate) - strtotime($startDate)) / (60*60*24));
        for($i=0; $i <= $days; $i++) {
            $day = date('Y-m-d' ,strtotime("+".$i." day", strtotime($startDate)));
            $data[$day]['total']    = 0;
            $data[$day]['compare']  = 0;
        }
        $page_create_at = date('Y-m-d' ,strtotime($page->created_at));
        foreach ($listDetail as $i => $postDetail) {
            //total of columns
            foreach ($columns as $column) {
                $data[$postDetail->date]['total'] += $postDetail->$column;
            }
            //calculation after first item
            if($page_create_at >= $postDetail->date || $startDate == $postDetail->date) {
                $compare    = 0;
            } else {
                $beforeDate     = date('Y-m-d' ,strtotime("-1 day", strtotime($postDetail->date)));
                $beforeDayVal   = $data[$beforeDate]['total'];
                $thisDayVal     = $data[$postDetail->date]['total'];
                $compare    = $thisDayVal - $beforeDayVal;
            }
            $data[$postDetail->date]['compare'] = ($compare > 0) ? $compare : 0;
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

        }
        return $result;
    }

    public function checkAccessTokenInstagram($auth) {
        $result     = true;
        $url        = str_replace('{id}',$auth->account_id,config('instagram.url.user_info')).'?access_token='.$auth->access_token;
        $dataGet    = $this->getContent($url,false);
        $dataAccount = @json_decode($dataGet[0]);
        if(isset($dataGet) && (!isset($dataAccount->meta) || $dataAccount->meta->code != 200)){
            $this->repAuth->resetAccessToken($auth->id);
            $result = false;
        }
        return $result;
    }

    public function getContent($url,$postdata){
        if (!function_exists('curl_init')){
            return 'Sorry cURL is not installed!';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        if ($postdata)
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 ;Windows NT 6.1; WOW64; AppleWebKit/537.36 ;KHTML, like Gecko; Chrome/39.0.2171.95 Safari/537.36");
        $contents = curl_exec($ch);
        $headers = curl_getinfo($ch);
        curl_close($ch);
        return array($contents, $headers);
    }
}
