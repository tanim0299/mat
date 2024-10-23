<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StoreManager;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductItemController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ColorController;


Route::get('/dashboard',[StoreManager::class,'dashboard'])->name('store_dashboard.index');


Route::resources([
    'product' => ProductController::class,
    'product_item' => ProductItemController::class,
    'category' => CategoryController::class,
    'brand' => BrandController::class,
    'color' => ColorController::class,
]);

// product item extra routes
Route::get('product_item_trash',[ProductItemController::class,'trash_list'])->name('product_item.trash');
Route::get('product_item_restore/{id}',[ProductItemController::class,'restore'])->name('product_item.restore');
Route::get('product_item_delete/{id}',[ProductItemController::class,'delete'])->name('product_item.delete');
Route::post('product_item_status',[ProductItemController::class,'status'])->name('product_item.status');

// category extra routes

Route::post('change_category_status',[CategoryController::class,'status'])->name('category.status');
Route::get('category_trash',[CategoryController::class,'trash_list'])->name('category.trash');
Route::get('category_restore/{id}',[CategoryController::class,'restore'])->name('category.restore');
Route::get('delete_category/{id}',[CategoryController::class,'delete'])->name('category.delete');

//brand extra routes
Route::get('brand_trash',[BrandController::class,'trash_list'])->name('brand.trash');
Route::post('change_brand_status',[BrandController::class,'status'])->name('brand.status');
Route::get('restore_brand/{id}',[BrandController::class,'restore'])->name('brand.restore');
Route::get('brand_delete/{id}',[BrandController::class,'delete'])->name('brand.delete');

//color extra routes

Route::post('change_color_status',[ColorController::class,'status'])->name('color.status');
Route::get('trashed_colors',[ColorController::class,'trash_list'])->name('color.trash_list');
Route::get('color_restore/{id}',[ColorController::class,'restore'])->name('color.restore');
Route::get('color_delete/{id}',[ColorController::class,'delete'])->name('color.delete');
