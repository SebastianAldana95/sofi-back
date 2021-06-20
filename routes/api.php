<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('sendUserRegisteredMail', [App\Http\Controllers\Auth\LoginController::class, 'sendUserRegisteredMail']);
Route::post('sendLogInMail', [App\Http\Controllers\Auth\LoginController::class, 'sendLogInMail']);
Route::post('sendForgotPasswordMail', [App\Http\Controllers\Auth\LoginController::class, 'sendForgotPasswordMail']);
Route::post('addFavoriteArticlesToUser', [App\Http\Controllers\Auth\LoginController::class, 'addFavoriteArticlesToUser']);
Route::post('getFavoriteArticlesByUser', [App\Http\Controllers\Auth\LoginController::class, 'getFavoriteArticlesByUser']);
Route::post('getUsersByFavoriteArticle', [App\Http\Controllers\Auth\LoginController::class, 'getUsersByFavoriteArticle']);
Route::post('addScoreToArticles', [App\Http\Controllers\Auth\LoginController::class, 'addScoreToArticles']);
Route::post('getScoresByUser', [App\Http\Controllers\Auth\LoginController::class, 'getScoresByUser']);
Route::post('getScoresByArticle', [App\Http\Controllers\Auth\LoginController::class, 'getScoresByArticle']);
Route::post('getFutureEvents', [App\Http\Controllers\Auth\LoginController::class, 'getFutureEvents']);
Route::post('getFutureEventsByState', [App\Http\Controllers\Auth\LoginController::class, 'getFutureEventsByState']);
Route::post('getFutureEventsByUser', [App\Http\Controllers\Auth\LoginController::class, 'getFutureEventsByUser']);
Route::post('getFutureEventsByUserAndState', [App\Http\Controllers\Auth\LoginController::class, 'getFutureEventsByUserAndState']);
Route::post('confirmCodeRestorePassword', [App\Http\Controllers\Auth\LoginController::class, 'confirmCodeRestorePassword']);
Route::post('saveNewPassword', [App\Http\Controllers\Auth\LoginController::class, 'saveNewPassword']);
Route::post('removeFavoriteArticlesToUser', [App\Http\Controllers\Auth\LoginController::class, 'removeFavoriteArticlesToUser']);
Route::post('existFavorite', [App\Http\Controllers\Auth\LoginController::class, 'existFavorite']);

Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('events/public', [App\Http\Controllers\Event\EventController::class,'list_public']);
Route::get('articles/public/{type}', [App\Http\Controllers\Article\ArticleController::class,'list_public']);
Route::get('articles/public/show/{id}', [App\Http\Controllers\Article\ArticleController::class,'show_public']);
Route::group(['middleware' => 'auth:api'], function () {

    /*
     *  Auth User
     * */
    Route::get('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);
    Route::get('user', function (Request $request) {
        return $request->user()->load(['roles', 'favorites']);
    });
    /*
     *  Users
     * */
    Route::resource('users', App\Http\Controllers\User\UserController::class, ['except' => ['create', 'edit']]);
    Route::resource('users.events', App\Http\Controllers\User\UserEventController::class, ['only' => ['index']]);
    Route::resource('users.favorites', App\Http\Controllers\User\UserFavoriteController::class, ['except' => ['create', 'edit', 'show']]);
    Route::resource('users.scores', App\Http\Controllers\User\UserScoreController::class, ['only' => ['store', 'index']]);
    Route::post('import', [App\Http\Controllers\User\ImportUserController::class, 'import']);
    Route::post('user/update/photo', [App\Http\Controllers\User\UserController::class,'update_photo']);
    /*
    *  Roles and Permissions
    * */
    Route::resource('permissions', App\Http\Controllers\Permission\PermissionController::class, ['except' => ['create', 'edit']]);
    Route::resource('roles', App\Http\Controllers\Role\RoleController::class, ['except' => ['create', 'edit']]);
    /*
     *  Events
     * */
    Route::resource('events', App\Http\Controllers\Event\EventController::class, ['except' => ['create', 'edit']]);
    Route::resource('events.notifications', App\Http\Controllers\Event\EventNotificationController::class, ['only' => ['index']]);
    Route::resource('events.resources', App\Http\Controllers\Event\EventResourceController::class, ['only' => ['index']]);
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
    Route::resource('articles.resources', App\Http\Controllers\Article\ArticleResource::class, ['only' => ['index']]);
    Route::resource('articles.keywords', App\Http\Controllers\Article\ArticleKeyword::class, ['only' => ['index']]);
    Route::get('articles/type/{type}', [App\Http\Controllers\Article\ArticleController::class,'list_type']);

});





