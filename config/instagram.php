<?php

/*
 * This file is part of Laravel Instagram.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Instagram Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => [

        'main' => [
            'id' => 'f159adb765794af587447d9dd8eb159e',
            'secret' => '1ff89f56dd1e4648b19afac93cc53e93',
            'access_token' => '{ "access_token": "4033827353.f159adb.04eff1b90cd64c899de8c262af733763" }'
//            'access_token' => '4033827353.f159adb.04eff1b90cd64c899de8c262af733763',
        ],

        'alternative' => [
            'id' => 'f159adb765794af587447d9dd8eb159e',
            'secret' => '1ff89f56dd1e4648b19afac93cc53e93',
            'access_token' => null
        ],

    ],
    'url' => [
        'redirect'  => '/social/handleInstagramCallback',
        'oauth'     => 'https://api.instagram.com/oauth/authorize',
        'token'     => 'https://api.instagram.com/oauth/access_token',
        'media'     => 'https://api.instagram.com/v1/users/{id}/media/recent/',
        'comment'   => 'https://api.instagram.com/v1/media/{id}/comments',
        'like'      => 'https://api.instagram.com/v1/media/{id}/likes',
        'user_info' => 'https://api.instagram.com/v1/users/{id}',
        'logout'    => 'https://instagram.com/accounts/logoutin/?force_classic_login=&next={url}'
    ],
    'scope' => [
        'access'=>'basic+public_content+follower_list+comments+relationships+likes'
    ],
    'limit' => [
        'post_media' => 20,
    ]

];
