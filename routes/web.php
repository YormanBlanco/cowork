<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;

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
    return view('login');
})->name('login');

//Auth
Route::group(['prefix' => 'auth'],function() {
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

//Admin
Route::group(['prefix' => 'admin'],function() {
    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    //Salas
    Route::get('rooms', [RoomController::class, 'getRooms'])->name('rooms.index');
    Route::post('rooms/create', [RoomController::class, 'store']);
    Route::post('rooms/{id}/update', [RoomController::class, 'update']);
    Route::post('rooms/{room}/delete', [RoomController::class, 'destroy']);
    
    //Reservaciones
    Route::get('reservations', [ReservationController::class, 'getReservations'])->name('reservations.index');
    Route::post('reservations/{id}/status', [ReservationController::class, 'updateStatus'])->name('reservation.updateStatus');
});

//Cliente
Route::group(['prefix' => 'client'],function() {
    Route::get('dashboard', function () {
        return view('client.dashboard');
    })->name('client.dashboard');

    //Salas
    Route::get('rooms', [RoomController::class, 'getRooms'])->name('client.rooms');
    
    //Reservaciones
    Route::get('reservations', [ReservationController::class, 'getReservations'])->name('client.reservations.index');
    Route::post('reservations/create', [ReservationController::class, 'store'])->name('client.reservations.create');
});

