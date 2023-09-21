<?php

/*

type: layout

name: Default

description: Default

*/
?>

<div id="js-slider-<?php echo $params['id']; ?>" class="swiper">
    <div class="swiper-wrapper">

        <?php foreach($slides as $i => $slide): ?>

        <?php print getCssForSlide($slide); ?>

        <div class="swiper-slide">

            <div class="js-slide-image-<?php echo $slide['itemId']; ?>"
                 style="
                     background-image: url('<?php echo $slide['image'];?>');
                 ">
            </div>

            <div style="height:600px" class="d-flex flex-column justify-content-center align-items-center">
               <div>
                   <h3 data-option-value="settings.<?php echo $i; ?>.title" class="js-slide-title-<?php echo $slide['itemId']; ?>" style="font-size:32px">
                       <?php echo $slide['title'];?>
                   </h3>
               </div>
                <div>
                    <p data-option-value="settings.<?php echo $i; ?>.description" class="js-slide-description-<?php echo $slide['itemId']; ?>" style="font-size:20px">
                        <?php echo $slide['description'];?>
                    </p>
                </div>
            </div>

        </div>
        <?php endforeach; ?>
    </div>

    <div id="js-slide-pagination-<?php echo $params['id']; ?>" class="swiper-pagination"></div>
    <div id="js-slide-pagination-previous-<?php echo $params['id']; ?>" class="swiper-button-prev"></div>
    <div id="js-slide-pagination-next-<?php echo $params['id']; ?>" class="swiper-button-next"></div>
</div>
