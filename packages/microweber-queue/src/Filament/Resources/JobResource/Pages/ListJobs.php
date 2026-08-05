<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Filament\Resources\JobResource\Pages;

use Filament\Resources\Pages\ListRecords;
use MicroweberPackages\Queue\Filament\Resources\JobResource;

class ListJobs extends ListRecords
{
    protected static string $resource = JobResource::class;
}
