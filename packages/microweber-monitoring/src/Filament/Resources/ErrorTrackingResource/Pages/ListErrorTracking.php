<?php

namespace MicroweberPackages\Monitoring\Filament\Resources\ErrorTrackingResource\Pages;

use MicroweberPackages\Monitoring\Filament\Resources\ErrorTrackingResource;
use Filament\Resources\Pages\ListRecords;

class ListErrorTracking extends ListRecords
{
    protected static string $resource = ErrorTrackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action needed for error tracking
        ];
    }
}
