<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/kasir', function () {
    return view('kasir.index');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});
