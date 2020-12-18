<?php

/*

  type: layout

  name: Background overlay

  description: Background overlay template for bxSlider


*/

?>
<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>/templates/background-overlay/style.css"/>

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
