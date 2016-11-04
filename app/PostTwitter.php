<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTwitter extends Model
{
    protected $fillable = ['auth_id', 'sns_post_id', 'created_time'];

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
        return $this->post_detail('App\PostTwitterDetail', 'post_id', 'id');
    }
}
