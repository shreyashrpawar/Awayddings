<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect(route('login'));
});


Auth::routes();
Route::resource('users',\App\Http\Controllers\UserController::class);
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::delete('property-media',[App\Http\Controllers\PropertyController::class,'deletePropertyMedia']);
    Route::resource('property',App\Http\Controllers\PropertyController::class);
    Route::get('property-vendor/{vendor_id}/associate',[App\Http\Controllers\VendorController::class,'showPropertyVendorAssociationPage']);
    Route::post('property-vendor/{vendor_id}/associate',[App\Http\Controllers\VendorController::class,'submitPropertyVendorAssociationForm']);
    Route::resource('property-vendors',App\Http\Controllers\VendorController::class);
    Route::resource('property-rate',App\Http\Controllers\PropertyRateController::class);
});
Route::prefix('settings')->middleware(['auth'])->group(function () {
    Route::resource('locations',App\Http\Controllers\Settings\LocationController::class);
});

