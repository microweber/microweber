<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Filament\Resources\FailedJobResource\Pages;

use Filament\Resources\Pages\ListRecords;
use MicroweberPackages\Queue\Filament\Resources\FailedJobResource;

class ListFailedJobs extends ListRecords
{
    protected static string $resource = FailedJobResource::class;
}
