<?php

/*

  type: layout

  name: Slider

  description: Slider template for bxSlider


*/

?>

<section class="slider slider--animate height-100 cover cover-5 parallax" data-arrows="true" data-paging="true" data-timing="5000">
    <ul class="slides">
        <?php foreach ($data as $slide) { ?>
            <li class="imagebg" data-overlay="4">
                <?php if (isset($slide['skin_file'])) { ?>
                    <?php include $slide['skin_file'] ?>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
</section>



