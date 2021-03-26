<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes([
    'reset' => false,
    'verify' => false,
    'register' => false,
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('users', UserController::class,  ['except' => 'show'])->middleware(['auth']);
*/



/*Route::group(['middleware' => 'auth'], function (){
    // Route::get('/users', [UserController::class, 'index']);
    // Route::post('/users/register', [UserController::class, 'store']);
    // Route::put('/users/update', [UserController::class, 'update']);
    // Route::put('/users/enable', [UserController::class, 'enable']);
    // Route::put('/users/disable', [UserController::class, 'disable']);
    // Route::put('/users/delete', [UserController::class, 'destroy']);
});*/

// Route::view('userList', 'livewire.usuarios.component');






