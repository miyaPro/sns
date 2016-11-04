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
    protected $signature = 'instagram {account_id?}';

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
        $account_id = $this->argument('account_id');
        $auths = $this->repAuth->getListInitAuth(config('constants.service.instagram'), $account_id);
        foreach ($auths as $auth){
            $this->getPage($auth);
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

    public function getPage($auth){
        $url = str_replace('{id}',$auth->account_id,config('instagram.url.user_info')).'?access_token='.$auth->access_token;
        $dataGet = $this->getContent($url,false);
        $dataGet  = @json_decode($dataGet[0]);
        if(!empty($dataGet)&&!empty($dataGet->meta->code)&&$dataGet->meta->code==200){
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
            $this->getPost($page, $auth);
        }else{
            $this->error('message',trans('error_do_not_get_page_instagram'));
        }
    }

    public function getPageDetail($data, $page){
        $current_date = date('Y-m-d');
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

    public function getPost($page, $auth){
        $url = str_replace('{id}',$auth->account_id,config('instagram.url.media')).'?access_token='.$auth->access_token;
        $dataGet = $this->getContent($url,false);
        $dataGet  = @json_decode($dataGet[0]);
        $date = new \DateTime();
        if(!empty($dataGet)&&!empty($dataGet->meta->code)&&$dataGet->meta->code==200){
            foreach ($dataGet->data as $row){
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
        }
        else{
            $this->error('message','error_do_not_get_post_instagram');
        }

    }

    public function getPostDetail($row, $post){
        $current_date = date('Y-m-d');
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
