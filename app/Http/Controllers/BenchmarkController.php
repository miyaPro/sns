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
        foreach ($service as $key => $value) {
            $name = 'name_'.$lang;
            $social[$value->$name] = ucfirst($value->$name);
        }
        return view('benchmark.create')->with([
            'social' => $social
        ]);
    }

    public function store(BenchmarkRequest $request)
    {
        $input = $request->all();
        if($input) {
            $account_func = 'checkAccount'.ucfirst($input['typeSociale']);
            return $this->$account_func($input);
        }
    }

    public function destroy($id)
    {
        $user_id            = Auth::user()->id;
        $auth        = $this->repAuth->getById($id);
        $page        = $this->repPage->getOneByField('auth_id', $id);
        $page_detail = $this->repPageDetail->getAllByField('page_id', $page->id);
        $arr_page_detail_id = array();
        foreach ($page_detail as $value) {
            $arr_page_detail_id[] = $value->id;
        }
        if ($user_id == $auth->user_id) {
            $this->repPageDetail->destroyById($arr_page_detail_id);
            $this->repPage->destroy($page->id);
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
            try{
                $user_detail    = $connection->get("users/show", array("screen_name" => $input['nickname']));
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
                            'today'      => true
                        ]);
                    } else {
                        return redirect()->back()->with('alert-danger', trans('message.exists_error'))->withInput($input);
                    }
                } else {
                    return redirect()->back()->with('alert-danger', trans('message.error_get_info_acc'))->withInput($input);
                }
            } catch (TwitterOAuthException $e) {
                return $this->error('message1',"result null");
            }
            return redirect('/benchmark')->with('alert-success', trans('message.benchmark_save_success', ['name' => trans('default.profile')]));;
        } else {
            return redirect()->back()->with('alert-danger', trans('message.token_expired'));
        }
    }

    public function checkAccountInstagram($input)
    {
        $user_id            = Auth::user()->id;
        $service            = config('constants.service.instagram');
        $auth               = $this->repAuth->getFirstAuth($user_id, $service);
        if($auth){
            $url = str_replace('{name}',$input['nickname'], config('instagram.url.search')).'&access_token='.$auth->access_token;
            $dataGet = $this->getContent($url,false);
            $dataGet  = @json_decode($dataGet[0]);
            if(!isset($dataGet)){
                return redirect('/account')->with('alert-danger', trans('message.error_network_connect', ['name' => trans('default.instagram')]));
            }
            else if(isset($dataGet->meta) && $dataGet->meta->code == 200){
                dd($dataGet->data);
            }else{
                $this->repAuth->resetAccessToken($auth->id);
                return redirect('/account')->with('alert-danger', trans('message.error_get_access_token', ['name' => trans('default.instagram')]));
            }
        }
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
