<?php
// config/license.php
return [
    'api_url'   => env('LICENSE_API_URL', 'https://api.example.com/activate'),
    'check_url' => env('LICENSE_CHECK_URL', 'https://api.example.com/updates/version'),
    'key_env'   => env('LICENSE_KEY'),
    'file_path' => base_path('license.json'),
];




// $expectedLicense = [
//     'license_key' => '12345',
//     'url'         => 'https://your-app-url.com',
//     'email'       => 'ask@gmail.com',
//     'product'     => 'MyApp'
// ];
