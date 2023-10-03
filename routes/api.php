<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('auth')->group(function(){
    Route::post('/login',[AuthController::class,'login']);

    Route::post('/refresh',[AuthController::class,'refreshToken'])->middleware('auth:api');

    Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:api');
});

Route::prefix('users')->middleware(['auth:api'])->group(function(){
    Route::get('all', [UserController::class, 'getADusers']);
    Route::get('unit-heads', [UserController::class, 'getUnitHeads']);
    Route::get('', [UserController::class, 'searchUsers']);
});


