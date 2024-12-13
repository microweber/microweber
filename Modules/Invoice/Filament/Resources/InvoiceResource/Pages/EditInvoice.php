<?php

namespace Modules\Invoice\Filament\Resources\InvoiceResource\Pages;

use Modules\Invoice\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
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
