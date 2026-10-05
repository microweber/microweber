<?php

namespace Modules\Attributes\Filament\AttributeResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Attributes\Filament\AttributeResource;

class CreateAttribute extends CreateRecord
{
    protected static string $resource = AttributeResource::class;
}
