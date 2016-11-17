<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http;
use App\Http\Requests;
use Facebook\Exceptions\FacebookResponseException as FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException as FacebookSDKException;
use Facebook\Facebook as Facebook;
use App\Repositories\AuthRepository;
//use Illuminate\Support\Facades\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Session;

use Abraham\TwitterOAuth\TwitterOAuth;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Artisan;


/**
 * Created by PhpStorm.
 * User: nguyen.duc.quyet
 * Date: 13/10/2016
 * Time: 09:07
 */

class SocialNetworkController extends Controller
{

    protected $repAuth;
    protected $repPages;
    protected $repPost;

    public function __construct(AuthRepository $reAuth)
    {
        $this->repAuth = $reAuth;

    }

    public function handleFacebook($logout = false) {

        if(!session_id()) {
            session_start();
        }
        $fb = new Facebook([
            'app_id' => config('services.facebook.client_id'),
            'app_secret' => config('services.facebook.client_secret'),
            'default_graph_version' => 'v2.8',
            'grant_type' => 'fb_exchange_token',
        ]);

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email','manage_pages']; // optional
        if($logout) {
            $urlCallback = config('services.facebook.redirect_logout');
        } else {
            $urlCallback = config('services.facebook.redirect');
        }
        $url = $helper->getLoginUrl($urlCallback, $permissions);
        return redirect($url);
    }
    public function handleFacebookCallback($logout = false) {

        if(!session_id()) {
            session_start();
        }
        $fb = new Facebook([
            'app_id' => config('services.facebook.client_id'),
            'app_secret' => config('services.facebook.client_secret'),
            'default_graph_version' => 'v2.8',
            'persistent_data_handler'=>'session',
            'grant_type' => 'fb_exchange_token'
        ]);
        $helper = $fb->getRedirectLoginHelper();
        $_SESSION['FBRLH_state']=$_GET['state'];
        try {
            $user_id        = Auth::user()->id;
            $accessToken    = $helper->getAccessToken();
            $response       =
                $fb->get('/me?fields=id,name,email', $accessToken);
            $userFb         = $response->getGraphUser();
            $authUser       = $this->repAuth->getAuth($user_id, $userFb['id'], config('constants.service.facebook'));
            if($authUser)
            {
                if($logout) {
                    $logout_url = $helper->getLogoutUrl($accessToken, url('social/handleFacebook'));
                    return redirect($logout_url);
                }
                $inputs = [
                    'access_token'      => $accessToken
                ];
                $this->repAuth->update($authUser, $inputs);
//                $process = new Process('php '.env('ARTISAN_PATCH').'artisan'.$authUser['account_id']);
//                $process->start();
                Artisan::call('facebook', [
                    'account_id' => $authUser['account_id'],
                    'today'      => true
                ]);
            }
            else{
                $inputs = [
                    'user_id'           => $user_id,
                    'email'             => $userFb['email'],
                    'account_name'      => $userFb['name'],
                    'account_id'        => $userFb['id'],
                    'access_token'      => $accessToken ,
                    'service_code'      => config('constants.service.facebook')
                ];
                $this->repAuth->store($inputs);
//                $process = new Process('php '.env('ARTISAN_PATCH').'artisan '.$inputs['account_id']);
//                $process->start();
                Artisan::call('facebook', [
                    'account_id' => $inputs['account_id'],
                    'today'      => true
                ]);
            }
        } catch(FacebookResponseException $e) {
            return redirect('/dashboard')->with('alert-danger', trans('message.error_permission_facebook'));

        } catch(FacebookSDKException $e) {
            return redirect('/dashboard')->with('alert-danger', trans('message.error_get_data_facebook'));
        }

        if (isset($accessToken)) {
           // Logged in!
            $_SESSION['facebook_access_token'] = (string) $accessToken;

        }
        return redirect('/dashboard');
    }

