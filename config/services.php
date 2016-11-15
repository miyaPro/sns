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
        'client_id' => '432909340201182',
        'client_secret' => 'cc7726171d9654335269b949c25261ae',
        'redirect' => config('app.url').'/social/handleFacebookCallback',
        'redirect_logout' => config('app.url').'/social/handleFacebookCallback/1',

    ],

    'twitter' => [
        'client_id' => '0mSzwmpftBesxGQBPQnGNn449',
        'client_secret' => '5imLIZFpKq56HYFt4PPZ6YGFeb5vkCXXLx9akhbMr3zttyPTzk',
        'redirect' => config('app.url').'/social/handleTwitterCallback',
    ],

];
