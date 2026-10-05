<?php

namespace Modules\Log\Filament\LogResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Log\Filament\LogResource;

class ListLogs extends ListRecords
{
    protected static string $resource = LogResource::class;

    // Read-only viewer — no "New" header action.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
