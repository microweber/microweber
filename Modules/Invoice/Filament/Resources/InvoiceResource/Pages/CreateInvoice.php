<?php

namespace Modules\Invoice\Filament\Resources\InvoiceResource\Pages;

use Modules\Invoice\Filament\Resources\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;


}
