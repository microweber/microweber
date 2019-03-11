<?php

/*

  type: layout

  name: bxSlider - Background overlay

  description: bxSlider - Background overlay


*/

?>
<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>/templates/bxslider-skin-3/style.css"/>

<div class="bxSliderWrapper template-default">
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
