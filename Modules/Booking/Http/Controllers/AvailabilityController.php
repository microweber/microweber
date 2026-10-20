<?php

namespace Modules\Booking\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Modules\Booking\Services\AvailabilityService;

/**
 * Public, read-only availability feed the storefront calendar consumes to disable
 * fully-booked nights (date_range) or list open slots (time_slot). Never exposes
 * who booked — only aggregate availability.
 */
class AvailabilityController extends Controller
{
    public function __construct(protected AvailabilityService $availability)
    {
    }

    public function index(Request $request)
    {
        $productId = (int) $request->query('product_id');
        if (!$productId) {
            return response()->json(['error' => 'missing_product_id'], 422);
        }

        $setting = $this->availability->settingsFor($productId);
        if (!$setting) {
            return response()->json(['error' => 'not_bookable'], 404);
        }

        if ($setting->isTimeSlot()) {
            $date = $request->query('date') ?: Carbon::now()->format('Y-m-d');

            return response()->json([
                'type' => 'time_slot',
                'date' => Carbon::parse($date)->format('Y-m-d'),
                'slots' => $this->availability->availableSlots($productId, $date),
            ]);
        }

        // date_range: default to a 12-month window.
        $from = $request->query('from') ?: Carbon::now()->format('Y-m-d');
        $to = $request->query('to') ?: Carbon::parse($from)->addMonths(12)->format('Y-m-d');

        return response()->json([
            'type' => 'date_range',
            'from' => Carbon::parse($from)->format('Y-m-d'),
            'to' => Carbon::parse($to)->format('Y-m-d'),
            'unavailable' => $this->availability->unavailableDates($productId, $from, $to),
            'min_nights' => $setting->min_duration,
            'max_nights' => $setting->max_duration,
        ]);
    }
}
