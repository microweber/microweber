<?php

/*

  type: layout

  name: Default

  description: Default


*/

?>
<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>/templates/bxslider-skin-1/style.css"/>

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