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
Route::prefix('v1')->group(function () {
    Route::post('register',[\App\Http\Controllers\Api\UserController::class,'register']);
    Route::get('/email/verify/{user}', [\App\Http\Controllers\Api\EmailVerificationController::class, 'verify'])
    ->name('email.verify')
    ->middleware('signed');
    Route::post('login',[\App\Http\Controllers\Api\UserController::class,'login']);
    Route::post('leads/capture',[\App\Http\Controllers\Api\LandingLeadsController::class,'store']);
    Route::get('property/random',[\App\Http\Controllers\Api\PropertyController::class,'getRandomProperty']);
    Route::get('properties',[\App\Http\Controllers\Api\PropertyController::class,'searchProperty']);
    Route::get('properties/{id}',[\App\Http\Controllers\Api\PropertyController::class,'propertyDetails']);
    Route::get('properties/{id}/available',[\App\Http\Controllers\Api\PropertyController::class,'propertyAvailable']);
    Route::get('properties/{id}/budget',[\App\Http\Controllers\Api\PropertyController::class,'getPropertyBudget']);
    Route::get('locations',[\App\Http\Controllers\Api\LocationController::class,'getActiveLocation']);
    Route::post('sendForgetpasswordLink',[\App\Http\Controllers\Api\ForgotPasswordController::class,'sendForgetpasswordLink']);
    Route::post('submitResetPassword',[\App\Http\Controllers\Api\ForgotPasswordController::class,'submitResetPassword']);

    Route::get('properties/{id}/budget-calculator',[\App\Http\Controllers\Api\PropertyController::class,'getPropertyDetails']);
    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('pre-booking',[\App\Http\Controllers\Api\PreBookingController::class,'submit']);
        Route::post('change-password',[\App\Http\Controllers\Api\ChangePasswordController::class,'changePassword']);
        Route::get('pre-booking',[\App\Http\Controllers\Api\PreBookingController::class,'index']);
        Route::get('me',[\App\Http\Controllers\Api\UserController::class,'userInformation']);
        Route::get('getEventManagementData',[\App\Http\Controllers\Api\EventManagementController::class,'event_details']);
    });

    Route::get('top-destination',[\App\Http\Controllers\Api\PropertyController::class,'propertyCountWithLocation']);

});
