<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Pawon Ratu Roso
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/tentang-kami', 'about')->name('about');
    Route::get('/menu', 'menu')->name('menu');
    Route::get('/booking', 'booking')->name('booking');
    Route::get('/hubungi-kami', 'contact')->name('contact');
    Route::get('/masuk', 'login')->name('login');
    Route::get('/daftar', 'register')->name('register');
    Route::get('/profil', 'profile')->name('profile');
});

// Halaman latihan Bootstrap yang lama (sebelumnya di '/')
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');
