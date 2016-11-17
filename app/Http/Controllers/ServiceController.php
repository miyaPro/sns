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
    }

    public function dashboard(Request $request, $user_id = null)
    {
        $user_current   = Auth::user();
        $authority      = config('constants.authority');
        if((!$user_id || $user_current->id == $user_id) && $user_current->authority == $authority['admin']) {
            return redirect('user');
        }
        if($user_current->authority == $authority['client']){
            if(!$user_id){
                $user_id    = $user_current->id;
            }else{
                abort(404);
            }
        }
        $user           = $this->repUser->getById($user_id);
        if($user) {
            $inputs         = $request->all();
            if(!@$inputs['to']) {
                $inputs['to']   = date('Y/m/d');
            }
            if(!@$inputs['from']) {
                $inputs['from'] = date('Y/m/d', strtotime($inputs['to']." -2 weeks"));
            }
            $fromDate   = date('Y-m-d' ,strtotime($inputs['from']));
            $toDate     = date('Y-m-d' ,strtotime($inputs['to']));
            $services   = $pageList = $postByDay = $totalPage = $authAccount = [];
            //get data pages by date
            foreach ($user->auth as $auth) {
                if($auth->access_token) {
                    $services[] = $auth->service_code;
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
                $pages = $this->repPage->getAllByField('auth_id', $auth->id);
                foreach ($pages as $page) {
                    //info page
                    $pageList[$auth->service_code][$page->id] = $page;
                    if(isset($repPost)) {
                        //post data by page
                        $beforeDate = date('Y-m-d' ,strtotime("-1 day", strtotime($fromDate)));
                        $postDetail = $repPost->getListPostByDate($page->id, null, $beforeDate, $toDate);
                        $postByDay[$auth->service_code][$page->id] = $this->getData($page, $postDetail, $columns, $beforeDate, $toDate);
                    }

                    //get total from page detail
                    $pageDetail = $this->repPageDetail->getLastDate($page->id);
                    $totalPage[$auth->service_code][$page->id] = [
                        'friends_count'     => @$pageDetail->friends_count ? $pageDetail->friends_count : 0,
                        'posts_count'       => @$pageDetail->posts_count ? $pageDetail->posts_count : 0,
                        'followers_count'   => @$pageDetail->followers_count ? $pageDetail->followers_count : 0,
                        'favourites_count'  => @$pageDetail->favourites_count ? $pageDetail->favourites_count : 0
                    ];
                }
            }
            return response(view('service.dashboard')->with([
                'services'      => $services,
                'authAccount'   => $authAccount,
                'dates'         => $inputs,
                'pageList'      => $pageList,
                'postByDay'     => $postByDay,
                'totalPage'     => $totalPage,
                'user'          => $user
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
}
