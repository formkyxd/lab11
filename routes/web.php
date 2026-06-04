<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\LineController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/line', [LineController::class, 'index'])->name('line');
    Route::post('/line/store', [LineController::class, 'store'])->name('line.store');

    Route::get('/station', [StationController::class, 'index'])->name('station');
    Route::get('/station/list', [StationController::class, 'list'])->name('station.list');
    Route::post('/station/store', [StationController::class, 'store'])->name('station.store');

    Route::get('/vehicle', [VehicleController::class, 'index'])->name('vehicle');
    Route::post('/vehicle/store', [VehicleController::class, 'store'])->name('vehicle.store');

    Route::get('/driver', [DriverController::class, 'index'])->name('driver');
    Route::post('/driver/store', [DriverController::class, 'store'])->name('driver.store');

    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('lines', LineController::class);
    Route::resource('stations', StationController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
