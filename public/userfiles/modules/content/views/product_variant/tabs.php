<?php
$productVariant = [];
$productVariant['id'] = 0;
$productVariantPrice = 0;
$contentData = \Modules\Product\Models\ProductVariant::$contentDataDefault;
$customFields = \Modules\Product\Models\ProductVariant::$customFields;

if ($data['id'] > 0) {
    $productVariant = \Modules\Product\Models\ProductVariant::where('id',$data['id'])->first();
    $contentData = $productVariant->getContentData();
    $productVariantPrice = $productVariant->price;
}
?>
