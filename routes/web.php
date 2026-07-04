<?php

use Illuminate\Support\Facades\Route;

Route::get('/auth/login', function () {
    return view('auth.login');
})->name('login');

