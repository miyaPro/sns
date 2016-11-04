<?php
/**
 * Created by PhpStorm.
 * User: le.van.hai
 * Date: 10/20/2016
 * Time: 4:48 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageDetailInstagram extends Model
{
    protected $fillable = ['new_follow', 'new_post', 'new_like', 'date', 'created_at', 'page_id','updated_at', 'deleted_at'];

    /**
     * Get the page detail Instagram that owns the page.
     */
    public function post_instagram()
    {
        return $this->belongsTo('App\PostInstagram', 'post_id', 'id');
    }
}