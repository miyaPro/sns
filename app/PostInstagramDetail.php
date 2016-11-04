<?php
/**
 * Created by PhpStorm.
 * User: le.van.hai
 * Date: 10/18/2016
 * Time: 9:48 AM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostInstagramDetail extends Model
{
    protected $fillable = ['like', 'post_id', 'date'];

    /**
     * Get the Post detail that owns the post
     */
    public function post()
    {
        return $this->belongsTo('App\PostInstagram', 'post_id', 'id');
    }
}