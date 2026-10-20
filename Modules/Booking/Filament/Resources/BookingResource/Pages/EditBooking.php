<?php

namespace Modules\Booking\Filament\Resources\BookingResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Booking\Filament\Resources\BookingResource;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;
}
