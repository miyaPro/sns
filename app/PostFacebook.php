<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostFacebook extends Model
{
    protected $fillable = ['page_id', 'link', 'sns_post_id', 'created_time', 'message', 'type', 'image_thumbnail'];

    /**
     * Get the Post that owns the page.
     */
    public function page()
    {
        return $this->belongsTo('App\Page', 'page_id', 'id');
    }

    /**
     * Get the post detail that owns the post.
     */
    public function post_detail()
    {
        return $this->hasMany('App\PostFacebookDetail', 'post_id', 'id');
    }
}
