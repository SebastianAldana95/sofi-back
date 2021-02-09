<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*Fortify::authenticateUsing(function ($request) {
            $validated = Auth::validate([
                'sAMAccountName' => $request->username,
                'password' => $request->password
            ]);

            return $validated ? Auth::getLastAttempted() : null;
        });*/
    }
}
