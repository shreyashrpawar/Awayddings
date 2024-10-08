<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    'default' => env('MAIL_MAILER', 'ses'),

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('MAIL_AWS_ACCESS_KEY_ID'),
        'secret' => env('MAIL_AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'ap-south-1'),
    ],
    '2factor' => [
        'api_key' => env('2FACTOR_API_KEY'),
    ],
    'phonepe'=> [
        'api_key' => env('PHONEPE_API_KEY'),
        'salt_index' => env('PHONEPE_SALT_INDEX'),
    ]

];
