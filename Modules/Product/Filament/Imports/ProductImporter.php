<?php

namespace Modules\Product\Filament\Imports;

use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;
use Modules\Product\Models\Product;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('url')
                ->rules(['max:255']),
            ImportColumn::make('sku')
                ->rules(['max:255']),
            ImportColumn::make('price')
                ->numeric()
                ->rules(['numeric', 'min:0']),
            ImportColumn::make('qty')
                ->rules(['max:50']),
            ImportColumn::make('is_active')
                ->boolean()
                ->rules(['boolean']),
            ImportColumn::make('description')
                ->rules(['max:65535']),
            ImportColumn::make('content_meta_title')
                ->rules(['max:255']),
            ImportColumn::make('content_meta_keywords')
                ->rules(['max:500']),
            ImportColumn::make('content_meta_description')
                ->rules(['max:500']),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('update_existing')
                ->label('Update existing products by URL')
                ->default(false),
            Checkbox::make('skip_existing')
                ->label('Skip if product with URL already exists')
                ->default(false),
        ];
    }

    public function resolveRecord(): ?Product
    {
        $url = $this->data['url'] ?? null;

        if (empty($this->data['title'])) {
            return null;
        }

        // Generate URL if not provided
        if (empty($url)) {
            $url = str_slug($this->data['title']);
            $this->data['url'] = $url;
        }

        // Check if product exists by URL
        if (!empty($url)) {
            $existingProduct = Product::where('url', $url)->first();

            if ($existingProduct) {
                if (isset($this->options['update_existing']) && $this->options['update_existing']) {
                    // Update existing product
                    $existingProduct->fill($this->data);
                    $existingProduct->save();
                    return $existingProduct;
                }

                if (isset($this->options['skip_existing']) && $this->options['skip_existing']) {
                    // Skip existing product
                    return null;
                }

                // If neither option is set, generate unique URL
                $url = $url . '-' . uniqid();
                $this->data['url'] = $url;
            }
        }

        // Set required fields
        $this->data['content_type'] = 'product';
        $this->data['subtype'] = 'product';

        // Set defaults for missing fields
        if (!isset($this->data['is_active'])) {
            $this->data['is_active'] = true;
        }

        return new Product($this->data);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your product import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
