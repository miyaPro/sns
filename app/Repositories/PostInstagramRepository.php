<?php

namespace App\Repositories;

use App\PostInstagram;
use Illuminate\Support\Facades\DB;
class PostInstagramRepository extends BaseRepository
{
    /**
     * Create a new MasterRepository instance.
     *
     * @param  App\Master $master
     * @return void
     */
    public function __construct(PostInstagram $post)
    {
        $this->model = $post;
    }

    /**
     * Save the Master.
     *
     * @param  App\Post $post
     * @param  Array  $inputs
     * @return post
     */
    private function save($post, $inputs)
    {
        $post->type = $inputs['type'];
        $post->created_time = $inputs['created_time'];
        $post->link = $inputs['link'];

        $post->image_low_resolution= $inputs['image_low_resolution'];
        $post->image_thumbnail= $inputs['image_thumbnail'];
        $post->image_standard_resolution= $inputs['image_standard_resolution'];

        $post->video_low_resolution = $inputs['video_low_resolution'];
        $post->video_standard_resolution= $inputs['video_standard_resolution'];
        $post->video_low_bandwidth = $inputs['video_low_bandwidth'];

        $post->location_id = $inputs['location_id'];
        $post->location_name= $inputs['location_name'];
        $post->location_lat= $inputs['location_lat'];
        $post->location_long = $inputs['location_long'];
        $post->content = $inputs['content'];
        $post->tag= $inputs['tag'];
        $post->save();
        return $post;
    }

    /**
     * Create a post.
     *
     * @param  array  $inputs
     * @param  int    $confirmation_code
     * @return App\Post
     */
    public function store($inputs, $page_id)
    {
        $post = new $this->model;
        $post->page_id = $page_id;
        $post->sns_post_id = $inputs['sns_post_id'];
        return $this->save($post, $inputs);
    }

    /**
     * Update a Post.
     *
     * @param  array  $inputs
     * @param  App\Models\Post $post
     * @return post
     */
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
            ->join('post_instagram_details as pd', 'post_instagrams.id', '=', 'pd.post_id')
            ->select(
                'post_instagrams.id as post_id',
                'post_instagrams.content',
                'post_instagrams.link',
                'post_instagrams.type',
                'post_instagrams.location_id',
                'post_instagrams.location_name',
                'post_instagrams.location_lat',
                'post_instagrams.location_long',
                'post_instagrams.tag',
                'post_instagrams.created_time',
                'post_instagrams.image_low_resolution',
                'post_instagrams.image_thumbnail',
                'post_instagrams.image_standard_resolution',
                'post_instagrams.video_low_resolution',
                'post_instagrams.video_standard_resolution',
                'post_instagrams.video_low_bandwidth',
                'pd.id',
                'pd.comment_count',
                'pd.like_count',
                'pd.date',
                'pd.updated_at'
            )
            ->where('pd.date', $date)
            ->orderBy('post_instagrams.created_time', 'asc')
            ->limit(10);
        return $model->get();
    }
    public function getMaxDate($page_id){
        $model= new $this->model();
        $model= $model->where('page_id', $page_id)
            ->join('post_instagram_details as pd', 'post_instagrams.id', '=', 'pd.post_id')
            ->select(DB::raw('Max(pd.date) as max_date'));
        return $model->first();
    }

}
