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
        'default_phone' => env('WHATSAPP_DEFAULT') ?: '573076978295',

        'messages' => [
            'arriendo' => env(
                'WHATSAPP_MESSAGE_ARRIENDO',
                'Hola, me interesa información sobre arriendos en :ciudad con Paseo España.'
            ),
            'venta' => env(
                'WHATSAPP_MESSAGE_VENTA',
                'Hola, me interesa información sobre ventas en :ciudad con Paseo España.'
            ),
            'legal' => env(
                'WHATSAPP_MESSAGE_LEGAL',
                'Hola, necesito orientación del área legal y abogados de Paseo España.'
            ),
        ],

        'legal' => [
            'phone' => env('WHATSAPP_LEGAL') ?: env('WHATSAPP_DEFAULT', '573076978295'),
        ],
    ],

    'mipagoamigo' => [
        'url' => env('MIPAGOAMIGO_URL', 'https://www.mipagoamigo.com/MPA_WebSite/ServicePayments'),
    ],

];
