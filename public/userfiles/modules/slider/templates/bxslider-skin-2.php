<?php

/*

  type: layout

  name: bxSlider - Background overlay

  description: bxSlider - Background overlay


*/

?>
<?php $skin_css_file =  __DIR__.'/bxslider-skin-2/style.css' ?>
<?php if(is_file($skin_css_file)){ ?>
    <style>
        <?php print  @file_get_contents($skin_css_file) ?>
    </style>
<?php } ?>

<div class="bxSlider-skin-2">
    <ul class="bxSlider">
        <?php foreach ($data as $slide) { ?>
            <li>
                <?php if (isset($slide['skin_file'])) { ?>
                    <?php include $slide['skin_file'] ?>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
</div>
