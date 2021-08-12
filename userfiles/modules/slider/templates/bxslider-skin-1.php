<?php

/*

  type: layout

  name: Default

  description: Default


*/

?>

<?php $skin_css_file =  __DIR__.'/bxslider-skin-1/style.css' ?>
<?php if(is_file($skin_css_file)){ ?>
    <style>
        <?php print  @file_get_contents($skin_css_file) ?>
    </style>
<?php } ?>
<?php
$thumb_quality_y = '1920';
$thumb_quality_y = $thumb_quality_y / $slides_xl;

?>

<div class="bxSlider-default">
    <div class="bxSlider">
        <?php foreach ($data as $slide) { ?>
            <?php if (isset($slide['skin_file'])) { ?>
                <?php include $slide['skin_file'] ?>
            <?php } ?>
        <?php } ?>
    </div>
</div>
