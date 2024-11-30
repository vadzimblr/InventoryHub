<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierOrderController;
use App\Http\Controllers\UserCredentialsController;
use App\Http\Controllers\WaybillController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/role', [UserCredentialsController::class, 'getUserRole']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/showAllUsers', [AdminController::class, 'showAllUsers']);
        Route::get('/admin/showAllRoles', [AdminController::class, 'showAllRoles']);
        Route::post('/admin/addUser', [AdminController::class, 'addUser']);
    });
    Route::get('/suppliers',[SupplierController::class,'showAllSuppliers']);
    Route::post('/supplier',[SupplierController::class,'addSupplier']);
    Route::delete('/supplier/{id}',[SupplierController::class,'deleteSupplier']);
    Route::put('/supplier/{id}',[SupplierController::class,'updateSupplier']);

    Route::get('client/products',[ProductController::class,'showAllProductsForClients']);
    Route::post('client/order',[OrderController::class,'placeOrder']);
    Route::get('client/order/{orderId}',[OrderController::class,'showOrderDetails']);
    Route::get('client/order/{orderId}/status/history',[OrderStatuscontroller::class,'getOrderHistory']);
    Route::get('client/orders',[OrderController::class,'showAllOrders']);
    Route::get('client/invoices',[PaymentController::class,'showUnpaidInvoices']);
    Route::patch('client/invoice/{invoiceId}/pay',[PaymentController::class,'payInvoice']);

    Route::post('/product',[ProductController::class,'createProduct']);
    Route::put('/product/{id}',[ProductController::class,'updateProduct']);
    Route::get('/products',[ProductController::class,'showAllProducts']);
    Route::get('/product/{id}',[ProductController::class,'showProduct']);
    Route::patch('/product/{id}/add-stock/{amount}',[ProductController::class,'addStock']);
    Route::patch('/product/{id}/remove-stock/{amount}',[ProductController::class,'removeStock']);
    Route::get('/product/{id}/supplier',[ProductController::class,'getProductSupplierId']);

    Route::get('/account-manager/orders/pending',[OrderStatusController::class,'getPendingOrders']);
    Route::patch('/account-manager/order/{orderId}/mark-as-processing',[OrderStatusController::class,'markOrderAsProcessing']);
    Route::patch('/account-manager/order/{orderId}/mark-as-cancelled',[OrderStatusController::class,'markOrderAsCancelled']);
    Route::get('/account-manager/product/{id}/stock',[ProductController::class,'getStockOfProduct']);

    Route::post('/procurement-manager/supplier-order',[SupplierOrderController::class,'placeSupplierOrder']);
    Route::get('/procurement-manager/supplier-order/{orderId}',[SupplierOrderController::class,'getSupplierOrderById']);
    Route::get('/procurement-manager/supplier-orders/supplier/{supplierId}',[SupplierOrderController::class,'getAllSupplierOrders']);

    Route::post('/accountant/bill',[PaymentController::class,'createBill']);
    Route::get('/accountant/bill/{billId}',[PaymentController::class,'showBillById']);
    Route::get('/accountant/bills/unpaid',[PaymentController::class,'showUnpaidBills']);
    Route::patch('/accountant/bill/{billId}/pay',[PaymentController::class,'payBill']);
    Route::post('/accountant/invoice',[PaymentController::class,'createInvoice']);
    Route::get('/accountant/invoices/client/{clientId}',[PaymentController::class,'showInvoicesByClientId']);
    Route::get('/invoice/{invoiceId}',[PaymentController::class,'showInvoice']);
    Route::get('/invoice/order/{orderId}',[PaymentController::class,'showInvoiceByOrderId']);


    Route::patch('/order/{orderId}/status/mark-as-shipped',[OrderStatusController::class,'markOrderAsShipped']);
    Route::patch('/order/{orderId}/status',[OrderStatusController::class,'updateOrderStatus']);
    Route::get('/orders/status/{status}',[OrderStatusController::class,'getOrdersByStatus']);
    Route::get('/order/{id}/status/processing',[OrderStatusController::class,'getProcessingOrderById']);
    Route::post('/storekeeper/waybill',[WaybillController::class,'createWaybill']);
    Route::get('/storekeeper/waybill/{waybillId}',[WaybillController::class,'getWaybillById']);
    Route::get('/storekeeper/waybills/',[WaybillController::class,'getWaybills']);

});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
