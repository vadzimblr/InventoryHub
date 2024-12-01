<?php

namespace App\Providers;

use App\Services\AdminService\AdminService;
use App\Services\AdminService\Api\AdminServiceInterface;
use App\Services\AuthenticationService;
use App\Services\AuthenticationServiceInterface;
use App\Services\NotificationService\Api\NotificationServiceInterface;
use App\Services\NotificationService\NotificationService;
use App\Services\OrderService\Api\OrderServiceInterface;
use App\Services\OrderService\Api\OrderStatusServiceInterface;
use App\Services\OrderService\OrderService;
use App\Services\OrderService\OrderStatusService;
use App\Services\PaymentService\Api\PaymentServiceInterface;
use App\Services\PaymentService\PaymentService;
use App\Services\ProductService\Api\ProductServiceInterface;
use App\Services\ProductService\ProductService;
use App\Services\SupplierService\Api\SupplierOrderServiceInterface;
use App\Services\SupplierService\Api\SupplierServiceInterface;
use App\Services\SupplierService\SupplierOrderService;
use App\Services\SupplierService\SupplierService;
use App\Services\WaybillService\Api\WaybillServiceInterface;
use App\Services\WaybillService\WaybillService;
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
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(OrderStatusServiceInterface::class, OrderStatusService::class);
        $this->app->bind(SupplierOrderServiceInterface::class, SupplierOrderService::class);
        $this->app->bind(PaymentServiceInterface::class, PaymentService::class);
        $this->app->bind(WaybillServiceInterface::class, WaybillService::class);
        $this->app->bind(NotificationServiceInterface::class,NotificationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
