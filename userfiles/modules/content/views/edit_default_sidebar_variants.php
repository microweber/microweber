<?php
$hasVariants = false;
if(content_data($params['content-id'], 'has_variants') == 1) {
    $hasVariants = true;
    $product = \MicroweberPackages\Product\Models\Product::where('id', $params['content-id'])->first();
}
?>

<?php if($hasVariants): ?>

    <div class="card style-1 mb-3 product-variants">
        <div class="card-body pt-3 pb-1">
            <div class="row">
                <div class="col-12">
                    <strong><?php _e("Variants"); ?></strong>

                    <div class="mt-2 mb-2">
                        <?php
                        if (isset($product->variants)):
                            $productVariants = $product->variants;
                            foreach($productVariants as $variant): ?>
                                <a href="<?php echo admin_url(); ?>view:content#action=editpage:<?php echo $variant->id; ?>" class="btn btn-outline-primary btn-sm mt-2"><?php echo $variant->title; ?> <i class="mdi mdi-pencil"></i> </a>
                            <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
