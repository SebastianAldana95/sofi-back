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

        /*'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],*/

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],
    ],


    'providers' => [
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
                    'username' => 'sAMAccountName',
                    'name' => 'cn',
                    'lastname' => 'sn',
                    'email' => 'userPrincipalName',
                    'phone' => 'mobile',
                    'type' => 'employeeType',
                ],
                'sync_existing' => [
                    'name' => 'cn',
                ],
            ],
        ],

        //'users' => [
        //    'driver' => 'eloquent', ldpa -> opcional
        //    'model' => App\Models\User::class,
        //],


        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
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
