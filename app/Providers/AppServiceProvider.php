<?php

namespace App\Providers;

use App\Services\AuthenticationService;
use App\Services\AuthenticationServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthenticationServiceInterface::class, function ($app) {
            return new AuthenticationService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
