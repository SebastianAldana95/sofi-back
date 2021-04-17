<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Event;
use App\Models\Keyword;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

    }
}
