<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $fillable = ['post_id', 'content', 'author', 'retweet_count', 'favorite_count'];

}
