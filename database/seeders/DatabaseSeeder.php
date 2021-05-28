<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UserSeeder::class);
       $this->call(PermissionsTableSeeder::class);

        Permission::create(['name' => 'users.events.index', 'guard_name' => 'api']);

        $admin = Role::where('name', 'Admin')->get()->first();
        $user = Role::where('name', 'User')->get()->first();

        $admin->givePermissionTo(Permission::all());
        $user->givePermissionTo('users.events.index');

    }
}
