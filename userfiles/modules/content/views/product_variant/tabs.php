<?php
$productVariant = [];
$productVariant['id'] = 0;
$productVariantPrice = 0;
$contentData = \MicroweberPackages\Product\Models\ProductVariant::$contentDataDefault;
$customFields = \MicroweberPackages\Product\Models\ProductVariant::$customFields;

if ($data['id'] > 0) {
    $productVariant = \MicroweberPackages\Product\Models\ProductVariant::where('id',$data['id'])->first();
    $contentData = $productVariant->getContentData();
    $productVariantPrice = $productVariant->price;
}
?>
