<?php

use App\Http\Controllers\LoginController;
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

Route::get('/', function () {
    return redirect('login');
});

Route::get('login', [LoginController::class, 'login_form']);
Route::post('login', [LoginController::class, 'do_login']);

Route::get('signup', [LoginController::class, 'signup_form']);
Route::post('signup', [LoginController::class, 'do_signup']);
Route::get('logout', [LoginController::class, 'logout']);

Route::get('home', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('profilo', [App\Http\Controllers\ProfileController::class, 'index']);
Route::get('galleria', [App\Http\Controllers\GalleryController::class, 'index']);
Route::get('prenotazioni', [App\Http\Controllers\BookingController::class, 'index']);
Route::get('offerte', [App\Http\Controllers\FlightController::class, 'index']);