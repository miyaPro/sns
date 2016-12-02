<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Repositories\NotificationRepository;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the auth for the user.
     */
    public function auth()
    {
        return $this->hasMany('App\Auth', 'user_id', 'id');
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function routeNotificationForDatabase()
    {
        Log::info('run route database');
        return $this;
    }

}
