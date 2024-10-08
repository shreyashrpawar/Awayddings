<?php

use App\Http\Controllers\Web\AddonFacilityController;
use App\Http\Controllers\Web\AddonFacilityDetailController;
use App\Http\Controllers\Web\ArtistController;
use App\Http\Controllers\Web\ArtistPersonController;
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
Route::get('/leads/lost-leads', [App\Http\Controllers\LeadController::class, 'lostLeads' ])->name('lost-leads');
Route::resource('users', \App\Http\Controllers\UserController::class);
Route::resource('leads', \App\Http\Controllers\LeadController::class);

Route::get('/email/verify/{user}', [\App\Http\Controllers\EmailVerificationController::class, 'verify'])
    ->name('email.verify')
    ->middleware('signed');
Route::get('redirect-to-frontend', [\App\Http\Controllers\EmailVerificationController::class, 'redirectToFrontend'])->name('redirect-to-frontend');
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('pre-bookings/test_booking_cancel_cron', [App\Http\Controllers\HomeController::class, 'test_cancel_booking_cron']);
    Route::get('test_emi_cron', [App\Http\Controllers\HomeController::class, 'test_emi_cron']);

    Route::get('/change-password', [App\Http\Controllers\ChangePasswordController::class, 'index'])->name('change-password');
    Route::post('/change-password/submit', [App\Http\Controllers\ChangePasswordController::class, 'changePassword'])->name('change-password.submit');
    Route::delete('property/media', [App\Http\Controllers\PropertyController::class, 'deletePropertyMedia']);
    Route::post('property/status', [App\Http\Controllers\PropertyController::class, 'updatePropertyStatus']);
    Route::resource('property', App\Http\Controllers\PropertyController::class);
    Route::resource('pre-bookings', App\Http\Controllers\PreBookingSummaryController::class);
    Route::post('pre-bookings/update_details/{id}', [App\Http\Controllers\PreBookingSummaryController::class, 'update_details'])->name('pre-bookings.update_details'); //Pre-booking edit url
    Route::delete('pre-bookings/delete/{id}', [App\Http\Controllers\PreBookingSummaryController::class, 'delete'])->name('delete');

    Route::post('pre_booking_qty_details/update_details/', [App\Http\Controllers\PreBookingSummaryController::class, 'update_qty_details'])->name('pre_booking_qty_details.update'); //Pre-booking edit url

    //Event Management Pre booking
    Route::resource('event-pre-booking', App\Http\Controllers\EventPreBookingSummaryController::class);
    Route::post('event-pre-booking/update_details/{id}', [App\Http\Controllers\EventPreBookingSummaryController::class, 'update_details'])->name('event-pre-booking.update_details'); //Pre-booking edit url
    Route::delete('event-pre-booking/delete/{id}', [App\Http\Controllers\EventPreBookingSummaryController::class, 'delete'])->name('delete');

    Route::post('event_pre_booking_qty_details/update_details/', [App\Http\Controllers\EventPreBookingSummaryController::class, 'update_qty_details'])->name('event_pre_booking_qty_details.update'); //Pre-booking edit url
    //Event Management Pre booking End

    Route::resource('event-booking', App\Http\Controllers\EventBookingSummaryController::class);

    Route::resource('vendors', App\Http\Controllers\VendorController::class);
    Route::get('property/vendor/{vendor_id}/associate', [App\Http\Controllers\VendorController::class, 'showPropertyVendorAssociationPage']);
    Route::post('property/vendor/{vendor_id}/associate', [App\Http\Controllers\VendorController::class, 'submitPropertyVendorAssociationForm']);
    Route::resource('property/rate', App\Http\Controllers\PropertyRateController::class);
    Route::post('/media', [App\Http\Controllers\MediaController::class, 'upload']);
    Route::resource('bookings', App\Http\Controllers\BookingSummaryController::class);
    Route::get('properties/budget-calculator', [\App\Http\Controllers\PropertyController::class, 'getDataOfBudgetCalculator']);

    //Event Management
    Route::controller(ArtistController::class)->group(function () {
        Route::get('/artists', 'index')->name('artists.index');
        Route::get('/artists/create', 'create')->name('artists.create');
        Route::get('/artists/{id}/edit', 'edit')->name('artists.edit');
        Route::post('/artists', 'store')->name('artists.store');
        Route::post('/artists/{artist}/update', 'update')->name('artists.update');
    });

    Route::controller(ArtistPersonController::class)->group(function () {
        Route::get('/artist-persons', 'index')->name('artist_person');
        Route::get('/artist-persons/create', 'create')->name('artist_person_create');
        Route::get('/artist-persons/{id}/edit', 'edit')->name('artist_person_edit');
        Route::post('/artist-persons/store', 'store')->name('artist_person_store');
        Route::post('/artist-persons/{person}/update', 'update')->name('artist_person_update');
    });


    Route::resource('decorations', App\Http\Controllers\DecorationsController::class);
    Route::post('decoration_update_status', [\App\Http\Controllers\DecorationsController::class, 'decoration_updateStatus'])->name('decoration_update_status');

    Route::resource('events', App\Http\Controllers\EventsController::class);
    Route::post('event_update_status', [\App\Http\Controllers\EventsController::class, 'event_updateStatus'])->name('event_update_status');

    Route::resource('lightandsounds', App\Http\Controllers\LightandSoundsController::class);
    Route::post('lightandsound_update_status', [\App\Http\Controllers\LightandSoundsController::class, 'lightandsound_updateStatus'])->name('lightandsound_update_status');

    Route::resource('timeslots', App\Http\Controllers\TimeSlotsController::class);
    Route::post('timeslots/update/{id}', [App\Http\Controllers\TimeSlotsController::class, 'update'])->name('timeslots.update');
    Route::post('timeslot_update_status', [\App\Http\Controllers\TimeSlotsController::class, 'timeslot_updateStatus'])->name('timeslot_update_status');


    Route::controller(AddonFacilityController::class)->group(function () {
        Route::get('/facilities', 'index')->name('addon_facilities.index');
        Route::post('/facilities/store', 'store')->name('addon_facilities.store');
        Route::post('/facilities/update/{emAddonFacility}', 'update')->name('addon_facilities.update');
    });

    Route::controller(AddonFacilityDetailController::class)->group(function () {
        Route::get('/facilities/{emAddonFacility}/details', 'index')->name('addon_facility_details.index');
        Route::post('/facilities/details/store', 'store')->name('addon_facility_details.store');
        Route::post('/facilities/{facilityDetails}/details', 'update')->name('addon_facility_details.update');
    });

    Route::get('generate-pdf/{prebookingid}', [\App\Http\Controllers\EventBookingSummaryController::class, 'generatePDF']);

});
Route::prefix('settings')->middleware(['auth'])->group(function () {
    Route::resource('locations', App\Http\Controllers\Settings\LocationController::class);
    Route::resource('amenities', App\Http\Controllers\Settings\HotelAmenitiesController::class);
    Route::resource('room-inclusion', App\Http\Controllers\Settings\HotelFacilitiesController::class);
});
Route::prefix('permissions-settings')->middleware(['auth','role:superAdmin'])->group(function () {
    Route::resource('permissions', App\Http\Controllers\PermissionsController::class); 
    Route::post('permissions/update', [App\Http\Controllers\PermissionsController::class, 'updatePermissions'])->name('permissions.updatePermissions');
    Route::post('create-role', [App\Http\Controllers\PermissionsController::class, 'createRole'])->name('permissions.create-role');
    Route::post('update-role', [App\Http\Controllers\PermissionsController::class, 'updateRole'])->name('permissions.update-role');
});


Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::get('property/{id}', [App\Http\Controllers\PropertyController::class, 'getPropertyDetails']);
    Route::get('locations', [App\Http\Controllers\Settings\LocationController::class, 'getAllActiveLocation']);
    Route::get('image-category', [App\Http\Controllers\MediaSubCategoryController::class, 'getAllImageCategory']);
    Route::get('video-category', [App\Http\Controllers\MediaSubCategoryController::class, 'getAllVideoCategory']);
    Route::get('menu-category', [App\Http\Controllers\MediaSubCategoryController::class, 'getAllMenuCategory']);
    Route::get('property-chargable-category', [App\Http\Controllers\HotelChargableTypeController::class, 'getAllPropertyChargableCategory']);
    Route::get('amenities-room-inclusion', [App\Http\Controllers\Settings\HotelAmenitiesController::class, 'getAllAmenitiesRoomInclusion']);
});

