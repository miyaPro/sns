<?php

namespace App\Repositories;

use App\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountRepository extends BaseRepository
{
    /**
     * Create a new UserRepository instance.
     *
     * @return void
     */
    public function __construct(Account $account)
    {
        $this->model = $account;
    }

    /**
     * Create a company.
     *
     * @param  array  $inputs
     * @param  int    $confirmation_code
     */
    public function store($inputs)
    {
        $account = new $this->model;
        $account->account_id                   = $inputs['account_id'];
        $account->account_name                 = @$inputs['account_name'];
        $account->screen_name                  = $inputs['screen_name'];
        $account->location                     = @$inputs['location'];
        $account->description                  = @$inputs['description'];
        $account->avatar_url                   = $inputs['avatar_url'];
        $account->created_time                 = $inputs['created_time'];
        $this->save($account, $inputs);
        return $account;

    }

    /**
     * Save the User.
     * @param  Array  $inputs
     * @return void
     */
    private function save($account, $inputs)
    {
        $account->followers_count              = $inputs['followers_count'];
        $account->friends_count                = $inputs['friends_count'];
        $account->listed_count                 = $inputs['listed_count'];
        $account->favourites_count             = $inputs['favourites_count'];
        $account->statuses_count               = $inputs['statuses_count'];
        $account->save();
    }

    /**
     * Update a user.
     *
     * @param  array  $inputs
     * @return void
     */
    public function update($account, $inputs)
    {
        $this->save($account, $inputs);
    }




}
