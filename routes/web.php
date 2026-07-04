<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\CategoryController as PublicCategoryController;
use App\Http\Controllers\Front\FoodController as PublicFoodController;

// Enable the default Auth routes we installed in Phase 3
Auth::routes();

// Public Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [PublicFoodController::class, 'search'])->name('foods.search');
Route::get('/foods/{food}', [PublicFoodController::class, 'show'])->name('foods.show');
Route::get('/categories', [PublicCategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [PublicCategoryController::class, 'show'])->name('categories.show');

// Basic Dashboard (we will build this later)
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
