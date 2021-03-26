<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\UserController;

/*
|----------5----------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/logout', [LoginController::class, 'logout']);
    /*
     *  Users
     * */
    Route::resource('users', App\Http\Controllers\User\UserController::class, ['except' => ['create', 'edit']]);
    Route::resource('users.events', App\Http\Controllers\User\UserEventController::class, ['only' => ['index']]);
    Route::resource('users.favorites', App\Http\Controllers\User\UserFavoriteController::class, ['except' => ['create', 'edit', 'show']]);
    Route::resource('users.scores', App\Http\Controllers\User\UserScoreController::class, ['only' => ['store', 'index']]);
    /*
     *  Events
     * */
    Route::resource('events', App\Http\Controllers\Event\EventController::class, ['except' => ['create', 'edit']]);
    Route::resource('events.notifications', App\Http\Controllers\Event\EventNotificationController::class, ['only' => ['index']]);
    Route::resource('events.users', App\Http\Controllers\Event\EventUserController::class, ['only' => ['index']]);
    /*
     *  Events Resources
     * */
    Route::resource('eventResources', App\Http\Controllers\EventResource\EventResourceController::class, ['except' => ['create', 'edit']]);
    /*
     *  Notifications
     * */
    Route::resource('notifications', App\Http\Controllers\Notification\NotificationController::class, ['except' => ['create', 'edit']]);
    /*
     *  Keywords
     * */
    Route::resource('keywords', App\Http\Controllers\Keyword\KeywordController::class, ['except' => ['create', 'edit']]);
    Route::resource('keywords.articles', App\Http\Controllers\Keyword\KeywordArticleController::class, ['only' => ['index']]);
    /*
     *  Resources Articles
     * */
    Route::resource('resources', App\Http\Controllers\Resource\ResourceController::class, ['except' => ['create', 'edit']]);
    /*
     *  Articles
     * */
    Route::resource('articles', App\Http\Controllers\Article\ArticleController::class, ['except' => ['create', 'edit']]);
    Route::resource('parentarticles', App\Http\Controllers\Article\ArticleController::class, ['only' => ['index']]);
});





