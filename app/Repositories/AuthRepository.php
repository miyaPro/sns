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
	 * Save the User.
	 * @param  Array  $inputs
	 * @return void
	 */
  	private function save($auth, $inputs)
	{
        $auth->refresh_token             = @$inputs['refresh_token'];
        $auth->access_token              = $inputs['access_token'];
        $auth->save();
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
        $this->save($auth, $inputs);
        return $auth;
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
        $update = DB::table('auths')->where('id',$auth_id)->update(array('access_token' => ''));
        return $update;
    }

    public function getAuth($account_id, $service_code)
    {
        $model = new $this->model();
        $model = $model->where('account_id', $account_id)
                       ->where('service_code', $service_code);
        return $model->first();
    }
}
