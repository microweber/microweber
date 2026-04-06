<?php

namespace Modules\Form\Filament\Resources\FormEntryResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Form\Filament\Resources\FormEntryResource;

class ListFormEntries extends ListRecords
{
    protected static string $resource = FormEntryResource::class;
}
