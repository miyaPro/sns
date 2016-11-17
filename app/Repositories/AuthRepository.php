<?php

namespace App\Repositories;

use App\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthRepository extends BaseRepository
{
	/**
	 * Create a new UserRepository instance.
	 *
	 * @return void
	 */
	public function __construct(Auth $auth)
	{
		$this->model = $auth;
	}

    /**
     * Create a company.
     *
     * @param  array  $inputs
     * @param  int    $confirmation_code
     */
    public function store($inputs)
    {
        $auth = new $this->model;
        $auth->user_id                   = $inputs['user_id'];
        $auth->email                     = @$inputs['email'];
        $auth->account_name              = $inputs['account_name'];
        $auth->account_id                = $inputs['account_id'];
        $auth->service_code              = $inputs['service_code'];
        $auth->rival_flg                 = isset($inputs['rival_flg']) ? $inputs['rival_flg'] : 0;
        $this->save($auth, $inputs);
        return $auth;
    }


	/**
	 * Save the User.
	 * @param  Array  $inputs
	 * @return void
	 */
  	private function save($auth, $inputs)
	{
        $auth->refresh_token             = @$inputs['refresh_token'];
        $auth->access_token              = @$inputs['access_token'];
        $auth->save();
	}

	/**
	 * Update a user.
	 *
	 * @param  array  $inputs
	 * @return void
	 */
	public function update($auth, $inputs)
	{
        $this->save($auth, $inputs);
	}

    public function getListUserAccess () {
        $model = new $this->model;
        $model = $model->select('user_id', 'service_code')
            ->whereNotNull('access_token');
        return $model->get();
    }
    public function getListInitAuth($service_code, $account_id = null){
        $model = new $this->model;
        $model = $model->whereNotNull('access_token')
                       ->where('service_code',$service_code);
        if($account_id) {
            $model = $model->where('account_id', $account_id);
        }
        return $model->get();
    }

    public function resetAccessToken($auth_id){
        $model = new $this->model;
        $update = $model->where('id',$auth_id)->update(array('access_token' => ''));
        return $update;
    }

    public function getAuth($user_id, $account_id, $service_code)
    {
        $model = new $this->model();
        $model = $model->where('user_id', $user_id)
                       ->where('account_id', $account_id)
                       ->where('service_code', $service_code);
        return $model->first();
    }

    public function getFirstAuth($user_id, $service_code)
    {
        $model = new $this->model();
        $model = $model->where('user_id', $user_id)
                       ->where('service_code', $service_code);
        return $model->first();
    }

    public function getRival($keyword, $perPage, $curent_date)
    {
        $model = new $this->model();
        $model = $model->where('rival_flg', 1)
                       ->join('pages', 'auths.id', '=', 'pages.auth_id')
                       ->join('page_details', 'pages.id', '=', 'page_details.page_id')
                       ->select(
                           'auths.service_code',
                           'pages.id as page_id',
                           'pages.name',
                           'pages.created_time',
                           'pages.avatar_url',
                           'pages.banner_url',
                           'pages.description',
                           'pages.sns_page_id',
                           'page_details.friends_count',
                           'page_details.posts_count',
                           'page_details.followers_count',
                           'page_details.favourites_count'

                       )
                       ->where('page_details.date', $curent_date)
                       ->orderby('pages.created_at', 'desc');
        if($keyword){
            $model = $model->where(function($q) use ($keyword) {
                $q->where('pages.name', 'like', "%{$keyword}%");
            });
        }
        $model  = $model->paginate($perPage);
        return $model;
    }
}
