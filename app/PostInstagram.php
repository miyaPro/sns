<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostInstagram extends Model
{
    protected $fillable = ['sns_post_id', 'account_id', 'page_id', 'link', 'type','create_time', 'location_id', 'location_name', 'tag'];

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
        return $this->post_detail('App\PostInstagramDetail', 'post_id', 'id');
    }
}
