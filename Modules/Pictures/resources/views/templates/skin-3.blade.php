<?php

/*

type: layout

name: Skin-3

description: Skin-3

*/

?>

<script>mw.lib.require('slick');</script>
<script>
    /* ###################### Slick   ###################### */
    (function (){
        var galleryImages = [];
        $(document).ready(function () {
            if ($('<?php print '#' . $params['id']; ?> .slick-gallery').length > 0) {
                $('<?php print '#' . $params['id']; ?> .slick-gallery').each(function () {

                    Array.from(this.querySelectorAll('.slick-slide-item-x')).forEach(function (node, index){
                        var img = node.querySelector('img')
                        galleryImages.push({
                            url: img.dataset.largeImage || img.src
                        })
                        node.addEventListener('click', function (){
                            mw.gallery(galleryImages, index)
                        })
                    })

                    var el = $(this);
                    el.slick({
                        rtl: document.documentElement.dir === 'rtl',
                        centerMode: false,
                        centerPadding: '0px',
                        slidesToShow: 4,
                        arrows: true,
                        autoplay: false,
                        autoplaySpeed: 2000,
                        dots: true,
                        responsive: [
                            {
                                breakpoint: 1200,
                                settings: {
                                    arrows: false,
                                    centerMode: true,
                                    centerPadding: '0px',
                                    slidesToShow: 3
                                }
                            }, {
                                breakpoint: 768,
                                settings: {
                                    arrows: false,
                                    centerMode: true,
                                    centerPadding: '0px',
                                    slidesToShow: 2
                                }
                            }, {
                                breakpoint: 480,
                                settings: {
                                    arrows: false,
                                    centerMode: true,
                                    centerPadding: '0px',
                                    slidesToShow: 1
                                }
                            }
                        ]
                    });

                });
            }
        });
    })()
</script>

<style>
    <?php print '#' . $params['id']; ?> .slick-track {
        display: flex;
        gap: 0.6rem;
    }
</style>

<?php if (is_array($data)): ?>
    <?php $rand = uniqid(); ?>

    <?php
    $click_image_event = 'fullscreen';
    $get_click_image_event = get_option('click_image_event', $params['id']);
    if ($get_click_image_event != false) {
        $click_image_event = $get_click_image_event;
    }
    ?>

    <div class="slick-arrows-1">
        <div class="slick-gallery">
            <?php foreach ($data as $item): ?>

                <?php
                $itemTitle = false;
                $itemDescription = false;
                $itemLink = false;
                $itemAltText = 'Open';
                if (isset($item['image_options']) and is_array($item['image_options'])) {
                    if (isset($item['image_options']['title'])) {
                        $itemTitle = $item['image_options']['title'];
                    }
                    if (isset($item['image_options']['caption'])) {
                        $itemDescription = $item['image_options']['caption'];
                    }
                    if (isset($item['image_options']['link'])) {
                        $itemLink = $item['image_options']['link'];
                    }
                    if (isset($item['image_options']['alt-text'])) {
                        $itemAltText = $item['image_options']['alt-text'];
                    }
                }
                ?>

                <div class="d-block position-relative">

                    <?php if ($itemLink): ?>
                    <a <?php if ($click_image_event == 'link_target_blank'): ?> target="_blank" <?php endif; ?>
                        href="<?php print $itemLink; ?>">
                    <?php endif; ?>

                    <div class="img-as-background mh-350 mb-3">
                        <img   src="<?php print thumbnail($item['filename'], 350, 350, true); ?>"/>
                    </div>

                     <?php if ($itemLink): ?>
                        </a>
                    <?php endif; ?>


                    <?php if ($itemTitle): ?>
                        <div class="bg-body-opacity-5 w-100 px-3 py-1 text-center" style="z-index: 9;">
                            <h6 class="m-0"><?php print $itemTitle; ?></h6>
                        </div>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>
        </div>
    </div>

<?php endif; ?>
