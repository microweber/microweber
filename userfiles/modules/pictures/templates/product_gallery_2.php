<?php

/*

type: layout

name: Product Gallery 2

description: Product Gallery

*/

?>


<?php if (is_array($data)): ?>
    <?php $id = "slider-" . uniqid(); ?>
    <?php $rand = uniqid(); ?>

    <script>
        mw.lib.require('bxslider');
        mw.lib.require('bootstrap3ns');
    </script>

    <script>
        $(document).ready(function () {
            $('.bxslider', '#<?php print $id ?>').bxSlider({
                adaptiveHeight: false,
                controls: true,
                pager: true,
                nextText: '<i class="material-icons">arrow_forward</i>',
                prevText: '<i class="material-icons">arrow_backward</i>',
                preventDefaultSwipeY: false,
            });
        });

        gallery<?php print $rand; ?> = [
                <?php foreach($data  as $item): ?>{image: "<?php print ($item['filename']); ?>", description: "<?php print $item['title']; ?>"},
            <?php endforeach;  ?>
        ];
    </script>

    <div class="product-gallery-layout-2">
        <div class="bootstrap3ns">
            <div class="gallery-content" id="<?php print $id; ?>">
                <div class="col-xs-12 col-lg-12 big-thumb">
                    <div class="bxslider">
                        <?php foreach ($data as $key => $item): ?>
                            <div class="item mw-gallery-item-<?php print $item['id']; ?>">
                                <a href="javascript:;" onclick="mw.gallery(gallery<?php print $rand; ?>, <?php print $key; ?>)" class="valign">
                                    <div class="valign-cell">
                                        <img class="mw-slider-zoomimg-base" src="<?php print thumbnail($item['filename'], 700); ?>" alt="" title="<?php print $item['title']; ?>"/>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
