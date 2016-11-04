<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    protected $fillable = ['user_id', 'email', 'account_name', 'account_id', 'link', 'access_token', 'refresh_token', 'service_code'];

    /**
     * Get the Auth that owns the user.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Get the page for the auth.
     */
    public function page()
    {
        return $this->hasMany('App\Page', 'auth_id', 'id');
    }
}
