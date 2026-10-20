<?php

namespace Modules\Booking\Filament\Resources\BookingResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Booking\Filament\Resources\BookingResource;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;
}
