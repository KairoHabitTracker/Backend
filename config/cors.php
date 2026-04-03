<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost',
        'http://127.0.0.1',
        'http://localhost:19006',
        'http://127.0.0.1:19006',
        'http://localhost:8081',
        'http://127.0.0.1:8081',
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'http://localhost:5173',
        'http://127.0.0.1:5173',
    ],

    'allowed_origins_patterns' => [
        '/^http:\/\/(localhost|127\\.0\\.0\\.1)(:\\d+)?$/',
        '/^http:\\/\\/192\\.168\\.\\d{1,3}\\.\\d{1,3}(:\\d+)?$/', // LAN dev
        '/^https:\\/\\/[a-z0-9-]+\\.ngrok-free\\.app$/',          // ngrok
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
