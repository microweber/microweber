<?php

/*

type: layout
name: Slider
description slider

*/
?>

<script>
    mw.require('<?php print modules_url() ?>slider_v2/slider-v2.js');
    $(document).ready(function () {
        new SliderV2('#js-teamcard-slider-<?php echo $params['id']; ?>', {
            loop: true,
            autoplay:true,
            direction: 'vertical', //horizontal or vertical
            pagination: {
                element: '#js-teamcard-slider-pagination-<?php echo $params['id']; ?>',
            },
            navigation: {},
        });
    });
</script>

<style>
    #js-teamcard-slider-<?php echo $params['id']; ?>{
        max-height: 500px;
        background-color: #f5f5f5;
    }
</style>

<div id="js-teamcard-slider-<?php echo $params['id']; ?>" class="slider_v2-default swiper">
    <div class="swiper-wrapper">
        <?php foreach($data as $i => $teamData): ?>
            <div class="swiper-slide">
                <div class="mb-3 overflow-hidden text-start px-md-4 my-5 d-flex flex-wrap">
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
</div>
