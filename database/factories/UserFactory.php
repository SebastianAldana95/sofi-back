<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'identification' => '576874545',
            'username' => 'admin',
            'name' => 'administrador',
            'lastname' => 'admin',
            'email' => 'administrador@admin.com',
            'title' => 'administrador',
            'institution' => 'administrador',
            'phone1' => 'phone admin 1',
            'phone2' => 'phone admin 1',
            'address' => 'Colombia',
            'alternatename' => 'administrador',
            'user' => 'manual',
            'email_verified_at' => now(),
            'password' => Hash::make('password12345'),
            'remember_token' => Str::random(10),
        ];
    }
}
