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

class PageController extends Controller
{
    protected $repPage;
    protected $repPageDetail;
    protected $repAuth;
    protected $repUser;
    protected $repService;
    protected $repPostFb;
    protected $repPostTw;
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
        PostTwitterRepository $post_tw,
        PostInstagramRepository $post_ig
    )
    {
        $this->repPage      = $page;
        $this->repUser      = $user;
        $this->repAuth      = $auth;
        $this->repService   = $service;
        $this->repPageDetail = $page_detail;
        $this->repPostFb        = $post_fb;
        $this->repPostTw        = $post_tw;
        $this->repPostIg        = $post_ig;
        $this->middleware('auth');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = $this->repPage->getById($id);
        if(!$page){
            return $this->rediect('/dashboard')->with('alert-danger', trans('message.error_page_not_found'));
        }
        $auth = $this->repAuth->getById($page->auth_id);
        $services = config('constants.service');
        $name_src = array_search($auth->service_code,$services);
        $pageInfo = $this->repPageDetail->getLast($page->id);
        $condition = config('constants.condition_filter_page');

        switch ($page->auth->service_code) {
            case config('constants.service.facebook'): {
                $repPost       = $this->repPostFb;
            } break;
            case config('constants.service.twitter'): {
                $repPost       = $this->repPostTw;
            } break;
            case config('constants.service.instagram'): {
                $repPost       = $this->repPostIg;
            } break;
            default: return redirect('dashboard')->with('alert-danger', trans('message.exiting_service'));
        }
        $date  = $repPost->getMaxDate($page->id) ;
        $listPosts      = $repPost->getListPostByPage($page->id, $date->max_date);

        $servicesCode   = $page->auth->service_code;
        $checkTime = array();
        foreach($listPosts as $getTime)
        {
            $time = strtotime($getTime->created_time);
            $timeNotification= $this->checkTime($time);
            array_push($checkTime,$timeNotification );
        }
        return view('page.index')->with([
            'pageInfo'      => $pageInfo,
            'nameService'   => $name_src,
            'condition'     => $condition,
            'listPosts'     => $listPosts,
            'serviceCode'   => $servicesCode,
            'checkTime'     => $checkTime
        ]);
    }

    public function checkTime($time_ago){
        $cur_time = time();
        $time_elapsed = $cur_time - $time_ago;
        $seconds = $time_elapsed ;
        return $seconds;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getGraphData(Request $request, $page_id)
    {
        $countType  = $request->get('typeDraw');
        $startDate  = $request->get('dateFrom');
        $endDate    = $request->get('dateTo');

        if (@$startDate && @$endDate) {
            $startDate  = date('Y-m-d' ,strtotime($startDate));
            $endDate    = date('Y-m-d' ,strtotime($endDate));
        } else {
            $startDate  = Carbon::parse('-2 weeks')->toDateString();
            $endDate    = Carbon::today()->toDateString();
        }
        $days           = floor((strtotime($endDate) - strtotime($startDate)) / (60*60*24));
        $data           = array();
        $pageDetail     = $this->repPageDetail->getPageByDate($page_id, $startDate, $endDate);
        $condition      = config('constants.condition_filter_page');
        /*data graph page*/
        for($i=0;$i <= $days; $i++) {
            $day = date('Y-m-d' ,strtotime("+".$i." day", strtotime($startDate)));
            $data[$day] = [
                'count' => 0,
                'count_compare' => 0,
            ];
        }
        foreach ($pageDetail as $key => $detail) {
            $val_condition = $condition[$countType].'_count';
            $data[$detail->date]['count'] = $detail->$val_condition;
            if (($detail->date > date('Y-m-d' ,strtotime($startDate)))) {
                $beforeDate = date('Y-m-d' ,strtotime("-1 day", strtotime($detail->date)));
                $beforeDayVal   = $data[$beforeDate]['count'];
                $thisDayVal     = $data[$detail->date]['count'];
                $compare = $thisDayVal - $beforeDayVal;
                $data[$detail->date]['count_compare'] = ($compare > 0) ? $compare : 0;

            }
        }

//        Log::info(print_r($data), true));
//        echo json_encode(array('success' => true, 'contentCount' => $data));exit();
        return Response::json(array('success' => true, 'contentCount' => $data), 200);

    }

    public function getGraphDataPost(Request $request, $page_id)
    {
        $startDate  = $request->get('dateFrom');
        $endDate    = $request->get('dateTo');
        $days       = floor((strtotime($endDate) - strtotime($startDate)) / (60*60*24));

        $serviceOfPage = $this->repPage->checkServicePage($page_id)->service_code;
        if ($serviceOfPage) {
            if (config('constants.service.facebook') == $serviceOfPage) {
                $columns = [
                    'like_count',
                    'comment_count',
                    'share_count'
                ];
                $repPost       = $this->repPostFb;
            }
            if (config('constants.service.twitter') == $serviceOfPage) {
                $columns = [
                    'retweet_count',
                    'favorite_count'
                ];
                $repPost       = $this->repPostTw;
            }
            if (config('constants.service.instagram') == $serviceOfPage) {
                $columns = [
                    'like_count',
                    'comment_count'
                ];
                $repPost       = $this->repPostIg;
            }
            $listPostByDate = $repPost->getListPostByDate($page_id, null, $startDate, $endDate);
        }

        $postPerDay = [];
        for($i=0;$i <= $days; $i++) {
            $day = date('Y-m-d' ,strtotime("+".$i." day", strtotime($startDate)));
            $postPerDay[$day]['total'] = 0;
            $postPerDay[$day]['compare'] = 0;
        }
        foreach ($listPostByDate as $i => $postDetail) {
            foreach ($columns as $column) {
                $postPerDay[$postDetail->date]['total'] += $postDetail->$column;
            }
            $startDate = date('Y-m-d' ,strtotime($startDate));
            if ($startDate != $postDetail->date){
                $beforeDate = date('Y-m-d' ,strtotime("-1 day", strtotime($postDetail->date)));
                $beforeDayVal   = $postPerDay[$beforeDate]['total'];
                $thisDayVal     = $postPerDay[$postDetail->date]['total'];
                $compare        = $thisDayVal - $beforeDayVal;
                $postPerDay[$postDetail->date]['compare'] = ($compare > 0) ? $compare : 0;
            }
        }

        return Response::json(array('success' => true, 'contentCount' => $postPerDay), 200);
    }
}
