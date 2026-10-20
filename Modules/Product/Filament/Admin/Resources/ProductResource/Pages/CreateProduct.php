<?php

namespace Modules\Product\Filament\Admin\Resources\ProductResource\Pages;

use Modules\Content\Filament\Admin\ContentResource\Pages\CreateContent;
use Modules\Product\Filament\Admin\Resources\ProductResource;
use Modules\Product\Filament\Admin\Resources\ProductResource\Concerns\HandlesBookingProductForm;

class CreateProduct extends CreateContent
{
    use HandlesBookingProductForm;

    protected static string $resource = ProductResource::class;

    // Map the product-type toggles to the subtype column + content_data flag.
    // Subtype precedence: bookable > downloadable > plain product.
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $isBookable = $this->extractBookingFormData($data); // pulls booking_* out
        $isDownloadable = !empty($data['is_downloadable']);

        $data['subtype'] = $isBookable
            ? 'booking_product'
            : ($isDownloadable ? 'downloadable_product' : 'product');

        $emailOnOrder = $isDownloadable && !empty($data['email_download_on_order']) ? '1' : '0';
        $data['content_data'] = array_merge($data['content_data'] ?? [], [
            'email_download_on_order' => $emailOnOrder,
        ]);

        unset($data['is_downloadable'], $data['email_download_on_order']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->persistBookingSettings($this->record, $this->stashedIsBookable());
    }
}
