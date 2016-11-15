<?php
/**
 * Created by PhpStorm.
 * User: nguyen.duc.quyet
 * Date: 15/11/2016
 * Time: 10:00
 */
namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use App\Repositories\AccountRepository;
use App\Http\Requests\UserRequest;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Lang;
use Abraham\TwitterOAuth\TwitterOAuth;


class AccountController extends Controller
{
    protected $repAuth;
    protected $repAcc;
    public function __construct(
        AuthRepository $auth,
        AccountRepository $acc
    )
    {
        $this->repAuth      = $auth;
        $this->repAcc       = $acc;
        $this->middleware('auth');
//        $this->middleware('authority', ['except' => ['searchAccount', 'show']]);
    }

    public function show()
    {
        return view('account.list_account');
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
        $connection         = new TwitterOAuth($client_id, $client_secret, $auth->access_token, $auth->refresh_token);
        try{
            $user_detail    = $connection->get("users/show", array("screen_name" => $input['nickname']));
            if (200 == $connection->getLastHttpCode()){
                $input_insert = [
                    'account_id'        => $user_detail->id,
                    'account_name'      => $user_detail->name,
                    'screen_name'       => $user_detail->screen_name,
                    'location'          => @$user_detail->location,
                    'description'       => @$user_detail->description,
                    'avatar_url'        => @$user_detail->profile_image_url,
                    'created_time'      => date("Y-m-d H:i:s",strtotime(@$user_detail->created_at)),
                    'followers_count'   => @$user_detail->followers_count,
                    'friends_count'     => @$user_detail->friends_count,
                    'listed_count'      => @$user_detail->listed_count,
                    'favourites_count'  => @$user_detail->favourites_count,
                    'statuses_count'    => @$user_detail->statuses_count,
                ];
                $this->repAcc->store($input_insert);
            } else {
                $this->repAuth->resetAccessToken($auth->id);
            }
        } catch (TwitterOAuthException $e) {
            return $this->error('message1',"result null");
        }
        return redirect('/account');
    }

    public function checkAccountInstagram($input)
    {

    }



}
