<?php

namespace App\Repositories;

use App\PostFacebook;
use Illuminate\Support\Facades\DB;

class PostFacebookRepository extends BaseRepository
{
    /**
     * Create a new UserRepository instance.
     *
     * @return void
     */
    public function __construct(PostFacebook $post)
    {
        $this->model = $post;
    }
    /**
     * Save the User.
     * @param  Array  $inputs
     * @return post
     */
    private function save($post, $inputs)
    {
        $post->link                      = $inputs['link'];
        $post->created_time              = $inputs['created_time'];
        $post->content                   = $inputs['content'];
        $post->type                      = $inputs['type'];
        $post->image_thumbnail           = $inputs['image_thumbnail'];
        $post->like_count                = $inputs['like_count'];
        $post->share_count               = $inputs['share_count'];
        $post->comment_count             = $inputs['comment_count'];
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
        return $this->save($post, $inputs);
    }

    public function update($post, $inputs)
    {
        return $this->save($post, $inputs);
    }

    public function getOneByPost($page_id, $sns_post_id) {
        $model = new $this->model();
        $model = $model->where('page_id', $page_id)
            ->where('sns_post_id', $sns_post_id);
        return $model->first();
    }

    public function getListPostByPage($page_id){
        $model = new $this->model();
        $model = $model->where('page_id', $page_id)
            ->select(
                'post_facebooks.id as post_id',
                'post_facebooks.link',
                'post_facebooks.created_time',
                'post_facebooks.content',
                'post_facebooks.image_thumbnail',
                'post_facebooks.type',
                'post_facebooks.like_count',
                'post_facebooks.comment_count',
                'post_facebooks.share_count',
                'post_facebooks.created_at',
                'post_facebooks.updated_at'
            )
            ->orderBy('post_facebooks.created_time', 'DESC')
            ->limit(10);
        return $model->get();
    }

    public function getMaxDate($page_id){
        $model= new $this->model();
        $model= $model->where('page_id', $page_id)
            ->join('post_facebook_details as pd', 'post_facebooks.id', '=', 'pd.post_id')
            ->select(DB::raw('Max(pd.date) as max_date'));
        return $model->first();
    }
    
   /* public function getListPostByDate($page_id, $date = null, $date_from = null, $date_to = null)

    {
        $model = new $this->model();
        $model = $model->where('page_id', $page_id)
            ->join('post_facebook_details as pd', 'post_facebooks.id', '=', 'pd.post_id')
            ->select(
                'post_facebooks.id as post_id',
                'pd.like_count',
                'pd.comment_count',
                'pd.share_count',
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

    public function  getSumByPage($page_id){
        $model = new $this->model();
        $model = $model->select(
            DB::raw('IFNULL(SUM(like_count),0) as like_count'),
            DB::raw('IFNULL(SUM(comment_count),0) as comment_count'),
            DB::raw('IFNULL(SUM(share_count),0) as share_count')
        )->where('page_id', $page_id);
        return $model->get();
    }
}
