<?php

namespace App\Console\Commands;

use App\PageDetail;
use App\PageDetailInstagram;
use App\Repositories\PageDetailRepository;
use Illuminate\Console\Command;
use App\PostInstagramDetail;
use App\Repositories\PostInstagramDetailRepository;
use App\Repositories\PostInstagramRepository;
use App\Repositories\PageRepository;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Log;
use App\Common\Common;

class CommandInstagram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instagram {today=0} {account_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $repPost;
    protected $repPostDetail;
    protected $repPage;
    protected $repPageDetail;
    protected $repAuth;
    protected $today = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        PostInstagramDetailRepository $repPostDetail,
        PostInstagramRepository $repPost,
        PageRepository $repPage,
        AuthRepository $repAuth,
        PageDetailRepository $repPageDetail)
    {
        parent::__construct();
        $this->repPost = $repPost;
        $this->repPostDetail = $repPostDetail;
        $this->repPage = $repPage;
        $this->repAuth = $repAuth;
        $this->repPageDetail = $repPageDetail;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('----------------Cront tab instagram----------------');
        $account_id     = $this->argument('account_id');
        $this->today    = $this->argument('today');
        $auths          = $this->repAuth->getListInitAuth(config('constants.service.instagram'), $account_id);
        foreach ($auths as $auth){
            if($auth->rival_flg == 0 && !$auth->access_token) {
                continue;
            }
            if ($auth->rival_flg == 1) {
                $this->getPageRival($auth);
            } else {
                $this->getPage($auth);
            }
        }
    }

    public function getPageRival($auth)
    {
        $authToGet = $this->repAuth->getFirstAuth($auth->user_id, $auth->service_code);
        if ($authToGet) {
                $this->getPage($auth, $authToGet->access_token);
        }
    }

    public function getPage($auth, $access_token=null){
        if($auth->rival_flg == 1 && isset($access_token)){
            $url = str_replace('{id}',$auth->account_id,config('instagram.url.user_info')).'?access_token='.$access_token;
        }else{
            $url = str_replace('{id}',$auth->account_id,config('instagram.url.user_info')).'?access_token='.$auth->access_token;
        }
        $dataGet = Common::getContent($url,false);
        $dataGet  = @json_decode($dataGet[0]);
        if(!isset($dataGet)){
            $this->error(trans('message.error_network_connect', ['name' => trans('default.instagram')]));
        }
        else if(isset($dataGet->meta) && $dataGet->meta->code == 200){
            $data =  $dataGet->data;
            $sns_page_id = $data->id;
            $inputs = array(
                'name'          => @$data->full_name,
                'screen_name'   => $data->username,
                'link'          => 'https://www.instagram.com/'.$data->username,
                'access_token'  => $auth->access_token,
                'sns_page_id'   => $auth->account_id,
                'category'      => '',
                'avatar_url'    => $data->profile_picture,
                'description'   => @$data->bio,
                'created_time'   => null
            );
            $page = $this->repPage->getPage($auth->id, $sns_page_id);
            if($page){
                $page = $this->repPage->update($page, $inputs);
            }else{
                $page = $this->repPage->store($inputs, $auth->id);
            }
            $this->getPageDetail($data, $page);
            if($auth->rival_flg == 0){
                $this->getPost($page, $auth, config('constants.service_limit_post'));
            }
        }else{
            $this->repAuth->resetAccessToken($auth->id);
            $this->error(trans('message.error_get_access_token', ['name' => trans('default.instagram')]));
        }
    }

    public function getPageDetail($data, $page){
        $current_date = date('Y-m-d');
        if(!$this->today) {
            $current_date = date('Y-m-d' ,strtotime("-1 day", strtotime($current_date)));
        }
        $page_detail = $this->repPageDetail->getByDate($page->id, $current_date);

        $inputs = array(
            'friends_count'     => $data->counts->follows,
            'posts_count'       => $data->counts->media,
            'followers_count'   => $data->counts->followed_by,
            'favourites_count'  => 0
        );
        if($page_detail){
            $this->repPageDetail->update($page_detail, $inputs);
        }else{
            $inputs['date'] = $current_date;
            $this->repPageDetail->store($inputs, $page->id);
        }
    }

    public function getPost($page, $auth, $numberPost){
        $url = str_replace('{id}',$auth->account_id,config('instagram.url.media')).'?access_token='.$auth->access_token;
        $maxGetPost = config('instagram.limit.post_media');
        $date = new \DateTime();
        if($numberPost < $maxGetPost) {
            $url .= '&count='.$numberPost;
        }
        $dataGet = Common::getContent($url, false);
        $dataGet  = @json_decode($dataGet[0]);
        if(isset($dataGet)){
            $dataAllPost = $dataGet->data;
            if(empty($dataAllPost)){
                $this->repAuth->resetAccessToken($auth->id);
                $this->error(trans('message.error_get_access_token', ['name' => trans('default.instagram')]));
                return;
            }
            if(count($dataAllPost) < $numberPost && $numberPost > $maxGetPost) {
                $numberLoop = floor($numberPost / $maxGetPost);
                for ($i = 0; $i < $numberLoop; $i++) {
                    $currNumPost = $numberPost - count($dataAllPost);
                    $nextUrl = @$dataGet->pagination->next_url;
                    if($nextUrl){
                        if($currNumPost < $maxGetPost){
                            $nextUrl .= '&count='.$currNumPost;
                        }
                        $dataGet = Common::getContent($nextUrl, false);
                        $dataGet = @json_decode($dataGet[0]);
                        if (isset($dataGet) && $dataGet->meta->code == 200) {
                            $dataAllPost = array_merge($dataAllPost, $dataGet->data);
                        } else {
                            $this->error('message.error_do_not_get_post_instagram');
                            break;
                        }
                    }
                }
            }
            foreach ($dataAllPost as $row){
                $sns_post_id = $row->id;
                $inputs = array(
                    'sns_post_id'   => $row->id,
                    'type'          => $row->type,
                    'like_count'    => $row->likes->count,
                    'comment_count' => $row->comments->count,
                    'created_time'  => @$date->setTimestamp($row ->created_time)->format('Y-m-d H:i:s'),
                    'date'          => $date,
                    'link' => $row->link,
                    'image_low_resolution' => $row->images->low_resolution->url,
                    'image_thumbnail'               => $row->images->thumbnail->url,
                    'image_standard_resolution'     => $row->images->standard_resolution->url,
                    'video_low_resolution'          => isset($row->videos->low_resolution->url)?$row->videos->low_resolution->url:'',
                    'video_standard_resolution'     => isset($row->videos->standard_resolution->url)?$row->videos->standard_resolution->url:'',
                    'video_low_bandwidth'           => isset($row->videos->low_bandwidth->url)?$row->videos->low_bandwidth->url:'',
                    'location_id'   => isset($row->location->id)?$row->location->id:'',
                    'location_name' => isset($row->location->name)?$row->location->name:'',
                    'location_lat'  => isset($row->location->latitude)?$row->location->latitude:'',
                    'location_long' => isset($row->location->longitude)?$row->location->longitude:'',
                    'content'       => isset($row->caption->text)?$row->caption->text:'',
                    'tag' => ( isset($row->tags) && !empty($row->tags) ) ? '#' . implode( '、#' , (array)$row->tags ) : '' ,
                );
                $post = $this->repPost->getOneByPost($page->id, $sns_post_id);
                if($post){
                    $this->repPost->update($post, $inputs);
                }else{
                    $this->repPost->store($inputs, $page->id);
                }
            }
            $this->getPostDetail($page->id);
        } else{
            $this->error('message','error_do_not_get_post_instagram');
        }
    }

    public function getPostDetail($page_id){
        $current_date        = date('Y-m-d');
        if(!$this->today) {
            $current_date = date('Y-m-d' ,strtotime("-1 day", strtotime($current_date)));
        }
        $sum = $this->repPost->getSumByPage($page_id);
        $postDetail = $this->repPostDetail->getByDate($page_id, $current_date);
        if ($sum){
            $inputs = [
                'like_count'     => $sum[0]->like_count,
                'comment_count'    => $sum[0]->comment_count,
            ];
            if($postDetail){
                $this->repPostDetail->update($postDetail, $inputs);
            }else{
                $inputs['date'] = $current_date;
                $this->repPostDetail->store($inputs, $page_id);
            }
        }
    }
}
