<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/collections', function () {
    return view('collections.index');
})->name('collections');

Route::get('/shop', function () {
    return view('shop.index');
})->name('shop');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');
