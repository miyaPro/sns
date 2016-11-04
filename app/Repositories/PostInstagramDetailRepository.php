<?php
/**
 * Created by PhpStorm.
 * User: le.van.hai
 * Date: 10/18/2016
 * Time: 9:54 AM
 */

namespace App\Repositories;

use App\PostInstagramDetail;
use Illuminate\Support\Facades\DB;


class PostInstagramDetailRepository extends BaseRepository
{
    /**
     * Create a new MasterRepository instance.
     *
     * @param  App\PostInstagramDetail $master
     * @return void
     */
    public function __construct(PostInstagramDetail $PostInstagramDetail)
    {
        $this->model = $PostInstagramDetail;
    }

    public function save($model, $inputs){
        $model->comment_count  = $inputs['comment_count'];
        $model->like_count = $inputs['like_count'];
        $model->save();
    }
    /**
     * Create a post.
     *
     * @param  array  $inputs
     * @return App\Post
     */
    public function store($inputs, $post_id)
    {
        $model = new $this->model;
        $model->post_id  = $post_id;
        $model->date = $inputs['date'];
        $this->save($model,$inputs);
    }

    /**
     * Update a Post.
     *
     * @param  array  $inputs
     * @param  App\Models\Post $post
     * @return void
     */
    public function update($model, $inputs)
    {
        $this->save($model, $inputs);
    }

    public function getByDate($post_id, $current_date)
    {
        $model = new $this->model();
        $model = $model->where('post_id', $post_id)
            ->where('date', $current_date);
        return $model->first();
    }

    public function getListPost($post_id = null, $date = null, $start_date = null, $end_date = null) {
        $model = DB::table('post_instagrams as p')
            ->join('post_instagram_details as pd', 'p.id', '=', 'pd.post_id')
            ->select(
                'p.id as post_id',
                'p.content',
                'p.link',
                'p.type',
                'p.location_id',
                'p.location_name',
                'p.location_lat',
                'p.location_long',
                'p.tag',
                'p.created_time',
                'p.image_low_resolution',
                'p.image_thumbnail',
                'p.image_standard_resolution',
                'p.video_low_resolution',
                'p.video_standard_resolution',
                'p.video_low_bandwidth',
                'pd.id',
                'pd.comment_count',
                'pd.like_count',
                'pd.date'
            )
            ->orderBy('pd.date', 'ASC')
        ;
        if($post_id) {
            $model = $model->Where('p.id', $post_id);
        }
        if($date) {
            $model = $model->Where('pd.date', $date);
        }
        if ($start_date && $end_date) {
            $model = $model->Where('pd.date', '>=', $start_date);
            $model = $model->Where('pd.date', '<=', $end_date);
        }
        return $model->get();
    }

    public function getTotalPost($post_id, $start_date = null, $end_date = null) {
        $model = DB::table('post_instagrams as p')
            ->join('post_instagram_details as pd', 'p.id', '=', 'pd.post_id')
            ->select(
                'p.id as post_id',
                'p.content',
                'p.link',
                'p.type',
                'p.location_id',
                'p.location_name',
                'p.location_lat',
                'p.location_long',
                'p.tag',
                'p.created_time',
                'p.image_low_resolution',
                'p.image_thumbnail',
                'p.image_standard_resolution',
                'p.video_low_resolution',
                'p.video_standard_resolution',
                DB::raw('SUM(pd.comment_count) as comment_count'),
                DB::raw('SUM(pd.like_count) as like_count')
            )
            ->where('p.id', $post_id)
            ->groupBy('pd.post_id')
        ;
        if ($start_date && $end_date) {
            $model = $model->Where('pd.date', '>=', $start_date);
            $model = $model->Where('pd.date', '<=', $end_date);
        }
        return $model->get();
    }
}