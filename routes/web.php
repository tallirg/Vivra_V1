<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

// Resources
Route::resource('admin/articles', ArticleController::class);
Route::resource('admin/categories', CategoryController::class);
Route::resource('admin/brands', BrandController::class);
Route::resource('admin/orders', OrderController::class);
Route::resource('admin/reviews', ReviewController::class);
