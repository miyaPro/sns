<?php

namespace App\Console\Commands;
//require "../../vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuthException;
use App\Repositories\PostTwitterRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Auth;
use App\PostTwitter;
use App\Repositories\PageDetailRepository;
use App\Repositories\PostTwitterDetailRepository;
use App\Repositories\AuthRepository;
use App\Repositories\PageRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommandTwitter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "twitter {today=0} {--queue=default} {account_id?}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $repPage;
    protected $repPageDetail;
    protected $repPostTwitter;
    protected $repPostTwitterDetail;
    protected $repAuth;
    protected $today = false;

    public function __construct(
        PageRepository $repPage,
        PageDetailRepository $repPageDetail,
        PostTwitterRepository $repPostTwitter,
        PostTwitterDetailRepository $repPostTwitterDetail,
        AuthRepository $repAuth
    )
    {
        $this->repPage = $repPage;
        $this->repPageDetail = $repPageDetail;
        $this->repPostTwitter = $repPostTwitter;
        $this->repPostTwitterDetail = $repPostTwitterDetail;
        $this->repAuth = $repAuth;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $service        = config('constants.service.twitter');
        $account_id     = $this->argument('account_id');
        $this->today    = $this->argument('today');
        $auths          = $this->repAuth->getListInitAuth($service, $account_id);
        foreach ($auths as $auth) {
            if($auth->rival_flg == 0 && !empty($auth->access_token)){
                $this->getPage($auth);
            }else if ($auth->rival_flg == 1) {
                $this->getPageRival($auth);
            }
        }
    }

    /*get data and store rival account*/
    public function getPageRival($auth)
    {
        $client_id          = config('services.twitter.client_id');
        $client_secret      = config('services.twitter.client_secret');
        $authToGet = $this->repAuth->getFirstAuth($auth->user_id, $auth->service_code);
        if ($authToGet) {
            $connection         = new TwitterOAuth($client_id, $client_secret, $authToGet->access_token, $authToGet->refresh_token);
            try{
                $user_detail    = $connection->get("users/show", array("user_id" => $auth->account_id));
                if (200 == $connection->getLastHttpCode()){
                    $page           = $this->repPage->getPage($auth->id, $user_detail->id);
                    $input_insert   = [
                        'name'              => $user_detail->name,
                        'screen_name'       => $user_detail->screen_name,
                        'location'          => $user_detail->location,
                        'category'          => '',
                        'link'              => '',
                        'sns_page_id'       => $user_detail->id,
                        'access_token'      => $auth->access_token,
                        'avatar_url'        => $user_detail->profile_image_url,
                        'banner_url'        => @$user_detail->profile_banner_url,
                        'description'       => @$user_detail->description,
                        'created_time'      => date("Y-m-d H:i:s",strtotime(@$user_detail->created_at)),
                        'public_flg'        => '1',
                    ];
                    if (!$page) {
                        $page = $this->repPage->store($input_insert, $auth->id);
                    }else{
                        $page =$this->repPage->update($page, $input_insert);
                    }
                    $this->getPageDetail($user_detail, $page);
                }
            } catch (TwitterOAuthException $e) {
                $this->error('message1',"result null");
            } catch (\Exception $e) {
            }
        }
    }

    public function getPageDetail($page_twitter, $page)
    {
        $current_date   = Carbon::today()->toDateString();
        if(!$this->today) {
            $current_date = date('Y-m-d' ,strtotime("-1 day", strtotime($current_date)));
        }
        $page_detail   = $this->repPageDetail->getByDate($page->id, $current_date);
        $inputs = [
            'friends_count'     => $page_twitter->friends_count,
            'posts_count'       => $page_twitter->statuses_count,
            'followers_count'   => $page_twitter->followers_count,
            'favourites_count'  => $page_twitter->favourites_count,
        ];
        if($page_detail){
            $this->repPageDetail->update($page_detail, $inputs);
        }else{
            $inputs['date'] = $current_date;
            $this->repPageDetail->store($inputs, $page->id);
        }
    }

    public function getPage($auth)
    {
        $client_id          = config('services.twitter.client_id');
        $client_secret      = config('services.twitter.client_secret');
        $connection         = new TwitterOAuth($client_id, $client_secret, $auth->access_token, $auth->refresh_token);
        try{
            $page_twitter       = $connection->get("account/verify_credentials");
            if (200 == $connection->getLastHttpCode()){
                $page           = $this->repPage->getPage($auth->id, $page_twitter->id);
                $input_insert   = [
                    'name'              => $page_twitter->name,
                    'screen_name'       => $page_twitter->screen_name,
                    'location'          => $page_twitter->location,
                    'category'          => '',
                    'link'              => '',
                    'sns_page_id'       => $page_twitter->id,
                    'access_token'      => $auth->access_token,
                    'avatar_url'        => $page_twitter->profile_image_url,
                    'banner_url'        => @$page_twitter->profile_banner_url,
                    'description'       => @$page_twitter->description,
                    'created_time'      => date("Y-m-d H:i:s",strtotime(@$page_twitter->created_at)),
                    'public_flg'        => '1',
                ];
                if (!$page) {
                    $page = $this->repPage->store($input_insert, $auth->id);
                }else{
                    $page =$this->repPage->update($page, $input_insert);
                }
                $this->getPageDetail($page_twitter, $page);
                $this->getPost($page, $auth);
            } else {
                $this->repAuth->resetAccessToken($auth->id);
            }
        } catch (TwitterOAuthException $e) {
            $this->error("error=".$e->getMessage());
        } catch (\Exception $e) {
            $this->error("error=".$e->getMessage());
        }

    }

    public function getPost($page, $auth)
    {
        $client_id          = config('services.twitter.client_id');
        $client_secret      = config('services.twitter.client_secret');
        $connection         = new TwitterOAuth($client_id, $client_secret, $auth->access_token, $auth->refresh_token);
        try {
            //the twitter get post count - 1, so count param need + 1
            $posts_twitter = $connection->get("statuses/user_timeline", array("exclude_replies" => "true", "count" => config('constants.service_limit_post') + 1));
            foreach ($posts_twitter as $posts) {
                //create post Twitter
                $sns_post_id = $posts->id;
                $post = $this->repPostTwitter->getOneByPost($page->id, $sns_post_id);
                $inputs = [
                    'sns_post_id'       => $sns_post_id,
                    'content'           => $posts->text,
                    'image_thumbnail'   => @$posts->entities->media[0]->media_url,
                    'author'            => $posts->user->screen_name,
                    'retweet_count'     => $posts->retweet_count,
                    'favorite_count'    => $posts->favorite_count,
                    'created_time'      => date("Y-m-d H:i:s",strtotime($posts->created_at)),
                ];
                if($post){
                    $this->repPostTwitter->update($post, $inputs);
                }else{
                    $this->repPostTwitter->store($inputs, $page->id);
                }
            }
            $this->getPostDetail($page->id);
        } catch (TwitterOAuthException $e) {
            $this->error('message1',"result null");
        } catch (\Exception $e) {
        }
    }

    public function getPostDetail($page_id)
    {
        $current_date        = Carbon::today()->toDateString();
        if(!$this->today) {
            $current_date = date('Y-m-d' ,strtotime("-1 day", strtotime($current_date)));
        }
        $sum = $this->repPostTwitter->getSumByPage($page_id);
        $postDetail = $this->repPostTwitterDetail->getByDate($page_id, $current_date);
        if ($sum){
            $inputs = [
                'retweet_count'     => $sum[0]['retweet_count'],
                'favorite_count'    => $sum[0]['favorite_count'],
            ];
            if($postDetail){
                $this->repPostTwitterDetail->update($postDetail, $inputs);
            }else{
                $inputs['date'] = $current_date;
                $this->repPostTwitterDetail->store($inputs, $page_id);
            }
        }
    }
    
}
