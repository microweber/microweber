<?php

/*

type: layout

name: skin 1

description: skin 1

*/
?>

<div id="js-slider-<?php echo $params['id']; ?>" class="slider_v2-default swiper">
    <div class="swiper-wrapper">

        <?php foreach($slides as $i => $slide): ?>

            <?php print getCssForSlide($slide); ?>

            <div class="swiper-slide">

                <div class="js-slide-image-<?php echo $slide['itemId']; ?>"
                     style="
                         background-image: url('<?php echo $slide['image'];?>');
                         ">
                </div>

                <div style="height: calc(100vh - 100px);" class="d-flex flex-column justify-content-center align-items-center">
                    <div>
                        <h3 class="js-slide-title-<?php echo $slide['itemId']; ?>">
                            <?php echo $slide['title'];?>
                        </h3>
                    </div>
                    <div>
                        <p class="js-slide-description-<?php echo $slide['itemId']; ?>">
                            <?php echo $slide['description'];?>
                        </p>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

    <div id="js-slide-pagination-<?php echo $params['id']; ?>" class="swiper-pagination"></div>
    <div id="js-slide-pagination-previous-<?php echo $params['id']; ?>" class="mw-slider-v2-buttons-slide mw-slider-v2-button-prev"></div>
    <div id="js-slide-pagination-next-<?php echo $params['id']; ?>" class="mw-slider-v2-buttons-slide mw-slider-v2-button-next"></div>
</div>
