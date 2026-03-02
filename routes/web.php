<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\WaybillController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DriverController;

// Главная страница кидает на логин
Route::get('/', function () {
    return redirect()->route('login');
});

// Защищенные маршруты (только для авторизованных)
Route::middleware(['auth', 'verified'])->group(function () {
    // Дашборд и путевые листы
    Route::get('/dashboard', [WaybillController::class, 'index'])->name('dashboard');
    Route::get('/waybills', [WaybillController::class, 'index'])->name('waybills.index');

    Route::get('/waybills/create', [WaybillController::class, 'create'])->name('waybills.create');
    Route::post('/waybills', [WaybillController::class, 'store'])->name('waybills.store');
    Route::get('/waybills/{waybill}/pdf', [WaybillController::class, 'downloadPdf'])->name('waybills.pdf');

    // Маршруты профиля (которые вызывали ошибку)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['role:admin'])->group(function () {
    // Транспорт
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');

    // Водители
    Route::get('/drivers', [DriverController::class, 'index'])->name('drivers.index');
    Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
    Route::delete('/drivers/{driver}', [DriverController::class, 'destroy'])->name('drivers.destroy');
});

// Healthcheck для DevOps
Route::get('/api/health', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['status' => 'OK', 'database' => 'Connected'], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'ERROR', 'database' => 'Disconnected'], 500);
    }
});

// Подключаем маршруты авторизации Breeze
require __DIR__.'/auth.php';
