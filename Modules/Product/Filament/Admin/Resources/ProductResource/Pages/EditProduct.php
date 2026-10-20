<?php

namespace Modules\Product\Filament\Admin\Resources\ProductResource\Pages;

use Modules\Product\Filament\Admin\Resources\ProductResource;

class EditProduct extends \Modules\Content\Filament\Admin\ContentResource\Pages\EditContent
{

    protected static string $resource = ProductResource::class;

    // Reflect the stored subtype into the "Downloadable product" toggle and the
    // content_data flag into the "Email download link on order" toggle.
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['is_downloadable'] = ($data['subtype'] ?? 'product') === 'downloadable_product';

        $contentData = $this->getRecord()->content_data;
        $data['email_download_on_order'] = filter_var(
            (is_array($contentData) ? ($contentData['email_download_on_order'] ?? '0') : '0'),
            FILTER_VALIDATE_BOOLEAN
        );

        return $data;
    }

    // Map the toggles back to the subtype column + content_data; neither
    // 'is_downloadable' nor 'email_download_on_order' is a Product attribute.
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $isDownloadable = !empty($data['is_downloadable']);
        $data['subtype'] = $isDownloadable ? 'downloadable_product' : 'product';

        $emailOnOrder = $isDownloadable && !empty($data['email_download_on_order']) ? '1' : '0';
        $data['content_data'] = array_merge($data['content_data'] ?? [], [
            'email_download_on_order' => $emailOnOrder,
        ]);

        unset($data['is_downloadable'], $data['email_download_on_order']);

        return $data;
    }
}
