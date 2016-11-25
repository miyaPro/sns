<?php
/**
 * Created by TungNK.
 * User: nguyen.khac.tung
 * Date: 10/05/2016
 * Time: 8:29 AM
 */

return [
    'per_page'  => [5, 10, 15, 20],
    'authority' => [
        'client'                => 1,
        'admin'                 => 2
    ],
    'active'    => [
        'enable'                => 1,
        'disable'               => 2,
    ],
    'service'     => [
        'facebook'  => '001',
        'twitter'   => '002',
        'instagram' => '003',
//        'snapchat'  => '004',
    ],
    'master' => [
        'contract_status'   => 'contract_status',
        'services'          => 'services',
    ],
    'condition_filter_page' => ['friends', 'followers', 'favourites', 'posts'],
    'condition_filter_post' => ['likes', 'comments', 'shares', 'retweets', 'favorites'],
    'condition_filter'      => ['total', 'daily', 'total_change'],
    'service_limit_post'    => 30,

];

//call a constant: config('constants.ad_service')