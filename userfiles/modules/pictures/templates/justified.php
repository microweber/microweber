<?php

/*

type: layout

name: Justified Slick

description: Justified Slick Pictures Template

*/

?>
<?php if (is_array($data)): ?>


    <script>
        mw.lib.require('slick');
    </script>

    <link rel="stylesheet" href="<?php print $config['url_to_module']; ?>/plugins/justified/jquery.justified.images.css">
    <script src="<?php print $config['url_to_module']; ?>/plugins/justified/jquery.justified.images.js"></script>
    <script>
        $('.image-container').empty().justifiedImages({
            images: photos,
            rowHeight: 200,
            maxRowHeight: 400,
            thumbnailPath: function (photo, width, height) {
                var purl = photo.url_s;
                if (photo.url_n && (width > photo.width_s * 1.2 || height > photo.height_s * 1.2)) purl = photo.url_n;
                if (photo.url_m && (width > photo.width_n * 1.2 || height > photo.height_n * 1.2)) purl = photo.url_m;
                if (photo.url_z && (width > photo.width_m * 1.2 || height > photo.height_m * 1.2)) purl = photo.url_z;
                if (photo.url_l && (width > photo.width_z * 1.2 || height > photo.height_z * 1.2)) purl = photo.url_l;
                return purl;
            },
            getSize: function (photo) {
                return {width: photo.width_s, height: photo.height_s};
            },
            margin: 1
        });
    </script>
    <script>mw.moduleCSS("<?php print $config['url_to_module']; ?>css/slick.css");</script>

    <script type="text/javascript">
        $(document).ready(function () {

            if ($('.slickSlider', '#<?php print $params['id'] ?>').hasClass('slick-initialized')) {
                console.log('initialized');
            } else {
                console.log('not initialized');
            }

//d($('.slickSlider', '#<?php print $params['id'] ?>').slick('unslick'))
            //  d($('.slickSlider', '#<?php print $params['id'] ?>').slick('getSlick').slick('getSlick'));

            $('.slickSlider', '#<?php print $params['id'] ?>').slick({
                dots: false,
                arrows: false,
                infinite: false,
                speed: 200,
                slidesToShow: 6,
                slidesToScroll: 6,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 5,
                            slidesToScroll: 5,
                        }
                    },
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 585,
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
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]
            });


//            $('.slickNext', '#<?php //print $params['id'] ?>//').on('click', function () {
//                $('.slickSlider', '#<?php //print $params['id'] ?>//').slick('slickNext');
//            });
//
//            $('.slickPrev', '#<?php //print $params['id'] ?>//').on('click', function () {
//                $('.slickSlider', '#<?php //print $params['id'] ?>//').slick('slickPrev');
//            });

        });
    </script>

    <?php if (!$no_img): ?>
        <div class="mw-module-images">
            <div class="slickSlider">
                <?php $count = -1;
                foreach ($data as $item): ?>
                    <?php $count++; ?>
                    <div class="slick-pictures-item slick-pictures-item-<?php print $item['id']; ?>">
                        <div class="thumbnail-wrapper">
                            <div class="thumbnail">
                                <img src="<?php print thumbnail($item['filename'], 300); ?>"/>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
<?php else : ?>
<?php endif; ?>