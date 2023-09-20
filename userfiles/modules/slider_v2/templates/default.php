<div class="swiper">

    <div class="swiper-wrapper">
        <?php foreach($slides as $slide): ?>

        <?php print getCssForSlide($slide); ?>


        <div class="swiper-slide">

            <div class="js-slide-image-<?php echo $slide['itemId']; ?>"
                 style="
                     background-image: url('<?php echo $slide['image'];?>');
                 ">
            </div>

            <div style="height:600px" class="d-flex flex-column justify-content-center align-items-center">
               <div>
                   <h3 class="js-slide-title-<?php echo $slide['itemId']; ?>" style="font-size:32px">
                       <?php echo $slide['title'];?>
                   </h3>
               </div>
                <div>
                    <p class="js-slide-description-<?php echo $slide['itemId']; ?>" style="font-size:20px">
                        <?php echo $slide['description'];?>
                    </p>
                </div>
            </div>

        </div>
        <?php endforeach; ?>
    </div>

    <!-- If we need pagination -->
    <div class="swiper-pagination"></div>

    <!-- If we need navigation buttons -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
