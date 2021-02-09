<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_TNS', ''),
        'host'           => env('DB_HOST', '192.168.0.14'),
        'port'           => env('DB_PORT', '1521'),
        'database'       => env('DB_DATABASE', 'sofi'),
        'username'       => env('DB_USERNAME', 'sofi'),
        'password'       => env('DB_PASSWORD', 'sofi2020'),
        'charset'        => env('DB_CHARSET', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
        'edition'        => env('DB_EDITION', 'ora$base'),
        'server_version' => env('DB_SERVER_VERSION', '11g'),
    ],
];
