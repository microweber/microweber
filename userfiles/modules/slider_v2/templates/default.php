<div class="swiper">

    <div class="swiper-wrapper">
        <?php foreach($slides as $slide): ?>
        <div class="swiper-slide"
             style="
                background: url('<?php echo $slide['image'];?>');
                 background-size:cover;
            ">

            <div style="height:600px" class="d-flex flex-column justify-content-center align-items-center">
               <div>
                   <h3 style="font-size:24px">
                       <?php echo $slide['primaryText'];?>
                   </h3>
               </div>
                <div>
                    <p style="font-size:24px">
                        <?php echo $slide['secondaryText'];?>
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
