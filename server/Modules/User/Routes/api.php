<?php

use Illuminate\Http\Request;
use Modules\User\Http\Controllers\UserController;

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


Route::post('login', [UserController::class,'login']);
Route::post('register', [UserController::class,'register']);
Route::get('formRegister', [UserController::class,'formRegister']);
Route::get('formLogin', [UserController::class,'formLogin']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('user', UserController::class);
});