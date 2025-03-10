<?php

namespace Modules\Tag\Filament\Resources\TaggedResource\Pages;

use Modules\Tag\Filament\Resources\TaggedResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTagged extends CreateRecord
{
    protected static string $resource = TaggedResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
