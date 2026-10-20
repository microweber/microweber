<?php

namespace Modules\Product\Filament\Admin\Resources\ProductResource\Pages;

use Modules\Product\Filament\Admin\Resources\ProductResource;
use Modules\Product\Filament\Admin\Resources\ProductResource\Concerns\HandlesBookingProductForm;

class EditProduct extends \Modules\Content\Filament\Admin\ContentResource\Pages\EditContent
{
    use HandlesBookingProductForm;

    protected static string $resource = ProductResource::class;

    // Reflect the stored subtype + content_data + booking settings back into the
    // product-type toggles/fields.
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['is_downloadable'] = ($data['subtype'] ?? 'product') === 'downloadable_product';

        $contentData = $this->getRecord()->content_data;
        $data['email_download_on_order'] = filter_var(
            (is_array($contentData) ? ($contentData['email_download_on_order'] ?? '0') : '0'),
            FILTER_VALIDATE_BOOLEAN
        );

        // is_bookable + booking_* fields from the settings row (if any).
        $this->fillBookingFormData($this->getRecord(), $data);

        return $data;
    }

    // Map the toggles back to the subtype column + content_data; the booking_*
    // and is_* keys are not Product attributes. Precedence: bookable >
    // downloadable > plain product.
    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function afterSave(): void
    {
        $this->persistBookingSettings($this->record, $this->stashedIsBookable());
    }
}
