<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::delete('/logout', [AuthController::class, 'logout']);

// Appoimtments Routes
Route::post('/appointments', [AppointmentController::class, 'store'])->middleware('auth:sanctum');

// Opening-Hours Routes
Route::get('/booking-hours', [AppointmentController::class, 'getBookingHours']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
