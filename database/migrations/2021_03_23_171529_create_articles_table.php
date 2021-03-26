<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('article_id')->nullable();

            $table->date('date');
            $table->string('title', 50);
            $table->mediumText('extract');
            $table->text('content');
            $table->string('state', 10);
            $table->string('type', 20);
            $table->boolean('visibility')->default(1);
            $table->float('total_score')->default(0);

            $table->foreign('article_id')->references('id')->on('articles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
