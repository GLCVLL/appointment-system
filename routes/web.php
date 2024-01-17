<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClosedDayController;
use App\Http\Controllers\Guest\HomeController as GuestHomeController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\OpeningHourController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home Routes
Route::get('/', [GuestHomeController::class, 'index'])->name('guest.home');


// Admin Routes
Route::prefix('/admin')->name('admin')->middleware(['auth', 'verified', 'checkrole:admin'])->name('admin.')->group(function () {

    // Home Routes
    Route::get('/', [AdminHomeController::class, 'index'])->name('home');

    // Categories Routes
    Route::resource('categories', CategoryController::class); // CRUD

    // Service Routes
    Route::resource('services', ServiceController::class); // CRUD

    // Appointment Routes
    Route::resource('appointments', AppointmentController::class); // CRUD

    // Opening Hours Routes
    Route::resource('opening-hours', OpeningHourController::class); // CRUD

    // Closed Days Routes
    Route::resource('closed-days', ClosedDayController::class); // CRUD

    // Users Routes
    Route::resource('users', UserController::class); // CRUD

});


// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Auth Routes
require __DIR__ . '/auth.php';
