<?php

namespace App\Console\Commands;
//require "../../vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuthException;
use App\Post;
use App\Repositories\PostTwitterRepository;
use Carbon\Carbon;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Auth;
use App\PostTwitter;
use App\Repositories\PageDetailRepository;
use App\Repositories\PostTwitterDetailRepository;
use App\Repositories\AuthRepository;
use App\Repositories\PageRepository;
use Illuminate\Support\Facades\Log;

class CommandTwitter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "twitter {account_id?} {today?}";

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
            $this->getPage($auth);
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
            return $this->error('message1',"result null");
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

    public function getPost($page, $auth)
    {
        $client_id          = config('services.twitter.client_id');
        $client_secret      = config('services.twitter.client_secret');
        $connection         = new TwitterOAuth($client_id, $client_secret, $auth->access_token, $auth->refresh_token);
        $posts_twitter      = $connection->get("statuses/user_timeline", array("exclude_replies" => "true", "count" => '100'));

        foreach ($posts_twitter as $posts) {
            //create post Twitter
            $sns_post_id = $posts->id;
            $post = $this->repPostTwitter->getOneByPost($page->id, $sns_post_id);
            $inputs = [
                'sns_post_id'       => $sns_post_id,
                'content'           => $posts->text,
                'image_thumbnail'   => @$posts->entities->media[0]->media_url,
                'author'            => $posts->user->screen_name,
                'created_time'      => date("Y-m-d H:i:s",strtotime($posts->created_at)),
            ];
            if($post){
                $post = $this->repPostTwitter->update($post, $inputs);
            }else{
                $post = $this->repPostTwitter->store($inputs, $page->id);
            }
            $this->getPostDetail($posts, $post);
        }
    }

    public function getPostDetail($posts_twitter, $post)
    {
        $current_date        = Carbon::today()->toDateString();
        if(!$this->today) {
            $current_date = date('Y-m-d' ,strtotime("-1 day", strtotime($current_date)));
        }
        $postDetail = $this->repPostTwitterDetail->getByDate($post->id, $current_date);
        $inputs = [
            'retweet_count'     => $posts_twitter->retweet_count,
            'favorite_count'    => $posts_twitter->favorite_count,
        ];
        if($postDetail){
            $this->repPostTwitterDetail->update($postDetail, $inputs);
        }else{
            $inputs['date'] = $current_date;
            $this->repPostTwitterDetail->store($inputs, $post->id);
        }
    }
}
