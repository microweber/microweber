<?php

namespace Modules\Faq\Filament\Resources\FaqModuleResource\Pages;

use Modules\Faq\Filament\Resources\FaqModuleResource;
use Filament\Resources\Pages\ListRecords;

class ListFaqs extends ListRecords
{
    protected static string $resource = FaqModuleResource::class;
}
