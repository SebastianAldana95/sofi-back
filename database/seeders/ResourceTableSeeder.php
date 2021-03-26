<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('resources')->insert([
            'details' => 'detalle del articulo',
            'url' => 'archivo',
            'article_id' => Article::all()->random()->id,
        ]);

        DB::table('resources')->insert([
            'details' => 'detalle del articulo 2',
            'url' => 'archivo 2',
            'article_id' => Article::all()->random()->id,
        ]);

        DB::table('resources')->insert([
            'details' => 'detalle del articulo 3',
            'url' => 'archivo 3',
            'article_id' => Article::all()->random()->id,
        ]);

        DB::table('resources')->insert([
            'details' => 'detalle del articulo 4',
            'url' => 'archivo 4',
            'article_id' => Article::all()->random()->id,
        ]);
        DB::table('resources')->insert([
            'details' => 'detalle del articulo 5',
            'url' => 'archivo 5',
            'article_id' => Article::all()->random()->id,
        ]);
        DB::table('resources')->insert([
            'details' => 'detalle del articulo 6',
            'url' => 'archivo 6',
            'article_id' => Article::all()->random()->id,
        ]);
        DB::table('resources')->insert([
            'details' => 'detalle del articulo 7',
            'url' => 'archivo 7',
            'article_id' => Article::all()->random()->id,
        ]);
    }
}
