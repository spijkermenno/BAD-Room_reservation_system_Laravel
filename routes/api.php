<?php

use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Routes for the rooms
Route::get("rooms", [RoomController::class, 'index']);
Route::get("rooms/{id}", [RoomController::class, 'searchByID']);
Route::get("freeRooms", [RoomController::class, 'getFreeRoomsBetweenDates']);
Route::get("allRoomsBetweenDate", [RoomController::class, 'getAllRoomsBetweenDates']);

// Routes for the users
Route::post("login", [UserController::class, 'signin']);

Route::get("user", [UserController::class, 'index']);
Route::post("user", [UserController::class, 'register']);
Route::put("user", [UserController::class, 'update']);
Route::delete("user", [UserController::class, 'delete']);

// Routes for the reservations
Route::get("reservations", [ReservationController::class, 'index']);
Route::post("reservations", [ReservationController::class, 'create']);
Route::put("reservations", [ReservationController::class, 'update']);
Route::delete("reservations", [ReservationController::class, 'delete']);

Route::get("reservations/my", [ReservationController::class, 'getUserReservations']);
Route::get("reservations/{id}", [ReservationController::class, 'searchByID']);

Route::fallback(function (){
    return response("Page not found", 400);
});


