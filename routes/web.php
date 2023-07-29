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

Route::middleware(['guest'])->group(function () {
    Route::get('/forgot-password', [App\Http\Controllers\ForgotPasswordController::class, 'index'])->name('forgot-password');
    Route::post('/forgot-password-submit', [App\Http\Controllers\ForgotPasswordController::class, 'forgotPasswordSubmit'])->name('forgot-password-submit');
    Route::get('/reset-password/{token}', [App\Http\Controllers\ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset-password');
    Route::post('/reset-password-submit', [App\Http\Controllers\ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset-password-submit');
});

Auth::routes(['register' => false]);
Route::resource('users',\App\Http\Controllers\UserController::class);
Route::resource('leads',\App\Http\Controllers\LeadController::class);
Route::get('/email/verify/{user}', [\App\Http\Controllers\EmailVerificationController::class, 'verify'])
    ->name('email.verify')
    ->middleware('signed');
    Route::get('redirect-to-frontend', [\App\Http\Controllers\EmailVerificationController::class, 'redirectToFrontend'])->name('redirect-to-frontend');
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('pre-bookings/test_booking_cancel_cron',[App\Http\Controllers\HomeController::class, 'test_cancel_booking_cron']);
    Route::get('test_emi_cron',[App\Http\Controllers\HomeController::class, 'test_emi_cron']);
    
    Route::get('/change-password', [App\Http\Controllers\ChangePasswordController::class, 'index'])->name('change-password');
    Route::post('/change-password/submit', [App\Http\Controllers\ChangePasswordController::class, 'changePassword'])->name('change-password.submit');
    Route::delete('property/media',[App\Http\Controllers\PropertyController::class,'deletePropertyMedia']);
    Route::post('property/status',[App\Http\Controllers\PropertyController::class,'updatePropertyStatus']);
    Route::resource('property',App\Http\Controllers\PropertyController::class);
    Route::resource('pre-bookings',App\Http\Controllers\PreBookingSummaryController::class);
    Route::post('pre-bookings/update_details/{id}', [App\Http\Controllers\PreBookingSummaryController::class, 'update_details'])->name('pre-bookings.update_details'); //Pre-booking edit url
    Route::delete('pre-bookings/delete/{id}', [App\Http\Controllers\PreBookingSummaryController::class, 'delete'])->name('delete');

    Route::post('pre_booking_qty_details/update_details/', [App\Http\Controllers\PreBookingSummaryController::class, 'update_qty_details'])->name('pre_booking_qty_details.update'); //Pre-booking edit url

    Route::resource('vendors',App\Http\Controllers\VendorController::class);
    Route::get('property/vendor/{vendor_id}/associate',[App\Http\Controllers\VendorController::class,'showPropertyVendorAssociationPage']);
    Route::post('property/vendor/{vendor_id}/associate',[App\Http\Controllers\VendorController::class,'submitPropertyVendorAssociationForm']);
    Route::resource('property/rate',App\Http\Controllers\PropertyRateController::class);
    Route::post('/media',[App\Http\Controllers\MediaController::class,'upload']);
    Route::resource('bookings',App\Http\Controllers\BookingSummaryController::class);
    Route::get('properties/budget-calculator',[\App\Http\Controllers\PropertyController::class,'getDataOfBudgetCalculator']);

    //Event Management
    Route::resource('artists',App\Http\Controllers\ArtistsController::class);

    Route::get('artist_person',[\App\Http\Controllers\ArtistsController::class,'artist_person_view'])->name('artist_person');
    Route::get('artist_person_create',[\App\Http\Controllers\ArtistsController::class,'artist_person_create'])->name('artist_person_create');
    Route::post('artist_person_store',[\App\Http\Controllers\ArtistsController::class,'artist_person_store'])->name('artist_person_store');
    Route::get('artist_person_edit/{id}',[\App\Http\Controllers\ArtistsController::class,'artist_person_edit'])->name('artist_person_edit');
    Route::post('artist_person_update/{id}',[\App\Http\Controllers\ArtistsController::class,'artist_person_update'])->name('artist_person_update');

    Route::resource('decorations',App\Http\Controllers\DecorationsController::class);
    Route::resource('events',App\Http\Controllers\EventsController::class);
    Route::resource('lightandsounds',App\Http\Controllers\LightandSoundsController::class);
    Route::resource('timeslots',App\Http\Controllers\TimeSlotsController::class);
    Route::post('timeslots/update/{id}', [App\Http\Controllers\TimeSlotsController::class, 'update'])->name('timeslots.update'); //Pre-booking edit url

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

