<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LineController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Главная страница
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('index');
})->name('home');

/*
|--------------------------------------------------------------------------
| Страницы форм
|--------------------------------------------------------------------------
*/

Route::get('/line', [LineController::class, 'index'])->name('line');

// Route::get('/station', function () {
//     return view('station');
// })->name('station');

Route::get('/vehicle', [VehicleController::class, 'index'])->name('vehicle');

Route::get('/driver', [DriverController::class, 'index'])->name('driver');

Route::get('/user', [UserController::class, 'index'])->name('user');

Route::get(
    '/station/list',
    [StationController::class, 'list']
)->name('station.list');

/*
|--------------------------------------------------------------------------
| Обработка форм
|--------------------------------------------------------------------------
*/



Route::post('/line/store', [LineController::class, 'store'])
    ->name('line.store');

Route::get('/station', [StationController::class, 'index'])
    ->name('station');

Route::post('/station/store', [StationController::class, 'store'])
    ->name('station.store');


Route::post('/vehicle/store', [VehicleController::class, 'store'])
    ->name('vehicle.store');

Route::post('/driver/store', [DriverController::class, 'store'])
    ->name('driver.store');

Route::post('/user/store', [UserController::class, 'store'])
    ->name('user.store');

/*
|--------------------------------------------------------------------------
| Breeze
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


Route::middleware('auth')->group(function () {

    Route::resource('lines', LineController::class);

    Route::resource('stations', StationController::class);

    Route::resource('vehicles', VehicleController::class);

    Route::resource('drivers', DriverController::class);

    Route::resource('users', UserController::class); 
});


require __DIR__ . '/auth.php';