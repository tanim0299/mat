<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StoreManager;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductItemController;


Route::get('/dashboard',[StoreManager::class,'dashboard'])->name('store_dashboard.index');

Route::resources([
    'product' => ProductController::class,
    'product_item' => ProductItemController::class,
]);
