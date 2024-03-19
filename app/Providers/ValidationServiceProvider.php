<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\DatabasePresenceVerifier;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('validator', function ($app) {
            $validator = new ValidatorFactory($app['translator'], $app);

            // Tambahkan presence verifier untuk verifikasi keberadaan
            $validator->setPresenceVerifier(new DatabasePresenceVerifier($app['db']));

            return $validator;
        });
    }
}
