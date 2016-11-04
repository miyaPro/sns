<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageDetail extends Model
{
    protected $fillable = ['auth_id', 'screen_name', 'friends_count', 'posts_count', 'followers_count', 'favourites_count'];

    /**
     * Get the page detail that owns the page.
     */
    public function page()
    {
        return $this->belongsTo('App\Page', 'page_id', 'id');
    }
}
