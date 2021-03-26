<?php

return [


    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'ldap',
        ],

        'api' => [
            'driver' => 'passport',
            'provider' => 'ldap',
            'hash' => false,
        ],
    ],


    'providers' => [
        // change ldap
        'ldap' => [
            'driver' => 'ldap',
            'model' => LdapRecord\Models\ActiveDirectory\User::class,
            'rules' => [
                // App\Ldap\Rules\OnlyAdministrators::class,
            ],
            'database' => [
                'model' => App\Models\User::class,
                'sync_passwords' => true,
                'sync_attributes' => [
                    //App\Ldap\AttributeHandler::class,
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
