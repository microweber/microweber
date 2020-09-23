<?php
if ($data['id'] > 0) {
    $product = \MicroweberPackages\Product\Product::find($data['id']);
    $contentData = $product->getContentData();
} else {
    $contentData = \MicroweberPackages\Product\Product::$contentDataDefault;
}
?>

<?php include_once __DIR__ .'/pricing.php'; ?>
<?php include_once __DIR__ .'/inventory.php'; ?>
<?php include_once __DIR__ .'/shipping.php'; ?>