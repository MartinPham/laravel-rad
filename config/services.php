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

//    'mailgun' => [
//        'domain' => env('MAILGUN_DOMAIN'),
//        'secret' => env('MAILGUN_SECRET'),
//    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION')
    ],

//    'mandrill' => [
//        'secret' => env('MANDRILL_SECRET')
//    ],

//    'sparkpost' => [
//        'secret' => env('SPARKPOST_SECRET'),
//    ],

//    'stripe' => [
//        'model' => App\User::class,
//        'key' => env('STRIPE_KEY'),
//        'secret' => env('STRIPE_SECRET'),
//    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_KEY'),
        'client_secret' => env('FACEBOOK_SECRET'),
        'redirect' => env('APP_URL') . '/api/v1/auth/oauthLoginCallback/facebook',
    ],
    'twitter' => [
        'client_id' => env('TWITTER_KEY'),
        'client_secret' => env('TWITTER_SECRET'),
        'redirect' => env('APP_URL') . '/api/v1/auth/oauthLoginCallback/twitter',
    ],

    'firebase' => [
        'database_url' => '',
        'secret' => ''
    ]
];
