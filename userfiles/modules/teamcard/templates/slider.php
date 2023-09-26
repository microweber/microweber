<?php

/*

type: layout

name: Slider

description: slider

*/
?>

<style>
    .swiper {
        width: 100%;
        height: 100%;
    }

    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<script>
    mw.require('<?php print userfiles_url() ?>slider_v2/slider-v2.js');
    $(document).ready(function () {
        new SliderV2('#js-teamcard-slider-<?php echo $params['id']; ?>', {
            loop: true,
            direction: 'vertical',
            //pagination: {
            //    element: '#js-teamcard-slider-pagination-<?php //echo $params['id']; ?>//',
            //},
            //navigation: {
            //    nextElement: '#js-teamcard-slider-pagination-next-<?php //echo $params['id']; ?>//',
            //    previousElement: '#js-teamcard-slider-pagination-previous-<?php //echo $params['id']; ?>//',
            //},

            navigation: {},
            pagination: {
               element: '.swiper-pagination',
            },
        });
    });
</script>


<!-- Swiper -->
<div class="swiper" id="js-teamcard-slider-<?php echo $params['id']; ?>">
    <div class="swiper-wrapper">
        <div class="swiper-slide">Slide 1</div>
        <div class="swiper-slide">Slide 2</div>
        <div class="swiper-slide">Slide 3</div>
        <div class="swiper-slide">Slide 4</div>
        <div class="swiper-slide">Slide 5</div>
        <div class="swiper-slide">Slide 6</div>
        <div class="swiper-slide">Slide 7</div>
        <div class="swiper-slide">Slide 8</div>
        <div class="swiper-slide">Slide 9</div>
    </div>
    <div class="swiper-pagination"></div>
</div>


<!--

<div id="js-teamcard-slider-<?php echo $params['id']; ?>" class="slider_v2-default swiper">
    <div class="swiper-wrapper">
        <?php foreach($data as $i => $teamData): ?>
            <div class="swiper-slide">
                <div style="height:150px;background:red" class="mb-3 overflow-hidden text-start px-md-4 my-5 d-flex flex-wrap">
                    <div class="col-md-6">
                        <?php if ($teamData['file']) { ?>
                            <div class="m-auto rounded-circle" style="width:150px;height:150px;background-image: url('<?php print thumbnail($teamData['file'], 200); ?>');"></div>

                        <?php } else { ?>
                            <div class="m-auto rounded-circle">

                                <img  width="185" height="185" src="<?php print modules_url() ?>teamcard/templates/default-image.svg"/>
                            </div>

                        <?php } ?>
                    </div>

                    <div class="col-md-6">
                        <div class="py-4 fs-4" ><?php print array_get($teamData, 'name'); ?></div>
                        <div class="pb-3"> <?php print array_get($teamData, 'role'); ?></div>
                        <a href="<?php print $teamData['website']; ?>" target="_blank"> <?php print array_get($teamData, 'website'); ?></a>
                        <div class="pt-3 italic"> <?php print array_get($teamData, 'bio'); ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="js-teamcard-slider-pagination-<?php echo $params['id']; ?>" class="swiper-pagination"></div>
    <div id="js-teamcard-slider-pagination-previous-<?php echo $params['id']; ?>" class="mw-slider-v2-buttons-slide mw-slider-v2-button-prev"></div>
    <div id="js-teamcard-slider-pagination-next-<?php echo $params['id']; ?>" class="mw-slider-v2-buttons-slide mw-slider-v2-button-next"></div>
</div>
-->
