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
		'client_id' => 'FB_CLIENT_ID',
		'client_secret' => 'FB_SECRET_KEY',
		'redirect' => 'http://YOUR_DOMAIN_NAME/login/facebook/callback',
	],
	
	'google' => [
		'client_id' => 'GOOGLE_CLIENT_ID',
		'client_secret' => 'GOOGLE_SECRET_KEY',
		'redirect' => 'http://YOUR_DOMAIN_NAME/login/google/callback',
	],

    'usps' => [
        'username' => "XXXXXXXXXXXX",
        'testmode' => true,
    ],

];
