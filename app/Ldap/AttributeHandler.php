<?php

namespace App\Ldap;

use App\Models\User as DatabaseUser;
use App\Ldap\User as LdapUser;

class AttributeHandler
{
    public function handle(LdapUser $ldap, DatabaseUser $database)
    {
        $database->username = $ldap->getFirstAttribute('sAMAccountName');
        $database->name = $ldap->getFirstAttribute('cn');
        $database->lastname = $ldap->getFirstAttribute('sn');
        $database->email = $ldap->getFirstAttribute('mail');
        $database->phone = $ldap->getFirstAttribute('mobile');
        $database->type = $ldap->getFirstAttribute('employeeType');
    }
}


