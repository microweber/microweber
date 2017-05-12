<?php

/*

  type: layout

  name: Default

  description: Default template for bxSlider


*/

?>
<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>/templates/default/style.css"/>

<ul class="bxSlider template-default">
    <?php foreach ($data as $slide) { ?>
        <li>
            <?php if (isset($slide['skin_file'])) { ?>
                <?php include $slide['skin_file'] ?>

            <?php } ?>
        </li>
    <?php } ?>
</ul>

