<?php

/*

  type: layout

  name: Slick Slider - Skin 3

  description: Slick Slider - Skin 3


*/

?>
<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>/templates/slickslider-skin-3/style.css"/>

<div class="slickSlider template-default">
    <?php foreach ($data as $slide) { ?>
        <?php if (isset($slide['skin_file'])) { ?>
            <?php include $slide['skin_file'] ?>
        <?php } ?>
    <?php } ?>
</div>