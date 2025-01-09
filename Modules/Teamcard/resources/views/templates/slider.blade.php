<?php

/*

type: layout
name: Slider
description slider

*/
?>

<style>
    .slider_v2-default.swiper .swiper-pagination-bullet {
        background-color: #000;
    }
</style>

<script>
    mw.require('<?php print modules_url() ?>slider_v2/slider-v2.js');
    $(document).ready(function () {
        new SliderV2('#js-teamcard-slider-<?php echo $params['id']; ?>', {
            loop: true,
            autoplay:true,
            direction: 'vertical',
            pagination: {
                element: '#js-teamcard-slider-pagination-<?php echo $params['id']; ?>',
            },
            navigation: {},
        });
    });
</script>

<style>
    #js-teamcard-slider-<?php echo $params['id']; ?>{
       max-height: 550px;
    }
</style>

<div id="js-teamcard-slider-<?php echo $params['id']; ?>" class="slider_v2-default swiper">
    <div class="swiper-wrapper">
        <?php foreach($data as $i => $teamData): ?>
            <div class="swiper-slide">
                <div class="mb-3 overflow-hidden text-start my-5 d-flex flex-wrap">
                    <div class="col-md-5">
                        <div class="w-450">
                            <?php if ($teamData['file']) { ?>
                                <div class="img-as-background square">
                                    <img loading="lazy" style="object-fit: contain !important;" src="<?php print thumbnail($teamData['file'], 850); ?>"/>
                                </div>
                            <?php } else { ?>
                                <div class="img-as-background square">
                                    <img loading="lazy" style="object-fit: contain !important;" src="<?php print asset('templates/big2/modules/teamcard/templates/default-image.svg'); ?>"/>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-md-6 my-auto">


                        <h1 class="mb-1"><?php print array_get($teamData, 'name'); ?></h1>
                        <p class="mb-3"><?php print array_get($teamData, 'role'); ?></p>
                        <p><?php print array_get($teamData, 'bio'); ?></p>
                        <a href="<?php print $teamData['website']; ?>" target="_blank"> <?php print array_get($teamData, 'website'); ?></a>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="js-teamcard-slider-pagination-<?php echo $params['id']; ?>" class="swiper-pagination"></div>
</div>
