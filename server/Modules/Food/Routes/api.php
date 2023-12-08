<?php

use Illuminate\Http\Request;
use Modules\Food\Http\Controllers\FoodController;

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

Route::resource('food', FoodController::class);
Route::prefix('food')->group(function () {
    Route::delete('attachment/{id}',[FoodController::class,'destroyFile']);
});