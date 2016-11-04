<?php

namespace App\Repositories;

use App\PageDetail;
use Illuminate\Support\Facades\DB;

class PageDetailRepository extends BaseRepository
{
    /**
     * Create a new UserRepository instance.
     *
     * @return void
     */
    public function __construct(PageDetail $page)
    {
        $this->model = $page;
    }

    /**
     * Create a company.
     *
     * @param  array  $inputs
     * @param  int    $confirmation_code
     */
    public function store($inputs, $page_id)
    {
        $model = new $this->model;
        $model->page_id                   = $page_id;
        $model->date                      = $inputs['date'];
        $this->save($model, $inputs);
        return $model;
    }

    /**
     * Save the User.
     * @param  Array  $inputs
     * @return void
     */
    private function save($model, $inputs)
    {
        $model->friends_count             = $inputs['friends_count'];
        $model->posts_count               = $inputs['posts_count'];
        $model->followers_count           = $inputs['followers_count'];
        $model->favourites_count          = $inputs['favourites_count'];
        $model->save();
    }

    public function update($model, $inputs)
    {
        $this->save($model, $inputs);
    }

    public function getByDate($page_id, $current_date)
    {
        $model = new $this->model();
        $model = $model->where('page_id', $page_id)
                       ->where('date', $current_date);
        return $model->first();
    }

    public function getLast($page_id){
        $model = new $this->model();
        $model = $model->where('page_id', $page_id)
                       ->join('pages', 'pages.id', '=', 'page_details.page_id')
                       ->select(
                           'pages.name',
                           'pages.description',
                           'pages.avatar_url',
                           'page_details.*'
                       )
                       ->orderBy('id', 'desc')
                       ->limit(1);
        return $model->first();
    }

    public function getPageByDate($page_id, $startDate = null, $endDate = null)
    {
        $model = new $this->model();
        $model = $model->where('page_id', $page_id);
        if ($startDate && $endDate) {
            $model = $model->where('date', '>=', $startDate);
            $model = $model->where('date', '<=', $endDate);
        }
        return $model->get();
    }

    public function getTotalPage($page_id, $start_date = null, $end_date = null) {
        $model = new $this->model();
        $model = $model->select(
                DB::raw('SUM(friends_count) as friends_count'),
                DB::raw('SUM(posts_count) as posts_count'),
                DB::raw('SUM(followers_count) as followers_count'),
                DB::raw('SUM(favourites_count) as favourites_count')
            )
            ->where('page_id', $page_id)
        ;
        if ($start_date && $end_date) {
            $model = $model->Where('date', '>=', $start_date);
            $model = $model->Where('date', '<=', $end_date);
        }
        return $model->first();
    }

}
