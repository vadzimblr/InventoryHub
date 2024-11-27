<?php

namespace App\Providers;

use App\Services\AdminService\AdminService;
use App\Services\AdminService\Api\AdminServiceInterface;
use App\Services\AuthenticationService;
use App\Services\AuthenticationServiceInterface;
use App\Services\SupplierService\Api\SupplierServiceInterface;
use App\Services\SupplierService\SupplierService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthenticationServiceInterface::class, AuthenticationService::class);
        $this->app->bind(AdminServiceInterface::class, AdminService::class);
        $this->app->bind(SupplierServiceInterface::class, SupplierService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
