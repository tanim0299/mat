<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StoreManager;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductItemController;
use App\Http\Controllers\Admin\CategoryController;


Route::get('/dashboard',[StoreManager::class,'dashboard'])->name('store_dashboard.index');


Route::resources([
    'product' => ProductController::class,
    'product_item' => ProductItemController::class,
    'category' => CategoryController::class,
]);

// product item extra routes
Route::get('product_item_trash',[ProductItemController::class,'trash_list'])->name('product_item.trash');
Route::get('product_item_restore/{id}',[ProductItemController::class,'restore'])->name('product_item.restore');
Route::get('product_item_delete/{id}',[ProductItemController::class,'delete'])->name('product_item.delete');
Route::post('product_item_status',[ProductItemController::class,'status'])->name('product_item.status');
