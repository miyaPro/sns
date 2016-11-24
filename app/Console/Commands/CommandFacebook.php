<?php

namespace App\Console\Commands;

use App\PageDetail;
use App\Repositories\PostFacebookRepository;
use Illuminate\Console\Command;
use App\Repositories\AuthRepository;
use App\Repositories\PageRepository;
use App\Repositories\PageDetailRepository;
use App\Repositories\PostFacebookDetailRepository;
use Facebook\Facebook as Facebook;
use App\Http\Requests;
use Facebook\Exceptions\FacebookResponseException as FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException as FacebookSDKException;
use Illuminate\Http;
use Illuminate\Support\Facades\Log;

class CommandFacebook extends Command
{

    public  $repAuth;
    public  $repPage;
    public  $repPost;
    public  $repPageDetail;
    public  $repPostDetail;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facebook {today=0} {account_id?}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $today = false;


    public function __construct(
        AuthRepository $reAuth,
        PageRepository $repPage,
        PostFacebookRepository $repPost,
        PageDetailRepository $repPageDetail,
        PostFacebookDetailRepository $repPostDetail)
    {
        $this->repAuth  = $reAuth;
        $this->repPage = $repPage;
        $this->repPost  = $repPost;
        $this->repPageDetail = $repPageDetail;
        $this->repPostDetail = $repPostDetail;

        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $service            = config('constants.service.facebook');
        $account_id         = $this->argument('account_id');
        $this->today        = $this->argument('today');
        $arrayAuth          = $this->repAuth->getListInitAuth($service, $account_id);
        foreach ($arrayAuth as $auth) {
            $this->getPage($auth);
        }
    }

    public function getPage($auth){
        $fb = new Facebook([
            'app_id' => config('services.facebook.client_id'),
            'app_secret' => config('services.facebook.client_secret'),
            'default_graph_version' => 'v2.8',
            'grant_type' => 'fb_exchange_token',
        ]);
        try{
            $accessToken = $auth['access_token'];
            $fb->getRedirectLoginHelper();
            $response = $fb->get('/me?fields=id,name,email,accounts{username,cover,picture,about,location,link,name,id,category,access_token,posts.limit('.config('constants.service_limit_post').'){comments,picture,type,permalink_url,message,likes,created_time,link,shares},fan_count,new_like_count,likes,comments{comment_count}}', $accessToken);
            $user = $response->getGraphUser();
            if(isset($user['accounts'])){
                foreach($user['accounts'] as  $row)  {
                    $sns_page_id =  $row['id'];
                    $inputs = [
                        'name'              => $row['name'],
                        'screen_name'       => @$row['username'],
                        'banner_url'        => @$row['cover']['source'],
                        'avatar_url'        => @$row['picture']['url'],
                        'description'       => @$row['about'],
                        'location'          => @$row['location']['street'],
                        'category'          => @$row['category'],
                        'link'              => @$row['permalink_url'],
                        'sns_page_id'       => @$row['id'],
                        'access_token'      => @$row['access_token'],

                    ];
                    $page = $this->repPage->getPage($auth->id, $sns_page_id);
                    if($page){
                        $page = $this->repPage->update($page, $inputs);
                    }else{
                        $page = $this->repPage->store($inputs, $auth->id);
                    }
                    $this->getPageDetail($row, $page);
                    $this->getPost($row, $page);
                }
            }
            else {
               $this->error('Error permission facebook');
            }
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            $this->repAuth->resetAccessToken($auth->id);
            $this->error(trans('message.error_get_access_token', ['name' => trans('default.facebook')]));
        } catch(FacebookSDKException $e) {
            $this->error(trans('message.error_network_connect', ['name' => trans('default.facebook')]));
        }
    }

    public function getPageDetail($row, $page){
        $current_date = date('Y-m-d');
        if(!$this->today) {
            $current_date = date('Y-m-d' ,strtotime("-1 day", strtotime($current_date)));
        }
        $inputs = [
            'friends_count'         => $row['fan_count'],
            'followers_count'       => '',
            'favourites_count'      => ''
        ];
        $inputs['posts_count'] = isset($row['posts']) ? count($row['posts']) : 0;
        $page_detail = $this->repPageDetail->getByDate($page->id, $current_date);
        if($page_detail){
            $this->repPageDetail->update($page_detail, $inputs);
        }else{
            $inputs['date'] = $current_date;
            $this->repPageDetail->store($inputs, $page->id);

        }
    }

    public function getPost($row, $page){
        if(isset($row['posts'])){
            foreach($row['posts'] as $data){
                $sns_post_id = $data['id'];
                $inputs = [
                    'sns_post_id'    => $sns_post_id,
                    'link'           => @$data['permalink_url'],
                    'created_time'   => $data['created_time'],
                    'content'        => @$data['message'],
                    'type'           => @$data['type'],
                    'image_thumbnail'=> @$data['picture']

                ];
                $post = $this->repPost->getOneByPost($page->id, $sns_post_id);
                if($post){
                    $post = $this->repPost->update($post, $inputs);
                }else{
                    $post = $this->repPost->store($inputs, $page->id);
                }
                $this->getPostDetail($data, $post);
            }
        }
    }

    public function getPostDetail($data, $post){
        $current_date = date('Y-m-d');
        if(!$this->today) {
            $current_date = date('Y-m-d' ,strtotime("-1 day", strtotime($current_date)));
        }
        $inputs['share_count'] = isset($data['shares']) ? $data['shares']['count'] : 0;
        $inputs['like_count'] = isset($data['likes']) ? count($data['likes']) : 0;
        $inputs['comment_count'] = isset($data['comments']) ? count($data['comments']) : 0;
        $post_detail = $this->repPostDetail->getByDate($post->id, $current_date);
        if($post_detail){
            $this->repPostDetail->update($post_detail, $inputs);
        }else{
            $inputs['date'] = $current_date;
            $this->repPostDetail->store($inputs, $post->id);
        }
    }
}
