<?php

namespace Modules\Invoice\Filament\Resources\InvoiceResource\Pages;

use Modules\Invoice\Filament\Resources\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['unique_hash'] = Str::random(60);
        
        // Convert amounts to cents if they're not already
        if (isset($data['sub_total'])) {
            $data['sub_total'] = $data['sub_total'] * 100;
        }
        if (isset($data['total'])) {
            $data['total'] = $data['total'] * 100;
        }
        if (isset($data['due_amount'])) {
            $data['due_amount'] = $data['due_amount'] * 100;
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
