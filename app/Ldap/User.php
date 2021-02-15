<?php

namespace App\Ldap;

use LdapRecord\Models\Model;
use LdapRecord\Models\ActiveDirectory\Entry;

class User extends Model
{
    /**
     * The object classes of the LDAP model.
     *
     * @var array
     */
    public static $objectClasses = [
        'top',
        'person',
        'organizationalPerson',
        'user',
    ];
}
