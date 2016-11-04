<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTwitterDetail extends Model
{
    protected $fillable = ['post_id', 'content', 'author', 'retweet_count', 'favorite_count'];

    /**
     * Get the Post detail that owns the post
     */
    public function post()
    {
        return $this->belongsTo('App\PostTwitter', 'post_id', 'id');
    }
}
