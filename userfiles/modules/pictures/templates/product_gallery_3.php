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
        mw.require('https://fonts.googleapis.com/icon?family=Material+Icons&.css', 'material_icons');
    </script>

    <script>
        $(document).ready(function () {
            $('.bxslider', '#<?php print $id ?>').bxSlider({
                pagerCustom: '#bx-pager',
                adaptiveHeight: false,
                controls: false,
                pager: true
            });

            $('.slick', '#<?php print $id ?>').slick({
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

    <style>
        .product-gallery-layout-1 .bx-wrapper {
            margin-bottom: 10px;
            -moz-box-shadow: none;
            -webkit-box-shadow: none;
            box-shadow: none;
            border: 0;
        }

        .product-gallery-layout-1 .bxslider li {
            height: 550px;
            outline: none;
        }

        .product-gallery-layout-1 .bxslider li img {
            max-height: 550px;
            margin: 0 auto;
            outline: none;
        }

        .product-gallery-layout-1 .slick-slider .slick-slide {
            padding: 0 5px;
            height: 100px;
        }

        .product-gallery-layout-1 .slick-slider .slick-slide img {
            max-width: 100%;
            max-height: 100px;
            margin: 0 auto;
            padding: 5px 0px;
        }

        .product-gallery-layout-1 .slick-arrow {
            cursor: pointer;
            text-align: center;
            padding: 30px 0;
        }

        .product-gallery-layout-1 .slick-arrow.mobile {
            position: absolute;
            top: 0;
            padding: 42px 0;
        }

        .product-gallery-layout-1 .slick-arrow.mobile:first-child {
            left: 0;
        }

        .product-gallery-layout-1 .slick-arrow.mobile:last-child {
            right: 0;
        }

        .product-gallery-layout-1 .slick-arrow.desktop {
            display: block;
        }

        .product-gallery-layout-1 .valign {
            display: table;
            height: 100%;
            width: 100%;
            outline: none;
        }

        .product-gallery-layout-1 .valign-cell {
            display: table-cell;
            vertical-align: middle;
            outline: none;
        }

        @media screen and (max-width: 1199px) {
            .product-gallery-layout-1 .slick-slider {
                padding: 0 30px;
            }

            .product-gallery-layout-1 .gallery-content {
                display: flex;
                flex-direction: column;
            }

            .product-gallery-layout-1 .gallery-content .thumbs {
                order: 2;
            }

            .product-gallery-layout-1 .gallery-content .big-thumb {
                order: 1;
            }
        }

        @media screen and (max-width: 480px) {
            .product-gallery-layout-1 .bxslider li {
                height: 450px;
            }

            .product-gallery-layout-1 .bxslider li img {
                max-height: 450px;
            }
        }
    </style>

    <div class="product-gallery-layout-1">
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
                        <ul class="slick">
                            <?php foreach ($data as $key => $item): ?>
                                <li>
                                    <a class="valign" data-slide-index="<?php print $key; ?>" href="javascript:;">
                                        <div class="valign-cell">
                                            <img src="<?php print thumbnail($item['filename'], 150); ?>"/>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>