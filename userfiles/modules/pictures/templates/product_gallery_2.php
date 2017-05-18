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
        mw.require('https://fonts.googleapis.com/icon?family=Material+Icons&.css', 'material_icons');
    </script>

    <script>
        $(document).ready(function () {
            $('.bxslider', '#<?php print $id ?>').bxSlider({
                adaptiveHeight: false,
                controls: true,
                pager: true,
                nextText: '<i class="material-icons">arrow_forward</i>',
                prevText: '<i class="material-icons">arrow_backward</i>'
            });
        });

        gallery<?php print $rand; ?> = [
                <?php foreach($data  as $item): ?>{image: "<?php print ($item['filename']); ?>", description: "<?php print $item['title']; ?>"},
            <?php endforeach;  ?>
        ];
    </script>

    <style>
        .product-gallery-layout-2 .bx-wrapper {
            margin-bottom: 10px;
            -moz-box-shadow: none;
            -webkit-box-shadow: none;
            box-shadow: none;
            border: 0;
        }

        .product-gallery-layout-2 .bxslider li {
            height: 550px;
            outline: none;
        }

        .product-gallery-layout-2 .bxslider li img {
            max-height: 550px;
            margin: 0 auto;
            outline: none;
        }

        .product-gallery-layout-2 .valign {
            display: table;
            height: 100%;
            width: 100%;
            outline: none;
        }

        .product-gallery-layout-2 .valign-cell {
            display: table-cell;
            vertical-align: middle;
            outline: none;
        }

        .product-gallery-layout-2 .bx-pager {
            padding: 20px 0 40px 0;
            position: relative;
        }

        .product-gallery-layout-2 .bx-wrapper .bx-pager.bx-default-pager a {
            background: #bababa;
            width: 15px;
            height: 15px;
            -moz-border-radius: 20px;
            -webkit-border-radius: 20px;
            border-radius: 20px;
            margin: 0 7px;
        }

        .product-gallery-layout-2 .bx-wrapper .bx-pager.bx-default-pager a.active {
            background: #000;
        }

        .product-gallery-layout-2 .bx-wrapper .bx-controls-direction a {
            background: none;
            color: #000;
            text-indent: 0;
            background: #fff;
            border: 1px solid #000;
            border-radius: 32px;
            padding: 3px 4px;
            z-index: 1;
        }

        .product-gallery-layout-2 .bx-wrapper .bx-controls-direction a i{
            color: #000;
        }

        @media screen and (max-width: 480px) {
            .product-gallery-layout-2 .bxslider li {
                height: 450px;
            }

            .product-gallery-layout-2 .bxslider li img {
                max-height: 450px;
            }
        }
    </style>

    <div class="product-gallery-layout-2">
        <div class="bootstrap3ns">
            <div class="gallery-content" id="<?php print $id; ?>">
                <div class="col-xs-12 col-lg-12 big-thumb">
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
            </div>
        </div>
    </div>
<?php endif; ?>