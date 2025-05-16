<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5173', 'http://127.0.0.1:5173'],
    'allowed_headers' => ['Content-Type', 'X-XSRF-TOKEN', 'Authorization', '*'],
    'exposed_headers' => ['Authorization'],
    'supports_credentials' => false,
];
