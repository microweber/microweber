<?php

/*

type: layout

name: Shop Inner For Templates 2

description: Default skin for shop inner of the templates 2

*/

?>
<style>
    .mw-shop-inner-skin-discount-label-2 {
        border-radius: 20px;
        border: 1px solid #ef5c5c;
        padding: 3px 10px;
        color: #ef5c5c;
        font-weight: 400;
        font-size: 14px;

        position: absolute;
        top: 12px;
        left: 12px;
        display: inline-block;
        text-align: center;
        z-index: 1;
        background-color: white;
    }

</style>
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
                <div class="position-absolute  top-0 left-0 m-2" style="z-index: 3;">
                    <div class="badge text-white px-3 pb-1 pt-2 rounded-0" style="background-color: <?php echo $itemData['label-color']; ?>;"><?php echo $itemData['label']; ?></div>
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
                    <div class="mw-shop-inner-skin-discount-label-2">
                        <span class="">
                                <?php echo number_format($percentChange); ?>%
                        </span>

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