    public function handleInstagramCallback(Request $request) {
        if(empty($request['code'])){
            return redirect('/dashboard/')->with('alert-danger', trans('message.error_get_access_token_instagram'));
        }
        $service = config('constants.service.instagram');
        $postdata= array(
            'client_id'=>config('instagram.connections.main.id'),
            'client_secret'=>config('instagram.connections.main.secret'),
            'grant_type'=>'authorization_code',
            'redirect_uri'=>url('/social/handleInstagramCallback'),
            'code'=>$request['code'],
        );
        $url = config('instagram.url.token');
        $dataUser = $this->getContent($url,$postdata);
        $dataUser =json_decode($dataUser[0]);
        $user = Auth::user();
        if(isset($dataUser) && $dataUser->access_token && $dataUser->user){
            $oauth = $this->repAuth->getAuth($user->id, $dataUser->user->id, $service);
            $input = array(
                'user_id'           => $user->id,
                'email'             => '',
                'account_name'      => $dataUser->user->username,
                'account_id'        => $dataUser->user->id,
                'access_token'      => $dataUser->access_token,
                'service_code'      => config('constants.service.instagram'),
                'refresh_token'     =>''
            );
            if($oauth){
                $this->repAuth->update($oauth, $input);
            }else{
                $this->repAuth->store($input);
            }
//            $process = new Process('php '.env('ARTISAN_PATH').'artisan instagram '.$input['account_id']);
//            $process->start();
            Artisan::call('instagram', [
                'account_id' => $input['account_id'],
                'today'      => true
            ]);
        }else{
            Session::flash('alert-danger', trans('message.error_get_access_token_instagram'));
        }
        return redirect('/dashboard');
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

    public function handleInstagram($logout = false) {
        $userInstagram = array(
            'oauth'=>config('instagram.url.oauth'),
            'client_id' => config('instagram.connections.main.id'),
            'redirect_uri' => url('/').config('instagram.url.redirect'),
            'scope'=>config('instagram.scope.access'),
        );
        $parameterUrl = '/?client_id='.$userInstagram['client_id'].'&redirect_uri='.$userInstagram['redirect_uri'].'&response_type=code&scope='.$userInstagram['scope'];
        if($logout){
            $urlLogOut = str_replace(
                '{url}',
                urlencode('/oauth/authorize'.$parameterUrl),
                config('instagram.url.logout')
            );
            return Redirect::to($urlLogOut);
        }else{
            return Redirect::to($userInstagram['oauth'].$parameterUrl);
        }
    }

    public function handleTwitter() {
        session_start();
        $client_id = config('services.twitter.client_id');
        $client_secret = config('services.twitter.client_secret');
        $callback_redirect = config('services.twitter.redirect');
        $connection = new TwitterOAuth($client_id, $client_secret);
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $callback_redirect));
        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        if (@$_SESSION['access_token'] != null) {
            $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token'], 'force_login' => 'true'));
        }else {
            $url = $connection->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));
        }

        return redirect($url);
    }

    public function handleTwitterCallback() {
        session_start();
        $client_id = config('services.twitter.client_id');
        $client_secret = config('services.twitter.client_secret');

        $request_token = array();
        $request_token['oauth_token'] = $_SESSION['oauth_token'];
        $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

        if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
            $_SESSION['oauth_status'] = 'oldtoken';
            die('Oauth token is  not available!');
        }

        $connection = new TwitterOAuth(
            $client_id,
            $client_secret,
            $request_token['oauth_token'],
            $request_token['oauth_token_secret']
        );
        $access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));

        $_SESSION['access_token'] = $access_token;
        unset($_SESSION['oauth_token']);
        unset($_SESSION['oauth_token_secret']);

        /*get info account and store database*/
        $connection = new TwitterOAuth($client_id, $client_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $twitterUser =  $connection->get("account/verify_credentials", array("include_email" => "true"));

        $service = config('constants.service.twitter');
        $user_id = Auth::user()->id;
        $input = [
            'user_id'           => $user_id,
            'email'             => $twitterUser->email,
            'account_name'      => $twitterUser->screen_name,
            'account_id'        => $twitterUser->id,
            'access_token'      => $access_token['oauth_token'],
            'refresh_token'     => $access_token['oauth_token_secret'],
            'service_code'      => $service,
        ];

        $authUser = $this->repAuth->getAuth($user_id, $twitterUser->id, $service);
        if ( $authUser ) {
            $input_update = [
                'access_token'      => $access_token['oauth_token'],
                'refresh_token'     => $access_token['oauth_token_secret'],
            ];
            $this->repAuth->update($authUser, $input_update);
        } else {
            $twitter = $this->repAuth->store($input);
        }

//        $process = new Process('php '.env('ARTISAN_PATH').'artisan twitter '.$input['account_id']);
//        $process->start();
        Artisan::call('twitter', [
            'account_id' => $input['account_id'],
            'today'      => true
        ]);
        return redirect('/dashboard');

    }

    public function handleSnapchat()
    {
        $casperKey = '694893979329-l59f3phl42et9clpoo296d8raqoljl6p';
        $casperSecret = '';
        $snapchat = new \Snapchat('quyetmiyatsu', 'ducquyet121@gmail.com', 'Quyetnd121');
        $snapchat->login('Quyetnd121');
        $a = $snapchat->getAuthToken();
        dd($a);
        //return view('service.snapchat');
    }

}