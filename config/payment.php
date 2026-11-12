<?php

return [
    /*
    |--------------------------------------------------------------------------
    | QRIS Payment Configuration
    |--------------------------------------------------------------------------
    | Konfigurasi untuk payment gateway QRIS menggunakan buatqris.site
    | Paste string QRIS statis dari DANA Bisnis, GoPay, OVO, BRI, BCA, dll.
    |--------------------------------------------------------------------------
    */
    'qris_string'       => env('QRIS_STRING', ''),
    'merchant_name'     => env('QRIS_MERCHANT_NAME', 'Ahmad Handoko Law Office'),
    'buatqris_base_url' => env('BUATQRIS_BASE_URL', 'https://buatqris.site/api'),
    'enabled'           => env('QRIS_ENABLED', true),
];

