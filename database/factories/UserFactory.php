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
            'identification' => '123456789',
            'username' => 'admin',
            'name' => 'admin',
            'lastname' => 'admin',
            'email' => 'admin@admin.com',
            'user' => 'manual',
            'state' => 'activo',
            'password' => Hash::make('password12345'),
        ];
    }
}
