<?php

namespace App\Models\Passport;

use Laravel\Passport\Client;


class PassportClient extends Client
{
    public static function findClientBySecret($clientSecret): PassportClient
    {
        return static::where('secret', $clientSecret)->get()->first();
    }
}
