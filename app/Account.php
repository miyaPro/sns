<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['account_id', 'account_name', 'screen_name', 'location', 'description', 'avatar_url', 'created_time'];

}
