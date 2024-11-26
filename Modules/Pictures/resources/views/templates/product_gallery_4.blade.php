<?php

/*

type: layout

name: Product Gallery 4

description: Product Gallery

*/

?>


<?php if (is_array($data) and $data): ?>
    <?php $id = "slider-" . uniqid(); ?>
    <?php $rand = uniqid(); ?>

    <script>
        mw.lib.require('bxslider');
        mw.lib.require('slick');
        mw.lib.require('bootstrap3ns');
        mw.lib.require('material_icons');
    </script>

    <script>
        $(document).ready(function () {
            const bx = $('.bxslider', '#<?php print $id ?>').bxSlider({
                pagerCustom: '#bx-pager',
                adaptiveHeight: false,
                controls: false,
                pager: true,
                preventDefaultSwipeY: false,
            });

            $('.slick', '#<?php print $id ?>').slick({
                rtl: document.documentElement.dir === 'rtl',
                slidesToShow: 5,
                slidesToScroll: 1,
                vertical: false,
                nextArrow: '<i class="material-icons desktop">arrow_forward</i>',
                prevArrow: '<i class="material-icons desktop">arrow_backward</i>',
                adaptiveHeight: true,
                infinite: false,
                responsive: [
                    {
                        breakpoint: 1199,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4
                        }
                    },
                    {
                        breakpoint: 997,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            })
            .on('click', '[data-slick-index]', function(){
                   bx.goToSlide(this.dataset.slickIndex);
            });
        });

        gallery<?php print $rand; ?> = [
                <?php foreach($data  as $item): ?>{image: "<?php print ($item['filename']); ?>", description: "<?php print $item['title']; ?>"},
            <?php endforeach;  ?>
        ];
    </script>

    <style>

    #<?php print $id; ?> .bxslider .image{
        height: min(calc(100vh - 200px), 900px);
        display:block;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }
    #<?php print $id; ?> .bx-pager .slick .slick-arrow:first-child{left:0}
    #<?php print $id; ?> .bx-pager .slick .slick-arrow:last-child{right:0}
    #<?php print $id; ?> .bx-pager .slick-arrow.slick-disabled{
        opacity: .5
    }
    #<?php print $id; ?> .bx-pager .slick-arrow{
        position:absolute;
        top: calc(50% - 17.5px);
        width: 35px;
        height: 35px;
        background-color: rgba(0,0,0,.5);
        color: #fff;
        text-align: center;
        align-content: center;
        z-index: 2;
        cursor: pointer;
    }
    #<?php print $id; ?> .bx-pager .image{
        height: 60px;
        width: 60px;
        display: block;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }
    </style>


    <div class="product-gallery-layout-4">
        <div class="bootstrap3ns">
            <div class="gallery-content" id="<?php print $id; ?>">

                <div class="col-xs-12 big-thumb">
                    <ul class="bxslider">
                        <?php foreach ($data as $key => $item): ?>
                            <li class="mw-gallery-item-<?php print $item['id']; ?>">
                                <a href="javascript:;" onclick="mw.gallery(gallery<?php print $rand; ?>, <?php print $key; ?>)" class="image"
                                   style="background-image: url('<?php print thumbnail($item['filename'], 900); ?>');"></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="col-xs-12 thumbs">
                    <div class="bx-pager">
                        <div class="slick">
                            <?php foreach ($data as $key => $item): ?>
                                <div class="item">
                                    <a class="image" data-slide-index="<?php print $key; ?>" href="javascript:;"
                                       style="background-image: url('<?php print thumbnail($item['filename'], 150); ?>');"></a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="clearfix"></div>
    </div>
<?php else : ?>
@include('modules.pictures::partials.no-pictures')
<?php endif; ?>
