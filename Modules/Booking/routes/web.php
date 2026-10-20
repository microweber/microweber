<?php

use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Controllers\AvailabilityController;

Route::middleware('web')->group(function () {
    // Public availability feed for the storefront calendar.
    // date_range  → ?product_id=&from=&to=  → { unavailable: ['Y-m-d', ...] }
    // time_slot   → ?product_id=&date=       → { slots: [{start,end,remaining}] }
    Route::get('booking/availability', [AvailabilityController::class, 'index'])
        ->name('booking.availability');
});
