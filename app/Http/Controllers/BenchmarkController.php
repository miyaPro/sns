<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use App\Repositories\MasterRepository;
use App\Repositories\PageRepository;
use App\Repositories\PageDetailRepository;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\BenchmarkRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Lang;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Artisan;
use Abraham\TwitterOAuth\TwitterOAuthException;
use App\Common\Common;

class BenchmarkController extends Controller
{

    protected $repAuth;
    protected $repMaster;
    protected $repPage;
    protected $repPageDetail;
    public function __construct(
        AuthRepository $auth,
        MasterRepository $master,
        PageRepository $page,
        PageDetailRepository $pageDetail

    )
    {
        $this->repAuth        = $auth;
        $this->repMaster      = $master;
        $this->repPage        = $page;
        $this->repPageDetail = $pageDetail;
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user_id        = (Auth::user()->authority != config('constants.authority.admin')) ? Auth::user()->id : '';
        $keyword        = $request->get('keyword','');
        $perPage        = config('constants.per_page');
        $data           = $this->repAuth->getRival($keyword, $perPage[1], $user_id);
        return view('benchmark.index')->with([
            'data'      => $data,
        ]);
    }

    public function create()
    {
        $lang    = Lang::locale();
        $service = $this->repMaster->getService();
        $social  = array();
        if($service && count($service) > 0){
            foreach ($service as $key => $value) {
                $name = 'name_'.$lang;
                $social[$value->$name] = ucfirst($value->$name);
            }
            return view('benchmark.create')->with([
                'social' => $social
            ]);
        }
        return redirect('benchmark')->with('alert-danger', trans('message.not_exiting_service'));
    }

    public function store(BenchmarkRequest $request)
    {
        $input = $request->all();
        if($input) {
            $account_func = 'checkAccount'.ucfirst($input['service_code']);
            return $this->$account_func($input);
        }
    }

    public function destroy($id)
    {
        $user_id      = Auth::user()->id;
        $auth         = $this->repAuth->getById($id);
        if ($auth && $user_id == $auth->user_id) {
            $page        = $this->repPage->getOneByField('auth_id', $id);
            if ($page) {
                $this->repPageDetail->destroyById($page->id);
                $this->repPage->destroy($page->id);
            }
            $this->repAuth->destroy($id);
            return Response::json(array('success' => true), 200);
        }
        $errors['msg'] = trans("message.common_error");
        return Response::json(array(
            'success' => false,
            'errors' => $errors
        ), 400);
    }

    public function checkAccountFacebook($input)
    {

    }

    public function checkAccountTwitter($input)
    {
        $user_id            = Auth::user()->id;
        $service            = config('constants.service.twitter');
        $client_id          = config('services.twitter.client_id');
        $client_secret      = config('services.twitter.client_secret');
        $auth               = $this->repAuth->getFirstAuth($user_id, $service);

        if ($auth) {
            $connection         = new TwitterOAuth($client_id, $client_secret, $auth->access_token, $auth->refresh_token);
            DB::beginTransaction();
            try{
                $user_detail    = $connection->get("users/show", array("screen_name" => $input['account_name']));

                if (200 == $connection->getLastHttpCode()){
                    $input_insert = [
                        'user_id'           => $user_id,
                        'account_id'        => $user_detail->id,
                        'account_name'      => $user_detail->screen_name,
                        'service_code'      => $service,
                        'rival_flg'         => 1,
                    ];
                    $authUser = $this->repAuth->getAuth($user_id, $user_detail->id, $service);
                    if (!$authUser) {
                        $this->repAuth->store($input_insert);
                        Artisan::call('twitter', [
                            'account_id' => $input_insert['account_id'],
                            'today'      => 1
                        ]);
                        DB::commit();
                        return redirect('/benchmark')->with('alert-success', trans('message.benchmark_save_success'));
                    } else {
                        return redirect()->back()->with('alert-danger', trans('message.exists_error'))->withInput($input);
                    }
                } else {
                    return redirect()->back()->with('alert-danger', trans('message.error_get_info_acc'))->withInput($input);
                }
            } catch (TwitterOAuthException $e) {
                DB::rollback();
            } catch (\Exception $e) {
                DB::rollback();
            }
        } else {
            return redirect()->back()->with('alert-danger', trans('message.token_expired'));
        }
        return redirect()->back()->with('alert-danger', trans('message.common_error'));
    }

    public function checkAccountInstagram($input)
    {
        $user_id            = Auth::user()->id;
        $service            = config('constants.service.instagram');
        $auth               = $this->repAuth->getFirstAuth($user_id, $service);
        if($auth){
            $url = str_replace('{name}',$input['account_name'], config('instagram.url.search')).'&access_token='.$auth->access_token;
            $dataGet = Common::getContent($url,false);
            $dataGet  = @json_decode($dataGet[0]);
            if(!isset($dataGet)){
                return redirect()->back()->with('alert-danger', trans('message.common_error'))->withInput($input);
            }
            else if(isset($dataGet->meta) && $dataGet->meta->code == 200 && isset($dataGet->data)){
                if(count($dataGet->data) > 0){
                    $user_detail = $dataGet->data[0];
                    $input_insert = array(
                        'user_id'           => $user_id,
                        'account_id'        => $user_detail->id,
                        'account_name'      => $user_detail->username,
                        'service_code'      => $service,
                        'rival_flg'         => 1,
                    );
                    $authUser = $this->repAuth->getAuth($user_id, $user_detail->id, $service);
                    if (!$authUser) {
                        $this->repAuth->store($input_insert);
                        Artisan::call('instagram', [
                            'today'      => 1,
                            'account_id' => $input_insert['account_id']
                        ]);
                        return redirect('/benchmark')->with('alert-success', trans('message.benchmark_save_success'));
                    } else {
                        return redirect()->back()->with('alert-danger', trans('message.exists_error'))->withInput($input);
                    }
                }
                else{
                    return redirect()->back()->with('alert-danger', trans('message.error_get_info_acc'))->withInput($input);
                }
            }else{
                return redirect()->back()->with('alert-danger', trans('message.error_get_info_acc'))->withInput($input);
            }
        }else {
            return redirect()->back()->with('alert-danger', trans('message.token_expired'))->withInput($input);
        }
    }

}
