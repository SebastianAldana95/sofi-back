<?php

return [

    'default' => env('LDAP_CONNECTION', 'default'),

    'connections' => [

        'default' => [
            'hosts' => [env('LDAP_HOST', '10.1.7.215')],
            'username' => env('LDAP_USERNAME', ''),
            'password' => env('LDAP_PASSWORD', ''),
            'port' => env('LDAP_PORT', 389),
            'base_dn' => env('LDAP_BASE_DN', 'dc=fiscalia,dc=col'),
            'timeout' => env('LDAP_TIMEOUT', 5),
            'use_ssl' => env('LDAP_SSL', false),
            'use_tls' => env('LDAP_TLS', false),
        ],

    ],

    'logging' => env('LDAP_LOGGING', true),

    'cache' => [
        'enabled' => env('LDAP_CACHE', false),
        'driver' => env('CACHE_DRIVER', 'file'),
    ],

];
