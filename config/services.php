<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'facebook' => [
        'client_id' => '1485803355037503',
        'client_secret' => 'd3f00dd3272c78eea02f044371cbfac0',
        'redirect' => config('app.url').'/social/handleFacebookCallback',
        'redirect_logout' => config('app.url').'/social/logoutFacebookCallback',

    ],

    'twitter' => [
        'client_id' => 'fh9ldFH21zBnmxvBAqBU7MyDc',
        'client_secret' => 'kqTyWUh4WPPXZkAI6bybfLezN2STb2y6M1HL1MekaXTuSFgjvh',
        'redirect' => config('app.url').'/social/handleTwitterCallback',
    ],

];
