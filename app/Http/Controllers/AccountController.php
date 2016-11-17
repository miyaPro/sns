<?php
/**
 * Created by PhpStorm.
 * User: nguyen.duc.quyet
 * Date: 15/11/2016
 * Time: 10:00
 */
namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use App\Http\Requests\UserRequest;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Lang;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Artisan;

class AccountController extends Controller
{
    protected $repAuth;
    public function __construct(
        AuthRepository $auth
    )
    {
        $this->repAuth      = $auth;
        $this->middleware('auth');
//        $this->middleware('authority', ['except' => ['searchAccount', 'show']]);
    }

    public function index(Request $request)
    {
        $curent_date = Carbon::today()->toDateString();
        $keyword        = $request->get('keyword','');
        $perPage        = config('constants.per_page');
        $data = $this->repAuth->getRival($keyword, $perPage[1], $curent_date);
        return view('account.list_account')->with([
            'data' => $data
        ]);
    }

    public function searchAccount(Request $request)
    {
        $service = config('constants.service');
        $social = array();
        foreach ($service as $key => $value) {
            $social[$key] = ucfirst($key);
        }
        $input = $request->all();
        if($input) {
            $account_func = 'checkAccount'.ucfirst($input['typeSociale']);
            return $this->$account_func($input);
        }
        return view('account.check_account')->with([
            'social' => $social
        ]);
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
                        'account_name'      => $user_detail->name,
                        'service_code'      => $service,
                        'rival_flg'         => 1,
                        'access_token'      => '',
                        'refresh_token'     => '',
                    ];
                    $authUser = $this->repAuth->getAuth($user_id, $user_detail->id, $service);
                    if ($authUser) {
                        $input_update = [
                            'access_token'      => '',
                            'refresh_token'     => '',
                        ];
                        $this->repAuth->update($authUser, $input_update);
                    }else {
                        $this->repAuth->store($input_insert);
                    }
                    Artisan::call('twitter', [
                        'account_id' => $input_insert['account_id'],
                        'today'      => true
                    ]);
                } else {
                    $this->repAuth->resetAccessToken($auth->id);
                }
            } catch (TwitterOAuthException $e) {
                return $this->error('message1',"result null");
            }
            return redirect('/account');
        }
    }

    public function checkAccountInstagram($input)
    {

    }



}
