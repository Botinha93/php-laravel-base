<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
USE App\Http\Services\Permission;

class AdminKeycloakServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
