<?php

/*

  type: layout

  name: bxSlider - Default

  description: bxSlider - Default


*/

?>
<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>/templates/bxslider-skin-1/style.css"/>


<div class="bxSlider-default">
    <div class="bxSlider">
        <?php foreach ($data as $slide) { ?>
            <?php if (isset($slide['skin_file'])) { ?>
                <?php include $slide['skin_file'] ?>
            <?php } ?>
        <?php } ?>
    </div>
</div>