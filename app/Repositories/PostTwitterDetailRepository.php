<?php

namespace App\Repositories;

use App\PostTwitterDetail;
use Illuminate\Support\Facades\DB;

class PostTwitterDetailRepository extends BaseRepository
{
    /**
     * Create a new UserRepository instance.
     *
     * @return void
     */
    public function __construct(PostTwitterDetail $postDetailDetail)
    {
        $this->model = $postDetailDetail;
    }
    /**
     * Save the User.
     * @param  Array  $inputs
     * @return void
     */
    private function save($postDetail, $inputs)
    {
        $postDetail->retweet_count             = $inputs['retweet_count'];
        $postDetail->favorite_count            = $inputs['favorite_count'];
        $postDetail->updated_at                = date("Y-m-d H:i:s");
        $postDetail->save();
    }
    /**
     * Create a company.
     *
     * @param  array  $inputs
     * @param  int    $confirmation_code
     */
    public function store($inputs, $page_id)
    {
        $postDetail = new $this->model;
        $postDetail->page_id                   = $page_id;
        $postDetail->date                      = $inputs['date'];
        $this->save($postDetail, $inputs);
    }

    public function update($model, $inputs)
    {
        $this->save($model, $inputs);
    }

   /* public function getByDate($post_id, $current_date)
    {
        $model = new $this->model();
        $model = $model->where('post_id', $post_id)
            ->where('date', $current_date);
        return $model->first();
    }*/
    public function getByDate($page_id, $current_date)
    {
        $model = new $this->model();
        $model = $model->where('page_id', $page_id)
            ->where('date', $current_date);
        return $model->first();
    }

    public function getListPost($post_id = null, $date = null, $start_date = null, $end_date = null) {
        $model = DB::table('post_twitters as p')
            ->join('post_twitter_details as pd', 'p.id', '=', 'pd.post_id')
            ->select(
                'p.id as post_id',
                'p.content',
                'p.author',
                'p.created_time',
                'pd.id',
                'pd.retweet_count',
                'pd.favorite_count',
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
        $model = DB::table('post_twitters as p')
            ->join('post_twitter_details as pd', 'p.id', '=', 'pd.post_id')
            ->select(
                'p.id as post_id',
                'p.content',
                'p.author',
                'p.created_time',
                DB::raw('SUM(pd.retweet_count) as retweet_count'),
                DB::raw('SUM(pd.favorite_count) as favorite_count')
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

    public function getPostEngagementByDate($page_id, $date = null, $date_from = null, $date_to = null)
    {
        $model = new $this->model();
        $model = $model->where('page_id', $page_id)
            ->select(
                DB::raw('IFNULL(SUM(retweet_count + favorite_count), 0) as post_engagement'),
                'date'
            );
        if($date) {
            $model = $model->where('date', $date);
        }
        if($date_from && $date_to) {
            $model = $model->where('date', '>=', $date_from)
                            ->where('date', '<=' , $date_to)
                            ->orderby('date');
        }
        $model = $model->groupBy('date');
        return $model->get();
    }
}
