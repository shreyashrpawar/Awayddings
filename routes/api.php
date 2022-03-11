<?php

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

Route::post('register',[\App\Http\Controllers\Api\UserController::class,'register']);
Route::post('login',[\App\Http\Controllers\Api\UserController::class,'login']);
Route::get('property/random',[\App\Http\Controllers\Api\PropertyController::class,'getRandomProperty']);
Route::get('properties',[\App\Http\Controllers\Api\PropertyController::class,'searchProperty']);

Route::group(['middleware' => ['jwt.verify']], function() {

});

