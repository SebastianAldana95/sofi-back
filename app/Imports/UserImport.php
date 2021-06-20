<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'identification' => $row['identification'],
            'username' => $row['username'],
            'name' => $row['name'],
            'lastname' => $row['lastname'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
	    'user' => 'manual',
        ]);
    }
}
