<?php

namespace Modules\Order\Filament\Imports;

use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;

class OrderImporter extends Importer
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('order_reference_id')
                ->rules(['max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('first_name')
                ->rules(['max:255']),
            ImportColumn::make('last_name')
                ->rules(['max:255']),
            ImportColumn::make('order_status')
                ->rules(['max:50']),
            ImportColumn::make('amount')
                ->numeric()
                ->rules(['numeric', 'min:0']),
            ImportColumn::make('currency')
                ->rules(['max:10']),
            ImportColumn::make('is_paid')
                ->boolean()
                ->rules(['boolean']),
            ImportColumn::make('order_completed')
                ->boolean()
                ->rules(['boolean']),
            ImportColumn::make('country')
                ->rules(['max:255']),
            ImportColumn::make('city')
                ->rules(['max:255']),
            ImportColumn::make('state')
                ->rules(['max:255']),
            ImportColumn::make('zip')
                ->rules(['max:50']),
            ImportColumn::make('address')
                ->rules(['max:1000']),
            ImportColumn::make('address2')
                ->rules(['max:1000']),
            ImportColumn::make('phone')
                ->rules(['max:100']),
            ImportColumn::make('shipping_amount')
                ->numeric()
                ->rules(['numeric', 'min:0']),
            ImportColumn::make('discount_value')
                ->numeric()
                ->rules(['numeric', 'min:0']),
            ImportColumn::make('taxes_amount')
                ->numeric()
                ->rules(['numeric', 'min:0']),
            ImportColumn::make('items_count')
                ->numeric()
                ->rules(['integer', 'min:0']),
            ImportColumn::make('payment_provider')
                ->rules(['max:255']),
            ImportColumn::make('shipping_provider')
                ->rules(['max:255']),
            ImportColumn::make('promo_code')
                ->rules(['max:255']),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('update_existing')
                ->label('Update existing orders by reference ID')
                ->default(false),
            Checkbox::make('skip_existing')
                ->label('Skip if order with reference ID already exists')
                ->default(false),
        ];
    }

    public function resolveRecord(): ?Order
    {
        $referenceId = $this->data['order_reference_id'] ?? null;

        if (empty($this->data['email'])) {
            return null;
        }

        // Generate reference ID if not provided
        if (empty($referenceId)) {
            $referenceId = 'IMPORT-' . now()->format('YmdHis') . '-' . uniqid();
            $this->data['order_reference_id'] = $referenceId;
        }

        // Validate order status
        if (!empty($this->data['order_status'])) {
            $validStatuses = array_map(fn ($status) => $status->value, OrderStatus::cases());
            if (!in_array($this->data['order_status'], $validStatuses)) {
                // Try to match by name
                $statusByName = OrderStatus::tryFrom(strtolower($this->data['order_status']));
                if ($statusByName) {
                    $this->data['order_status'] = $statusByName->value;
                } else {
                    $this->data['order_status'] = OrderStatus::New->value;
                }
            }
        } else {
            $this->data['order_status'] = OrderStatus::New->value;
        }

        // Check if order exists by reference ID
        if (!empty($referenceId)) {
            $existingOrder = Order::where('order_reference_id', $referenceId)->first();

            if ($existingOrder) {
                if (isset($this->options['update_existing']) && $this->options['update_existing']) {
                    // Update existing order
                    $existingOrder->fill($this->data);
                    $existingOrder->save();
                    return $existingOrder;
                }

                if (isset($this->options['skip_existing']) && $this->options['skip_existing']) {
                    // Skip existing order
                    return null;
                }

                // If neither option is set, generate unique reference ID
                $referenceId = $referenceId . '-' . uniqid();
                $this->data['order_reference_id'] = $referenceId;
            }
        }

        // Set defaults for missing fields
        if (!isset($this->data['is_paid'])) {
            $this->data['is_paid'] = false;
        }

        if (!isset($this->data['order_completed'])) {
            $this->data['order_completed'] = false;
        }

        if (!isset($this->data['currency'])) {
            $this->data['currency'] = 'USD';
        }

        if (!isset($this->data['currency_code'])) {
            $this->data['currency_code'] = $this->data['currency'] ?? 'USD';
        }

        return new Order($this->data);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your order import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
