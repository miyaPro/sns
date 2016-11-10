<?php

namespace App\Repositories;

use App\PostFacebookDetail;
use Illuminate\Support\Facades\DB;

class PostFacebookDetailRepository extends BaseRepository
{
    /**
     * Create a new UserRepository instance.
     *
     * @return void
     */
    public function __construct(PostFacebookDetail $postFacebookDetail)
    {
        $this->model = $postFacebookDetail;
    }
    /**
     * Save the User.
     * @param  Array  $inputs
     * @return void
     */
    private function save($model, $inputs)
    {
        $model->like_count               = $inputs['like_count'];
        $model->comment_count            = $inputs['comment_count'];
        $model->share_count              = $inputs['share_count'];
        $model->updated_at               = date("Y-m-d H:i:s");
        $model->save();
    }
    /**
     * Create a company.
     *
     * @param  array  $inputs
     * @param  int    $confirmation_code
     */
    public function store($inputs, $post_id)
    {
        $model = new $this->model;
        $model->post_id = $post_id;
        $model->date = $inputs['date'];
        $this->save($model, $inputs);
    }

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
        $model = DB::table('post_facebooks as p')
            ->join('post_facebook_details as pd', 'p.id', '=', 'pd.post_id')
            ->select(
                'p.id as post_id',
                'p.link',
                'p.created_time',
                'p.message',
                'pd.id',
                'pd.like_count',
                'pd.comment_count',
                'pd.share_count',
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
        $model = DB::table('post_facebooks as p')
            ->join('post_facebook_details as pd', 'p.id', '=', 'pd.post_id')
            ->select(
                'p.id as post_id',
                'p.link',
                'p.created_time',
                'p.message',
                DB::raw('SUM(pd.like_count) as like_count'),
                DB::raw('SUM(pd.comment_count) as comment_count'),
                DB::raw('SUM(pd.share_count) as share_count')
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
