<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('identification');
            $table->string('username', 60)->unique();
            $table->string('name', 60);
            $table->string('lastname', 60);
            $table->string('email', 60)->unique();
            $table->string('title', 120)->nullable();
            $table->string('institution', 120)->nullable();
            $table->string('phone1', 60)->nullable();
            $table->string('phone2', 60)->nullable();
            $table->string('address', 120)->nullable();
            $table->string('alternatename', 120)->nullable();
            $table->string('url',120)->nullable();
            $table->string('lang',120)->nullable();
            $table->string('firstnamephonetic',120)->nullable();
            $table->string('lastnamephonetic',120)->nullable();
            $table->string('middlename',120)->nullable();
            $table->string('photo',120)->default('storage/photos/default.jpg')->nullable();
            $table->string('state')->default('activo')->nullable();
            $table->string('user')->default('ldap')->nullable();
            $table->string('city')->default('BOGOTÁ')->nullable();
            $table->string('country')->default('COLOMBIA')->nullable();

            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
