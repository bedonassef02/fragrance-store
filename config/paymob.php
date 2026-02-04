<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paymob API Credentials
    |--------------------------------------------------------------------------
    */
    'api_key' => env('PAYMOB_API_KEY'),
    'secret_key' => env('PAYMOB_SECRET_KEY'),
    'public_key' => env('PAYMOB_PUBLIC_KEY'),
    'hmac_secret' => env('PAYMOB_HMAC_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Integration IDs
    |--------------------------------------------------------------------------
    | Each payment method has its own integration ID from Paymob dashboard
    */
    'integrations' => [
        'card' => env('PAYMOB_INTEGRATION_ID_CARD'),
        'wallet' => env('PAYMOB_INTEGRATION_ID_WALLET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    */
    'base_url' => env('PAYMOB_BASE_URL', 'https://accept.paymob.com/api'),
    
    'endpoints' => [
        'auth' => '/auth/tokens',
        'intention' => '/v1/intention/',
        'order' => '/ecommerce/orders',
        'payment_key' => '/acceptance/payment_keys',
    ],

    /*
    |--------------------------------------------------------------------------
    | Iframe ID for hosted checkout
    |--------------------------------------------------------------------------
    */
    'iframe_id' => env('PAYMOB_IFRAME_ID'),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    */
    'currency' => env('PAYMOB_CURRENCY', 'EGP'),

    /*
    |--------------------------------------------------------------------------
    | Payment Methods Toggle
    |--------------------------------------------------------------------------
    | Enable or disable specific payment methods.
    | Admin can control these via .env or database settings.
    */
    'methods' => [
        'cod' => env('PAYMENT_COD_ENABLED', true),
        'card' => env('PAYMENT_CARD_ENABLED', true),
        'wallet' => env('PAYMENT_WALLET_ENABLED', true),
        'fawry' => env('PAYMENT_FAWRY_ENABLED', true),
    ],
];
