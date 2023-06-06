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
Route::get('profilo', 'App\Http\Controllers\UserController@index');
Route::get('galleria', [App\Http\Controllers\GalleryController::class, 'index']);
Route::get('prenotazioni', [App\Http\Controllers\BookingController::class, 'index']);
Route::get('offerte/{luogo?}', [App\Http\Controllers\FlightController::class, 'index']);
Route::post('biglietto', [App\Http\Controllers\BookingController::class, 'ticket']);

Route::prefix('api')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get("exists/{fieldname}", 'App\Http\Controllers\UserController@existsField');
        Route::get("getUserInfo", 'App\Http\Controllers\UserController@getUserInfo');
        Route::post('updateUserInfo', 'App\Http\Controllers\UserController@updateUserInfo');
    });

    Route::post('destinazione', [App\Http\Controllers\DestinationController::class, 'index']);

    Route::prefix('destinazioni')->group(function () {
        Route::get("", [App\Http\Controllers\DestinationController::class, 'getDestinazioni']);
        Route::post("caricaDestinazione", [App\Http\Controllers\DestinationController::class, 'caricaDestinazione']);
        Route::post("eliminaDestinazione", [App\Http\Controllers\DestinationController::class, 'eliminaDestinazione']);
    });

    Route::get('voli/getFlightOffers', [App\Http\Controllers\FlightController::class, 'getFlightOffers']);

    Route::prefix("prenotazioni")->group(function () {
        Route::get('getPrenotazioni', [App\Http\Controllers\BookingController::class, 'getPrenotazioni']);
        Route::post('bookFlight', [App\Http\Controllers\BookingController::class, 'bookFlight']);
        Route::post('eliminaPrenotazione', [App\Http\Controllers\BookingController::class, 'deleteFlight']);
    });
});