<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\CategoryController as PublicCategoryController;
use App\Http\Controllers\Front\FoodController as PublicFoodController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\NutrientController;
use App\Http\Controllers\Admin\HealthStatusController;
use App\Http\Controllers\Admin\ReportsController;

Auth::routes();

// Public Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [PublicFoodController::class, 'search'])->name('foods.search');
Route::get('/foods/{food}', [PublicFoodController::class, 'show'])->name('foods.show');
Route::get('/categories', [PublicCategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [PublicCategoryController::class, 'show'])->name('categories.show');

// Redirect /home to /admin/dashboard for convenience
Route::get('/home', function() {
    return redirect()->route('admin.dashboard');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/audit-log', [ReportsController::class, 'auditLog'])->name('audit-log');
    
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('nutrients', NutrientController::class)->except(['show']);
    Route::resource('health_statuses', HealthStatusController::class)->except(['show']);
    Route::resource('foods', FoodController::class)->except(['show']);
});
