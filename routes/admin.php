<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BackendController;
use App\Http\Controllers\Admin\MenuLabelController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RoleController;

Route::get('/',[BackendController::class,'home'])->name('admin.home');
Route::resources([
    'menu_label' => MenuLabelController::class,
    'menu' => MenuController::class,
    'role' => RoleController::class,
]);

// menu_label_extra_routes
Route::post('changeMenuLabelStatus',[MenuLabelController::class,'status'])->name('menu_label.status');
Route::get('menu_label_trash',[MenuLabelController::class,'trash_list'])->name('menu_label.trash_list');
Route::get('restore_menu_label/{id}',[MenuLabelController::class,'restore'])->name('menu_label.restore');
Route::get('delete_menu_label/{id}',[MenuLabelController::class,'delete'])->name('menu_label.delete');

// menu extra routes;
Route::get('get_menu_labels',[MenuController::class,'get_menu_labels'])->name('menu.get_labels');
Route::get('get_parent',[MenuController::class,'get_parent'])->name('menu.get_parent');
Route::get('menu_trash_list',[MenuController::class,'trash_list'])->name('menu.trash_list');
Route::get('menu_restore/{id}',[MenuController::class,'restore'])->name('menu.restore');
Route::get('menu_delete/{id}',[MenuController::class,'delete'])->name('menu.delete');
Route::post('change_menu_status',[MenuController::class,'status'])->name('menu.status');
