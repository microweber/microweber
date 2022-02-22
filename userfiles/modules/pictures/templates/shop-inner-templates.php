<?php

/*

type: layout

name: Shop Inner For Templates

description: Default skin for shop inner of the templates

*/

?>

<?php

$pictureElementId = 'module-image-' . $params['id'];
$itemData = content_data(content_id());

if (!isset($itemData['label'])) {
    $itemData['label'] = '';
}
if (!isset($itemData['label-color'])) {
    $itemData['label-color'] = '';
}

if (is_array($data)): ?>
    <div class="shop-inner-gallery">
        <?php if (sizeof($data) > 1) { ?>
            <div class="shop-inner-gallery-thumbnails">
                <?php $count = -1; foreach ($data as $item): $count++; ?>
                    <a class="mx-0"
                       href="<?php print thumbnail($item['filename'], 1080, 1080); ?>"
                       onclick="setProductImage('<?php print $pictureElementId; ?>', '<?php print thumbnail($item['filename'], 1920, 1920); ?>', <?php print $count; ?>);return false;"
                       style="background-image: url('<?php print thumbnail ($item['filename'], 800, 800); ?>');">
                    </a>
                <?php endforeach; ?>
            </div>
        <?php } ?>
        <div class="shop-inner-big-image position-relative">
            <?php $price = app()->shop_manager->get_product_prices(content_id(), true);



            if (isset($itemData['label-type']) && $itemData['label-type'] === 'text'): ?>
                <div class="position-absolute  top-0 left-0 m-2" style="z-index: 9;">
                    <div class="badge text-white px-3" style="background-color: <?php echo $itemData['label-color']; ?>;"><?php echo $itemData['label']; ?></div>
                </div>
            <?php endif;


            if (isset($price[0]) and isset($price[0]['original_value'])): ?>

                <?php
                $oldFigure = floatval($price[0]['custom_value']);
                $newFigure = floatval($price[0]['original_value']);
                $percentChange = 0;

                ?>

                <?php if ($oldFigure < $newFigure): ?>
                    <?php
                    $percentChange = (1 - $oldFigure / $newFigure) * 100;
                    ?>
                <?php endif; ?>

                <?php if (isset($itemData['label-type']) && $itemData['label-type'] === 'percent' && $percentChange > 0): ?>
                    <div class="discount-label">
                        <span class="discount-percentage">
                                <?php echo number_format($percentChange, 1); ?>%
                        </span>
                        <span class="discount-label-text"><?php _lang("Discount"); ?></span>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <img src="<?php print thumbnail($data[0]['filename'], 1080, 1080); ?>" id="<?php print $pictureElementId; ?>" />
        </div>
    </div>


    <script>

        var setProductImage = function (id, url, index) {
            var el = document.getElementById(id);
            el.dataset.index = index;
            var parent = el.parentElement;
            mw.spinner(({element: parent, size: 60, decorate: true})).show();
            mw.element({
                tag: 'img',
                props: {
                    src: url,
                    onload: function (){
                        el.src = url;
                        mw.spinner(({element: parent})).hide();
                    }
                }
            })
        }

        var gallery = <?php print json_encode($data); ?>;

        document.getElementById('<?php print $pictureElementId; ?>').addEventListener('click', function(){
            mw.gallery(gallery, Number(this.dataset.index || 0));
        });
    </script>

<?php endif; ?>
