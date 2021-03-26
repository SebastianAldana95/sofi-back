<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Keyword;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KeywordTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Keyword::truncate();
        Article::truncate();

        $article = new Article();
        $article->date = Carbon::now()->subDays(10);
        $article->title = 'titulo del articulo';
        $article->extract = 'extracto del articulo';
        $article->content = '<p>Contenido del primer articulo</p>';
        $article->state = 'publico';
        $article->type = 'principales';
        $article->visibility = 1;
        $article->total_score = 0;
        $article->save();

        $article = new Article();
        $article->date = Carbon::now()->subDays(8);
        $article->title = 'titulo del articulo 2';
        $article->extract = 'extracto del articulo 2';
        $article->content = '<p>Contenido del primer articulo 2</p>';
        $article->state = 'publico';
        $article->type = 'asesinatos';
        $article->visibility = 1;
        $article->total_score = 4.2;
        $article->save();

        $article = new Article();
        $article->date = Carbon::now()->subDays(4);
        $article->title = 'titulo del articulo 3';
        $article->extract = 'extracto del articulo 3';
        $article->content = '<p>Contenido del primer articulo 3</p>';
        $article->state = 'publico';
        $article->type = 'estadistica';
        $article->visibility = 1;
        $article->total_score = 3.3;
        $article->article_id = Article::all()->random()->id;
        $article->save();

        $article = new Article();
        $article->date = Carbon::now()->subDays(3);
        $article->title = 'titulo del articulo 4';
        $article->extract = 'extracto del articulo 4';
        $article->content = '<p>Contenido del primer articulo 4</p>';
        $article->state = 'privado';
        $article->type = 'tipo 1';
        $article->visibility = 1;
        $article->total_score = 2.1;
        $article->article_id = Article::all()->random()->id;
        $article->save();

        $article = new Article();
        $article->date = Carbon::now()->subDays(2);
        $article->title = 'titulo del articulo 5';
        $article->extract = 'extracto del articulo 5';
        $article->content = '<p>Contenido del primer articulo 5</p>';
        $article->state = 'privado';
        $article->type = 'tipo 2';
        $article->visibility = 0;
        $article->total_score = 0.8;
        $article->article_id = Article::all()->random()->id;
        $article->save();


        $keyword = new Keyword();
        $keyword->name = "Policia";
        $keyword->save();

        $keyword = new Keyword();
        $keyword->name = "Educacion";
        $keyword->save();

        $keyword = new Keyword();
        $keyword->name = "Criminalistica";
        $keyword->save();
    }
}
