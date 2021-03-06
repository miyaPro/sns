<?php

namespace App\Repositories;

use App\PostTwitter;
use Illuminate\Support\Facades\DB;

class PostTwitterRepository extends BaseRepository
{
    /**
     * Create a new UserRepository instance.
     *
     * @return void
     */
    public function __construct(PostTwitter $post)
    {
        $this->model = $post;
    }
    /**
     * Save the User.
     * @param  Array  $inputs
     * @return post
     */
    private function save( $post, $inputs)
    {
        $post->content                   = $inputs['content'];
        $post->image_thumbnail           = $inputs['image_thumbnail'];
        $post->retweet_count             = $inputs['retweet_count'];
        $post->favorite_count            = $inputs['favorite_count'];
        $post->updated_at                = date('Y-m-d H:i:s');
        $post->save();
        return $post;
    }
    /**
     * Create a company.
     *
     * @param  array  $inputs
     * @param  int    $confirmation_code
     */
    public function store($inputs, $page_id)
    {
        $post = new $this->model;
        $post->page_id                   = $page_id;
        $post->sns_post_id               = $inputs['sns_post_id'];
        $post->created_time              = $inputs['created_time'];
        $post->author                    = $inputs['author'];
        $post = $this->save($post, $inputs);
        return $post;
    }

    public function update($post, $inputs)
    {
        $post = $this->save($post, $inputs);
        return $post;
    }

    public function getOneByPost($page_id, $sns_post_id) {
        $model = new $this->model();
        $model = $model->where('page_id', $page_id)
                      ->where('sns_post_id', $sns_post_id);
        return $model->first();
    }

    public function getPostId($sns_post_id) {
        $model = new $this->model();
        $model = $model->where('sns_post_id', $sns_post_id)->first();
        $id = $model['id'];
        return $id;
    }

    public function getMaxDate($page_id){
        $model= new $this->model();
        $model= $model->where('page_id', $page_id)
            ->join('post_twitter_details as pd', 'post_twitters.id', '=', 'pd.post_id')
            ->select(DB::raw('Max(pd.date) as max_date'));
        return $model->first();
    }

/*    public function getListPostByDate($page_id, $date = null, $date_from = null, $date_to = null)
    {
        $model = new $this->model();
        $model = $model->where('page_id', $page_id)
            ->join('post_twitter_details as pd', 'post_twitters.id', '=', 'pd.post_id')
            ->select(
                'post_twitters.id as post_id',
                'pd.retweet_count',
                'pd.favorite_count',
                'pd.date'
            );
        if($date) {
            $model->where('pd.date', $date);
        }
        if($date_from && $date_to) {
            $model->where('pd.date', '>=', $date_from);
            $model->where('pd.date', '<=' , $date_to);
            $model->orderby('pd.date');
        }
        return $model->get();
    }*/

        public function getListPostByPage($page_id){
        $model = new $this->model();
        $model = $model->where('page_id', $page_id)
                       ->select(
                           'post_twitters.id as post_id',
                           'post_twitters.content',
                           'post_twitters.image_thumbnail',
                           'post_twitters.author',
                           'post_twitters.created_time',
                           'post_twitters.retweet_count',
                           'post_twitters.favorite_count',
                           'post_twitters.created_at',
                           'post_twitters.updated_at'
                       )
                       ->orderBy('post_twitters.created_time', 'DESC')
                       ->limit(10);
        return $model->get();
    }

    public function  getSumByPage($page_id){
        $model = new $this->model();
        $model = $model->select(
            DB::raw('IFNULL(SUM(retweet_count),0) as retweet_count'),
            DB::raw('IFNULL(SUM(favorite_count),0) as favorite_count')
        )->where('page_id', $page_id);
        return $model->get();
    }
}
