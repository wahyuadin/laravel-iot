<?php

use App\Http\Controllers\AuthentifikasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\SesiFalse;
use App\Http\Middleware\SesiTrue;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware([SesiTrue::class])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::any('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::any('/api', [DashboardController::class, 'get']);
    Route::get('/data', [DashboardController::class, 'showDataApi'])->name('data');
    Route::get('/tes', [DashboardController::class,'tes'])->name('tes');
});
Route::middleware([SesiFalse::class])->group(function () {
    Route::any('/register', [AuthentifikasiController::class, 'register'])->name('register');
    Route::any('/', [AuthentifikasiController::class, 'login'])->name('login');
});
Route::get('/logout', [AuthentifikasiController::class,'logout'])->name('logout');


