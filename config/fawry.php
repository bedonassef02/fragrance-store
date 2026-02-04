<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FawryPay Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for FawryPay payment gateway integration.
    | Register at https://fawrypay.io to get your credentials.
    |
    */

    // Merchant credentials from FawryPay dashboard
    'merchant_code' => env('FAWRY_MERCHANT_CODE'),
    'secure_key' => env('FAWRY_SECURE_KEY'),

    // Set to true for staging/testing, false for production
    'staging' => env('FAWRY_STAGING', true),

    // API endpoints (automatically selected based on staging flag)
    'base_url' => env('FAWRY_STAGING', true)
        ? 'https://atfawry.fawrystaging.com'
        : 'https://www.atfawry.com',

    // Payment expiry in hours (how long the reference code is valid)
    'payment_expiry_hours' => env('FAWRY_PAYMENT_EXPIRY_HOURS', 48),

    // Webhook URL for payment notifications
    'webhook_url' => env('APP_URL') . '/webhooks/fawry',

    // Default language for notifications (en-gb or ar-eg)
    'language' => env('FAWRY_LANGUAGE', 'en-gb'),
];
