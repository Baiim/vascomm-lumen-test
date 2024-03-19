<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Support\Carbon;

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

    public function boot()
    {
        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addDays(7)); // Token akan kedaluwarsa dalam 7 hari
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(14)); // Refresh token akan kedaluwarsa dalam 14 hari
    }
}
