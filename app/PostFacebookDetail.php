<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostFacebookDetail extends Model
{
    protected $fillable = ['post_id', 'date', 'like_count', 'comment_count','share_count'];

    /**
     * Get the Post detail that owns the post
     */
    public function post()
    {
        return $this->belongsTo('App\PostFacebook', 'post_id', 'id');
    }
}
