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
        $post->message                   = $inputs['message'];
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

    public function getListPostByPage($page_id, $date)
    {
        $model = new $this->model();
        $model = $model->where('page_id', $page_id)
            ->join('post_facebook_details as pd', 'post_facebooks.id', '=', 'pd.post_id')
            ->select(
                'post_facebooks.id as post_id',
                'post_facebooks.link',
                'post_facebooks.created_time',
                'post_facebooks.message',
                'pd.id',
                'pd.like_count',
                'pd.comment_count',
                'pd.share_count',
                'pd.date',
                'pd.updated_at'
            )
            ->where('pd.date', $date)
            ->orderBy('post_facebooks.created_time', 'desc')
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

}
