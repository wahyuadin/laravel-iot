<?php

use App\Http\Controllers\DashboardController;
use App\Http\Middleware\SesiTrue;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware([SesiTrue::class])->group(function(){
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// }
