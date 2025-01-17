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
    $(document).ready(function () {
        if ($('<?php print '#' . $params['id']; ?> .slick-gallery').length > 0) {
            $('<?php print '#' . $params['id']; ?> .slick-gallery').each(function () {
                var el = $(this);
                el.slick({
                    rtl: document.documentElement.dir === 'rtl',
                    centerMode: false,
                    centerPadding: '0px',
                    slidesToShow: 3,
                    arrows: true,
                    autoplay: false,
                    autoplaySpeed: 2000,
                    dots: true,
                    responsive: [
                        {
                            breakpoint: 1200,
                            settings: {
                                arrows: true,
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

</script>

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
        <div class="slick-gallery" style="margin: 0 -15px;">
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
                <div class="px-3">
                    <div class="d-block position-relative">
                        <?php if ($itemTitle): ?>
                            <div class="position-absolute bg-body-opacity-5 w-100 px-3 py-2 bottom-0 text-center" style="z-index: 9;">
                                <h6 class="m-0"><?php print $itemTitle; ?></h6>
                            </div>
                        <?php endif; ?>

                        <div class="img-as-background   mh-350 mb-3">
                            <img   src="<?php print thumbnail($item['filename'], 350, 350, true); ?>"/>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

<?php endif; ?>
