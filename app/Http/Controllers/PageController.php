<?php

namespace App\Http\Controllers;

use App\PageDetail;
use App\Repositories\AuthRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\UserRepository;
use App\Repositories\PageRepository;
use App\Repositories\PageDetailRepository;
use App\Repositories\PostFacebookRepository;
use App\Repositories\PostFacebookDetailRepository;
use App\Repositories\PostTwitterRepository;
use App\Repositories\PostTwitterDetailRepository;
use App\Repositories\PostInstagramRepository;
use App\Repositories\PostInstagramDetailRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Common\Common;

class PageController extends Controller
{
    protected $repPage;
    protected $repPageDetail;
    protected $repAuth;
    protected $repUser;
    protected $repService;
    protected $repPostFb;
    protected $repPostDetailFb;
    protected $repPostTw;
    protected $repPostDetailTw;
    protected $repPostDetailIg;
    protected $repPostIg;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(
        PageRepository $page,
        ServiceRepository $service,
        AuthRepository $auth,
        UserRepository $user,
        PageDetailRepository $page_detail,
        PostFacebookRepository $post_fb,
        PostFacebookDetailRepository $post_detail_fb,
        PostTwitterRepository $post_tw,
        PostTwitterDetailRepository $post_detail_tw,
        PostInstagramDetailRepository $post_detail_ig,
        PostInstagramRepository $post_ig
    )
    {
        $this->repPage          = $page;
        $this->repUser          = $user;
        $this->repAuth          = $auth;
        $this->repService       = $service;
        $this->repPageDetail    = $page_detail;
        $this->repPostFb        = $post_fb;
        $this->repPostDetailFb  = $post_detail_fb;
        $this->repPostTw        = $post_tw;
        $this->repPostDetailTw  = $post_detail_tw;
        $this->repPostIg        = $post_ig;
        $this->repPostDetailIg = $post_detail_ig;
        $this->middleware('auth');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return
     */
    public function show($id)
    {
        $user   = Auth::user();
        $page = $this->repPage->getById($id);
        if($page){
            $auth = $this->repAuth->getById($page->auth_id);
            if(!$auth->rival_flg && (($user->id == $auth->user_id) || ($user->authority == config('constants.authority.admin')))){
                $services = config('constants.service');
                $name_src = array_search($auth->service_code,$services);
                $pageInfo = $this->repPageDetail->getLastDate($page->id);
                $condition = config('constants.condition_filter_page');
                switch ($auth->service_code){
                    case config('constants.service.facebook'):
                        $repPost       = $this->repPostFb;
                        break;
                    case config('constants.service.twitter'):
                        $repPost       = $this->repPostTw;
                        break;
                    case config('constants.service.instagram'):
                        $repPost       = $this->repPostIg;
                        break;
                    default:
                        return  redirect('dashboard/001')->with('alert-danger', trans('message.not_exiting_service'));
                }
                $current_date = Carbon::today()->toDateString();
                $pageCurrent = $this->repPageDetail->getPageByDate($page->id, $current_date, $current_date);
                if(!$pageCurrent){
                    Artisan::call($name_src, [
                        'today'      => 1,
                        'account_id' => $auth->account_id,
                    ]);
                }
                $listPosts      = $repPost->getListPostByPage($page->id);
                $servicesCode   = $page->auth->service_code;
                return view('page.index')->with([
                    'pageInfo'      => $pageInfo,
                    'nameService'   => $name_src,
                    'condition'     => $condition,
                    'listPosts'     => $listPosts,
                    'serviceCode'   => $servicesCode,
                ]);
            }
        }
        return redirect('dashboard/001')->with('alert-danger', trans('message.error_page_not_found'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param
     * @return
     */
    public function getGraphData(Request $request, $page_id)
    {
        if($request->has('dateFrom') && $request->has('dateTo') && $request->has('typeDraw')){
            $startDate = $request->get('dateFrom');
            $endDate = $request->get('dateTo');
            $countType = $request->get('typeDraw');
            $condition = config('constants.condition_filter_page');
            $user      = Auth::user();

            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
            if(strtotime($endDate) < strtotime($startDate)){
                return Response::json(array(
                    'success' => false,
                    'message' => trans('message.error_date_ranger')), 200);
            }
            $page = $this->repPage->getById($page_id);
            if(array_key_exists($countType, $condition)){
                if($page){
                    $auth = $this->repAuth->getById($page->auth_id);
                    if($auth && ($auth->user_id == $user->id || $user->authority == config('constants.authority.admin'))){
                        $days = floor((strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24));
                        $data = array();
                        $inputs = array(
                            'from' => $startDate,
                            'to' => $endDate,
                        );
                        $startDate = date('Y-m-d', strtotime("-1 day", strtotime($startDate)));
                        $pageDetail = $this->repPageDetail->getPageByDate($page_id, $startDate, $endDate);
                        /*data graph page*/
                        for ($i = 0; $i <= $days + 1; $i++){
                            $day = date('Y-m-d', strtotime("+" . $i . " day", strtotime($startDate)));
                            $data[$day] = [
                                'count' => 0,
                                'count_compare' => 0,
                                'count_change' => 0,
                            ];
                        }
                        $startDateByDb = date('Y-m-d', strtotime($page->created_at));
                        if($pageDetail){
                            $val_condition = $condition[$countType] . '_count';
                            foreach ($pageDetail as $key => $detail){
                                $data[$detail->date]['count'] = $detail->$val_condition;
                                if($detail->date > $startDateByDb && $detail->date > $startDate){
                                    $beforeDate = date('Y-m-d', strtotime("-1 day", strtotime($detail->date)));
                                    $compare = $data[$detail->date]['count'] - $data[$beforeDate]['count'];
                                    $data[$detail->date]['count_compare'] = ($compare > 0) ? $compare : 0;
                                    if ($startDate < $startDateByDb) {
                                        $change = $data[$detail->date]['count'] - $data[$startDateByDb]['count'];
                                    }else {
                                        $change = $data[$detail->date]['count'] - $data[$startDate]['count'];
                                    }
                                    $data[$detail->date]['count_change'] = ($change > 0) ? $change : 0;
                                }
                            }
                            if(isset($data[$startDateByDb])){
                                $data[$startDateByDb]['count'] = 0;
                            }
                            if(isset($data[$startDate])){
                                unset($data[$startDate]);
                            }
                            $maxValuePage = Common::getMaxGraph($data);
                            return Response::json(array('success' => true,
                                'contentCount' => $data,
                                'maxValueData' => $maxValuePage), 200)
                                ->withCookie(cookie()->make('date_search', ['from' => $inputs['from'], 'to' => $inputs['to']]));
                        }
                    }
                }
            }
        }
        return Response::json(array('success' => false, 'message' => trans('message.common_error')), 200);
    }

    public function getGraphDataPost(Request $request, $page_id)
    {
        if($request->has('dateFrom') && $request->has('dateTo')){
            $startDate = $request->get('dateFrom');
            $endDate = $request->get('dateTo');
            $user = Auth::user();
            if(strtotime($endDate) < strtotime($startDate)){
                return Response::json(array(
                    'success' => false,
                    'message' => trans('message.error_date_ranger')), 200);
            }
            $inputs = array(
                'from' => $startDate,
                'to' => $endDate,
            );
            $startDate = date('Y-m-d', strtotime("-1 day", strtotime($startDate)));
            $endDate = date('Y-m-d', strtotime($endDate));

            $days = floor((strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24));
            $page = $this->repPage->getById($page_id);
            if($page){
                $auth = $this->repAuth->getById($page->auth_id);
                if($auth && ($auth->user_id == $user->id || $user->authority == config('constants.authority.admin'))){
                    $service_code = $auth->service_code;
                    if($service_code){
                        if(config('constants.service.facebook') == $service_code){
                            $listPostEngagement = $this->repPostDetailFb->getPostEngagementByDate($page_id, null, $startDate, $endDate);
                        }
                        else if(config('constants.service.twitter') == $service_code){
                            $listPostEngagement =  $this->repPostDetailTw->getPostEngagementByDate($page_id, null, $startDate, $endDate);
                        }
                        else if(config('constants.service.instagram') == $service_code){
                            $listPostEngagement = $this->repPostDetailIg->getPostEngagementByDate($page_id, null, $startDate, $endDate);
                        }

                        if(isset($listPostEngagement)){
                            $postPerDay = [];
                            for ($i = 0; $i <= $days; $i++){
                                $day = date('Y-m-d', strtotime("+" . $i . " day", strtotime($startDate)));
                                $postPerDay[$day]['total'] = 0;
                                $postPerDay[$day]['compare'] = 0;
                                $postPerDay[$day]['change'] = 0;
                            }
                            $startDateByDb = date('Y-m-d', strtotime($page->created_at));
                            $startDate = date('Y-m-d', strtotime($startDate));
                            foreach ($listPostEngagement as $i => $postDetail){
                                $postPerDay[$postDetail->date]['total'] = $postDetail->post_engagement;
                                if($postDetail->date > $startDate && $postDetail->date > $startDateByDb){
                                    $beforeDate = date('Y-m-d', strtotime("-1 day", strtotime($postDetail->date)));
                                    $beforeDayVal = $postPerDay[$beforeDate]['total'];
                                    $thisDayVal = $postPerDay[$postDetail->date]['total'];
                                    $compare = $thisDayVal - $beforeDayVal;
                                    if ($startDate < $startDateByDb){
                                        $startDayVal = $postPerDay[$startDateByDb]['total'];
                                    }else{
                                        $startDayVal = $postPerDay[$startDate]['total'];
                                    }
                                    $change = $thisDayVal - $startDayVal;
                                    $postPerDay[$postDetail->date]['compare'] = ($compare > 0) ? $compare : 0;
                                    $postPerDay[$postDetail->date]['change']  = ($change > 0) ? $change : 0;
                                }
                            }
                            if(isset($postPerDay[$startDateByDb])){
                                $postPerDay[$startDateByDb]['total'] = 0;
                            }
                            if(isset($postPerDay[$startDate])){
                                unset($postPerDay[$startDate]);
                            }
                            $maxValuePost = Common::getMaxGraph($postPerDay);
                            return Response::json(array(
                                'success' => true,
                                'contentCount' => $postPerDay,
                                'maxValueData' => $maxValuePost), 200)
                                ->withCookie(cookie()->make('date_search', ['from' => $inputs['from'], 'to' => $inputs['to']]));
                        }
                    }
                }
            }
        }
        return Response::json(array('success' => false, 'message' => trans('message.common_error')), 200);
    }

}
