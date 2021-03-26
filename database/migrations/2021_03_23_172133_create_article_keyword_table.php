<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleKeywordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_keyword', function (Blueprint $table) {
            $table->unsignedInteger('article_id');
            $table->unsignedInteger('keyword_id');

            $table->foreign('article_id')->references('id')->on('articles')
                ->onDelete('set null');
            $table->foreign('keyword_id')->references('id')->on('keywords')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_keyword');
    }
}
