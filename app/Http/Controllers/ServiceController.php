<?php

namespace App\Http\Controllers;

use App\Repositories\MasterRepository;
use App\Repositories\AuthRepository;
use App\Repositories\ServiceRepository;
use App\Http\Requests;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $repMaster;
    protected $repUser;
    protected $repAuth;
    protected $repService;
    protected $contractStatus;
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
        ServiceRepository $service
    )
    {
        $this->repMaster    = $master;
        $this->repUser      = $user;
        $this->repAuth      = $auth;
        $this->repService   = $service;
        $this->middleware(['auth']);
    }

    public function dashboard(Request $request, $user_id = null)
    {
        $user_current   = Auth::user();
        $authority      = config('constants.authority');
        if((!$user_id || $user_current->id == $user_id) && $user_current->authority == $authority['admin']) {
            return redirect('user');
        }
        if(!$user_id || $user_current->authority == $authority['client']) {
            $user_id    = $user_current->id;
        }
        $user           = $this->repUser->getById($user_id);
        if($user) {
            $inputs         = $request->all();
            $services       = [];
            foreach ($user->auth as $auth) {
                if($auth->access_token) {
                    $services[] = $auth->service_code;
                }
            }
            if(!@$inputs['to']) {
                $inputs['to']   = date('Y/m/d');
            }
            if(!@$inputs['from']) {
                $inputs['from'] = date('Y/m/d', strtotime($inputs['to']." -2 weeks"));
            }
            $fromDate   = date('Y-m-d' ,strtotime($inputs['from']));
            $toDate     = date('Y-m-d' ,strtotime($inputs['to']));
            //get data pages by date
            $pageData       = $this->repService->getListPage($user_id);
            $totalPageData  = $this->repService->getTotalPage($user_id, $fromDate, $toDate);
            $pagePerDayData = $this->repService->getListPageDetail($user_id, null, $fromDate, $toDate);

            $pageList = $pagePerDay = $totalPage = [];
            //prepare data
            $timeFrom   = strtotime($inputs['from']);
            $timeto     = strtotime($inputs['to']);
            $days       = floor(($timeto - $timeFrom) / (60*60*24));
            $dataByday  = [];

            foreach ($pagePerDayData as $page) {
                $dataByday[$page->service_code][$page->page_id][$page->date] = (array) $page;
            }
            foreach ($totalPageData as $page) {
                $totalPage[$page->service_code][$page->page_id] = (array) $page;
            }

            //list page and data for them
            $dataItem['friends_count']      = 0;
            $dataItem['posts_count']        = 0;
            $dataItem['followers_count']    = 0;
            $dataItem['favourites_count']   = 0;
            foreach ($pageData as $page) {
                $pageList[$page->service_code][$page->page_id] = (array) $page;
                //set data per days
                for($i=0;$i <= $days; $i++) {
                    $day = date('Y-m-d' ,strtotime("+".$i." day", $timeFrom));
                    if(isset($dataByday[$page->service_code][$page->page_id][$day])) {
                        $pagePerDay[$page->service_code][$page->page_id][] = $dataByday[$page->service_code][$page->page_id][$day];
                    } else {
                        $dataItem['date'] = $day;
                        $pagePerDay[$page->service_code][$page->page_id][] = $dataItem;
                    }
                }
            }
            return view('service.dashboard')->with([
                'services'      => $services,
                'dates'         => $inputs,
                'user'          => $user,
                'pageList'      => $pageList,
                'pagePerDay'    => $pagePerDay,
                'totalPage'     => $totalPage,
            ]);
        } else {
            return redirect('user')->with('alert-danger', trans('message.exiting_error', ['name' => trans('default.user')]));
        }
    }
}
