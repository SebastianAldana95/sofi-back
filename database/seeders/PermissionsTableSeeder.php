<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // permission List Users
        Permission::create(['name' => 'users.index']);
        Permission::create(['name' => 'users.store']);
        Permission::create(['name' => 'users.show']);
        Permission::create(['name' => 'users.update']);
        Permission::create(['name' => 'users.delete']);

        // permission List Events
        Permission::create(['name' => 'events.index']);
        Permission::create(['name' => 'events.store']);
        Permission::create(['name' => 'events.show']);
        Permission::create(['name' => 'events.update']);
        Permission::create(['name' => 'events.delete']);

        // permission List Event Resource
        Permission::create(['name' => 'eventResource.index']);
        Permission::create(['name' => 'eventResource.store']);
        Permission::create(['name' => 'eventResource.show']);
        Permission::create(['name' => 'eventResource.update']);
        Permission::create(['name' => 'eventResource.delete']);

        // permission List Notifications
        Permission::create(['name' => 'notifications.index']);
        Permission::create(['name' => 'notifications.store']);
        Permission::create(['name' => 'notifications.show']);
        Permission::create(['name' => 'notifications.update']);
        Permission::create(['name' => 'notifications.delete']);


        //Roles

        $admin = Role::create([
            'name' => 'Admin',
        ]);

        $admin->givePermissionTo(Permission::all());

        $user = Role::create([
            'name' => 'User',
        ]);

        $user->givePermissionTo([
            'events.index',
            'events.show'
        ]);

        $userAdmin = User::where('username', 'admin')->get()->first();
        $userAdmin->assignRole($admin);

    }
}
