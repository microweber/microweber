<?php

/*

type: layout

name: Product Gallery 3

description: Product Gallery

*/

?>


<?php if (is_array($data)): ?>
    <?php $id = "slider-" . uniqid(); ?>
    <?php $rand = uniqid(); ?>

    <script>
        mw.lib.require('bxslider');
        mw.lib.require('slick');
        mw.lib.require('bootstrap3ns');
    </script>

    <script>
        $(document).ready(function () {
            $('.bxslider', '#<?php print $id ?>').bxSlider({
                pagerCustom: '#bx-pager',
                adaptiveHeight: false,
                controls: false,
                pager: true,
                preventDefaultSwipeY: false,
            });

            $('.slick', '#<?php print $id ?>').slick({
                rtl: document.documentElement.dir === 'rtl',
                slidesToShow: 4,
                slidesToScroll: 1,
                vertical: true,
                nextArrow: '<i class="material-icons desktop">arrow_downward</i>',
                prevArrow: '<i class="material-icons desktop">arrow_upward</i>',
                adaptiveHeight: true,
                infinite: false,
                responsive: [
                    {
                        breakpoint: 1199,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4,
                            vertical: false,
                            nextArrow: '<i class="material-icons mobile">arrow_forward</i>',
                            prevArrow: '<i class="material-icons mobile">arrow_backward</i>',
                        }
                    }
                ]
            });
        });

        gallery<?php print $rand; ?> = [
                <?php foreach($data  as $item): ?>{image: "<?php print ($item['filename']); ?>", description: "<?php print $item['title']; ?>"},
            <?php endforeach;  ?>
        ];
    </script>

    <div class="product-gallery-layout-3">
        <div class="bootstrap3ns">
            <div class="gallery-content" id="<?php print $id; ?>">

                <div class="col-xs-12 col-lg-9 big-thumb">
                    <ul class="bxslider">
                        <?php foreach ($data as $key => $item): ?>
                            <li class="mw-gallery-item-<?php print $item['id']; ?>">
                                <a href="javascript:;" onclick="mw.gallery(gallery<?php print $rand; ?>, <?php print $key; ?>)" class="valign">
                                    <div class="valign-cell">
                                        <img class="mw-slider-zoomimg-base" src="<?php print thumbnail($item['filename'], 700); ?>" alt="" title="<?php print $item['title']; ?>"/>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="col-xs-12 col-lg-3 thumbs">
                    <div id="bx-pager">
                        <div class="slick">
                            <?php foreach ($data as $key => $item): ?>
                                <div class="item">
                                    <a class="valign" data-slide-index="<?php print $key; ?>" href="javascript:;">
                                        <div class="valign-cell">
                                            <img src="<?php print thumbnail($item['filename'], 150); ?>"/>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>
