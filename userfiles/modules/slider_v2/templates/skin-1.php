<?php

/*

type: layout

name: skin 1

description: skin 1

*/
?>

<style>
    .header-section-title, .header-section-title span {

        @media screen and (max-width: 991px) {
            font-size: 3rem;
        }

        @media screen and (max-width: 600px) {
            font-size: 2.5rem!important;
        }

        @media screen and (max-width: 400px) {
            font-size: 2rem!important;

        }

        overflow-wrap: break-word;
    }
</style>

<div id="js-slider-<?php echo $params['id']; ?>" class="slider_v2-default swiper" >
    <div class="swiper-wrapper">

        <?php if($slides): ?>

        <?php foreach($slides as $i => $slide): ?>

            <?php print getCssForSlide($slide); ?>

            <div class="swiper-slide">

                <div class="js-slide-image-<?php echo $slide['itemId']; ?>"
                     style="
                         background-image: url('<?php echo $slide['image'];?>');
                         border-radius: 30px;">
                </div>

                <div style="height: 650px; border-radius: 30px; padding-inline-start: 100px;" class="d-flex flex-column justify-content-center align-items-start text-start gap-4">

                    <h3 class="header-section-title js-slide-title-<?php echo $slide['itemId']; ?>">
                        <?php echo $slide['title'];?>
                    </h3>


                    <p class="header-section-p js-slide-description-<?php echo $slide['itemId']; ?>">
                        <?php echo $slide['description'];?>
                    </p>


                    <?php if(!empty($slide['buttonText']) && isset($slide['showButton']) && $slide['showButton'] == true): ?>
                        <button class="btn btn-primary js-slide-button-<?php echo $slide['itemId']; ?>">
                            <?php echo $slide['buttonText'];?>
                        </button>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <div id="js-slide-pagination-<?php echo $params['id']; ?>" class="swiper-pagination"></div>
<!--    <div id="js-slide-pagination-previous---><?php //echo $params['id']; ?><!--" class="mw-slider-v2-buttons-slide mw-slider-v2-button-prev"></div>-->
<!--    <div id="js-slide-pagination-next---><?php //echo $params['id']; ?><!--" class="mw-slider-v2-buttons-slide mw-slider-v2-button-next"></div>-->
</div>
