<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomingMailController;
use App\Http\Controllers\OutcomingMailController;
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

Route::get('/login', function(){
    return view('welcome');
});
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/', [DashboardController::class, 'index']);

Route::get('/incoming-mail', [IncomingMailController::class, 'index']);
Route::get('/outcoming-mail', [OutcomingMailController::class, 'index']);

// Route untuk atasan
Route::group(['middleware' => 'role:atasan'], function () {

});

// Route untuk admin
Route::group(['middleware' => 'role:admin'], function () {
    Route::post('/incoming-letter-store', [IncomingMailController::class, 'store']);
    Route::put('/incoming-letter-update/{id}', [IncomingMailController::class, 'update']);
    Route::delete('/incoming-letter-delete/{id}', [IncomingMailController::class, 'destroy']);

    Route::post('/outcoming-letter-store', [OutcomingMailController::class, 'store']);
    Route::put('/outcoming-letter-update/{id}', [OutcomingMailController::class, 'update']);
    Route::delete('/outcoming-letter-delete/{id}', [OutcomingMailController::class, 'destroy']);
});
