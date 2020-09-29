<?php
$product = [];
$product['id'] = 0;
$productPrice = 0;
$contentData = \MicroweberPackages\Product\Product::$contentDataDefault;
$customFields = \MicroweberPackages\Product\Product::$customFields;

if ($data['id'] > 0) {
    $product = \MicroweberPackages\Product\Product::find($data['id']);
    $contentData = $product->getContentData();
    $productPrice = $product->price;
}
?>

<?php include_once __DIR__ .'/pricing.php'; ?>
<?php include_once __DIR__ .'/inventory.php'; ?>
<?php include_once __DIR__ .'/shipping.php'; ?>