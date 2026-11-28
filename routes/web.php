<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Pawon Ratu Roso
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/tentang-kami', 'about')->name('about');
    Route::get('/menu', 'menu')->name('menu');
    // Tamu melihat ajakan daftar/masuk, pelanggan yang sudah masuk melihat form booking
    Route::get('/booking', 'booking')->name('booking');
    Route::get('/hubungi-kami', 'contact')->name('contact');
    Route::get('/profil', 'profile')->middleware('auth')->name('profile');
});
Route::post('/booking', [BookingController::class, 'store'])->middleware('auth')->name('booking.store');

// Akun pelanggan
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::view('/masuk', 'pawon.login')->name('login');
    // Maksimal 5 percobaan per menit, supaya kata sandi tidak bisa ditebak terus-menerus
    Route::post('/masuk', 'login')->middleware('throttle:5,1');
    Route::view('/daftar', 'pawon.register')->name('register');
    Route::post('/daftar', 'register');
});
Route::post('/keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Panel admin: hanya akun dengan role "admin" (Gate "admin" di AppServiceProvider)
Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');
    Route::get('/pesanan', [Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::patch('/pesanan/{booking}', [Admin\BookingController::class, 'update'])->name('bookings.update');
    Route::get('/menu', [Admin\MenuController::class, 'index'])->name('menus.index');
    Route::post('/menu', [Admin\MenuController::class, 'store'])->name('menus.store');
    Route::put('/menu', [Admin\MenuController::class, 'update'])->name('menus.update');
});

// Halaman latihan Bootstrap yang lama (sebelumnya di '/')
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');
