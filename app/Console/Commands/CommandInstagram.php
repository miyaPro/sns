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
use  Log;

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
            $this->getPage($auth);
        }
    }

    public function getPage($auth){
        $url = str_replace('{id}',$auth->account_id,config('instagram.url.user_info')).'?access_token='.$auth->access_token;
        $dataGet = $this->getContent($url,false);
        $dataGet  = @json_decode($dataGet[0]);
        if(!isset($dataGet)){
            $this->error(trans('message.error_network_connect', ['name' => trans('default.instagram')]));
        }
        else if(isset($dataGet->meta) && $dataGet->meta->code == 200){
            $data =  $dataGet->data;
            $sns_page_id = $data->id;
            $inputs = array(
                'name'          => $data->username,
                'screen_name'   => @$data->full_name,
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
            $this->getPost($page, $auth, 100);
        }else{
            $this->repAuth->resetAccessToken($auth->id);
            $this->error(trans('message.error_get_access_token', ['name' => trans('default.instagram')]));
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
        $dataGet = $this->getContent($url, false);
        $dataGet  = @json_decode($dataGet[0]);
        if(isset($dataGet)){
            $dataAllPost = $dataGet->data;
            if(empty($dataAllPost)){
                $this->info('No post');
                return;
            }
            if(count($dataAllPost) < $numberPost) {
                if ($numberPost > $maxGetPost) {
                    $numberLoop = intval(round($numberPost / $maxGetPost, 0));
                    for ($i = 0; $i < $numberLoop; $i++) {
                        $id_min_sns_post = $dataAllPost[count($dataAllPost) - 1]->id;
                        $currentUrl = urlencode($url . '&max_id=' . $id_min_sns_post);
                        $dataGet = $this->getContent($currentUrl, false);
                        $dataGet = @json_decode($dataGet[0]);
                        if (isset($dataGet) && $dataGet->meta->code == 200) {
                            unset($dataGet->data[0]);
                            $dataAllPost = array_merge($dataAllPost, $dataGet->data);

                        } else {
                            $this->error('message', 'error_do_not_get_post_instagram');
                            break;
                        }
                    }
                }
            }
            foreach ($dataAllPost as $row){
                $sns_post_id = $row->id;
                $inputs = array(
                    'sns_post_id' => $row->id,
                    'type' => $row->type,
                    'created_time' => @$date->setTimestamp($row ->created_time)->format('Y-m-d H:i:s'),
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
                    $post = $this->repPost->update($post, $inputs);
                }else{
                    $post = $this->repPost->store($inputs, $page->id);
                }
                $this->getPostDetail($row, $post);
            }
        } else{
            $this->error('message','error_do_not_get_post_instagram');
        }
    }

    public function getPostDetail($row, $post){
        $current_date = date('Y-m-d');
        if(!$this->today) {
            $current_date = date('Y-m-d' ,strtotime("-1 day", strtotime($current_date)));
        }
        $post_detail = $this->repPostDetail->getByDate($post->id, $current_date);
        $inputs = array(
            'comment_count' => $row->comments->count,
            'like_count' => $row->likes->count
        );
        if($post_detail){
            $this->repPostDetail->update($post_detail, $inputs);
        }else{
            $inputs['date'] = $current_date;
            $this->repPostDetail->store($inputs, $post->id);
        }
    }
}
