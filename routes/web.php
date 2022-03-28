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


Auth::routes(['register' => false]);
Route::resource('users',\App\Http\Controllers\UserController::class);
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::delete('property/media',[App\Http\Controllers\PropertyController::class,'deletePropertyMedia']);
    Route::resource('property',App\Http\Controllers\PropertyController::class);
    Route::resource('vendors',App\Http\Controllers\VendorController::class);
    Route::get('property/vendor/{vendor_id}/associate',[App\Http\Controllers\VendorController::class,'showPropertyVendorAssociationPage']);
    Route::post('property/vendor/{vendor_id}/associate',[App\Http\Controllers\VendorController::class,'submitPropertyVendorAssociationForm']);
    Route::resource('property/rate',App\Http\Controllers\PropertyRateController::class);
    Route::post('/media',[App\Http\Controllers\MediaController::class,'upload']);
});
Route::prefix('settings')->middleware(['auth'])->group(function () {
    Route::resource('locations',App\Http\Controllers\Settings\LocationController::class);
    Route::resource('amenities',App\Http\Controllers\Settings\HotelAmenitiesController::class);
    Route::resource('room-inclusion',App\Http\Controllers\Settings\HotelFacilitiesController::class);
});

Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::get('property/{id}',[App\Http\Controllers\PropertyController::class,'getPropertyDetails']);
    Route::get('locations',[App\Http\Controllers\Settings\LocationController::class,'getAllActiveLocation']);
    Route::get('image-category',[App\Http\Controllers\MediaSubCategoryController::class,'getAllImageCategory']);
    Route::get('video-category',[App\Http\Controllers\MediaSubCategoryController::class,'getAllVideoCategory']);
    Route::get('menu-category',[App\Http\Controllers\MediaSubCategoryController::class,'getAllMenuCategory']);
    Route::get('property-chargable-category',[App\Http\Controllers\HotelChargableTypeController::class,'getAllPropertyChargableCategory']);
    Route::get('amenities-room-inclusion',[App\Http\Controllers\Settings\HotelAmenitiesController::class,'getAllAmenitiesRoomInclusion']);

});

