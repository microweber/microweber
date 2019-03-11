<?php

/*

  type: layout

  name: Slick Slider - Skin 2

  description: Slick Slider - Skin 2


*/

?>
<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>/templates/slickslider-skin-2/style.css"/>

<div class="slickslideWrapper template-second">
    <ul class="slickSlider">
        <?php foreach ($data as $slide) { ?>
            <div>
                <?php if (isset($slide['skin_file'])) { ?>
                    <?php include $slide['skin_file'] ?>
                <?php } ?>
            </div>
        <?php } ?>
    </ul>
</div>
