<?php

return [


    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
            'hash' => false,
        ],
    ],


    'providers' => [
        // change ldap
        'users' => [
            'driver' => 'ldap',
            'model' => LdapRecord\Models\ActiveDirectory\User::class,
            'database' => [
                'model' => App\Models\User::class,
                'sync_passwords' => true,
                'sync_attributes' => [
                    'identification' => 'description',
                    'username' => 'sAMAccountName',
                    'name' => 'givenName',
                    'lastname' => 'sn',
                    'email' => 'mail',
                    'title' => 'title',
                    'institution' => 'physicalDeliveryOfficeName',
                    'phone1' => 'telephoneNumber',
                    'phone2' => 'mobile',
                    'address' => 'streetAddress',
                    'alternatename' => 'displayName',
                    'city' => 'l',
                ],
                'sync_existing' => [
                    'username' => 'sAMAccountName',
                ],
            ],
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
