<?php

/*

  type: layout

  name: Slick Slider - Skin 2

  description: Slick Slider - Skin 2


*/

?>
<?php $skin_css_file =  __DIR__.'/slickslider-skin-2/style.css' ?>
<?php if(is_file($skin_css_file)){ ?>
    <style>
        <?php print  @file_get_contents($skin_css_file) ?>
    </style>
<?php } ?>
<div class="slickSlider-skin-2">
    <div class="slickSlider">
        <?php foreach ($data as $slide) { ?>
            <?php if (isset($slide['skin_file'])) { ?>
                <?php include $slide['skin_file'] ?>
            <?php } ?>
        <?php } ?>
    </div>
</div>