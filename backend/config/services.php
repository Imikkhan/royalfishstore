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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'whatsapp' => [
        'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v22.0'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID', '1261359957063709'),
        'waba_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID', '981039441675980'),
        'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
        'template_name' => env('WHATSAPP_OTP_TEMPLATE', 'otpsms_verification'),
        'template_lang' => env('WHATSAPP_TEMPLATE_LANG', 'en_US'),
    ],

    'codebey' => [
        'api_url' => env('CODEBEY_API_URL', 'https://codebey.online/external-api'),
        'client_id' => env('CODEBEY_CLIENT_ID', 'ci_1JPP85AUFD1NVM7NNDSQ'),
        'client_secret' => env('CODEBEY_CLIENT_SECRET', 'cs_6FPDTOC7VYMDNGMR2T5Q'),
        'template_id' => env('CODEBEY_TEMPLATE_ID', '2846735265701532'),
        'template_name' => env('CODEBEY_TEMPLATE_NAME', 'otpsms_verification'),
    ],

];
