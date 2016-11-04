<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{

    protected $fillable = ['auth_id', 'name', 'category', 'screen_name', 'created_time', 'location', 'banner_url', 'description', 'avatar_url', 'link', 'sns_page_id', 'access_token'];

    /**
     * Get the Auth that owns the page.
     */
    public function auth()
    {
        return $this->belongsTo('App\Auth', 'auth_id', 'id');
    }
    /**
     * Get the page detail for the page.
     */
    public function page_detail()
    {
        return $this->hasMany('App\PageDetail', 'page_id', 'id');
    }
    /**
     * Get the post for the page.
     */
    public function post_facebook()
    {
        return $this->hasMany('App\PostFacebook', 'page_id', 'id');
    }
    public function post_twitter()
    {
        return $this->hasMany('App\PostTwitter', 'page_id', 'id');
    }
    public function post_instagram()
    {
        return $this->hasMany('App\PostInstagram', 'page_id', 'id');
    }
}
