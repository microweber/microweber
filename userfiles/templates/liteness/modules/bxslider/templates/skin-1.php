<?php

/*

  type: layout

  name: Skin-1

  description: Default template for bxSlider


*/

?>
<section class="section-17">
    <div class="container-fluid p-0">
        <div class="slider-wrapper">
            <div class="home-slider bxSlider">
                <?php foreach ($data as $slide) { ?>
                    <div class="slide">
                        <?php if (isset($slide['skin_file'])) {
                            include($slide['skin_file']);
                        }
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>