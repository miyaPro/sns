<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ServiceRepository extends BaseRepository
{
	/**
	 * Create a new UserRepository instance.
	 *
   	 * @param  App\Service
	 * @return void
	 */
	public function __construct()
	{

	}
    public function getListPage($user_id = null) {
        $model = DB::table('users as u')
            ->join('auths as a', 'u.id', '=', 'a.user_id')
            ->join('pages as p', 'a.id', '=', 'p.auth_id')
            ->select(
                'a.service_code',
                'p.id as page_id',
                'p.auth_id',
                'p.screen_name',
                'p.category',
                'p.avatar_url',
                'p.banner_url',
                'p.link',
                'p.description',
                'p.name as page_name'
            )
            ->whereNull('a.deleted_at')
        ;
        if($user_id) {
            $model = $model->Where('u.id', $user_id);
        }
        return $model->get();
    }

	public function getListPageDetail($user_id = null, $date = null, $start_date = null, $end_date = null, $service_code = null) {
        $model = DB::table('users as u')
            ->join('auths as a', 'u.id', '=', 'a.user_id')
            ->join('pages as p', 'a.id', '=', 'p.auth_id')
            ->join('page_details as pd', 'p.id', '=', 'pd.page_id')
            ->select(
                'u.name',
                'u.company_name',
                'u.url',
                'u.contract_status',
                'a.service_code',
                'p.id as page_id',
                'p.auth_id',
                'p.screen_name',
                'p.category',
                'p.avatar_url',
                'p.banner_url',
                'p.link',
                'p.description',
                'p.name as page_name',
                'pd.page_id',
                'pd.friends_count',
                'pd.posts_count',
                'pd.followers_count',
                'pd.favourites_count',
                'pd.date'
            )
            ->whereNull('a.deleted_at')
            ->orderBy('pd.date', 'ASC')
        ;
        if($user_id) {
            $model = $model->Where('u.id', $user_id);
        }
        if($service_code) {
            $model = $model->Where('a.service_code', $service_code);
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

    public function getTotalPage($user_id, $start_date = null, $end_date = null, $service_code = null) {
        $model = DB::table('users as u')
            ->join('auths as a', 'u.id', '=', 'a.user_id')
            ->join('pages as p', 'a.id', '=', 'p.auth_id')
            ->join('page_details as pd', 'p.id', '=', 'pd.page_id')
            ->select(
                'p.id as page_id',
                'p.name as page_name',
                'p.link as page_link',
                'a.service_code',
                DB::raw('SUM(pd.friends_count) as friends_count'),
                DB::raw('SUM(pd.posts_count) as posts_count'),
                DB::raw('SUM(pd.followers_count) as followers_count'),
                DB::raw('SUM(pd.favourites_count) as favourites_count')
            )
            ->whereNull('a.deleted_at')
            ->where('u.id', $user_id)
            ->groupBy('pd.page_id')
            ->groupBy('a.service_code')
        ;
        if ($start_date && $end_date) {
            $model = $model->Where('pd.date', '>=', $start_date);
            $model = $model->Where('pd.date', '<=', $end_date);
        }
        if($service_code) {
            $model = $model->Where('a.service_code', $service_code);
        }
        return $model->get();
    }
}
