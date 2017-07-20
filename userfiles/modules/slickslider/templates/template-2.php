<?php

/*

  type: layout

  name: Template 2

  description: Template 2


*/

?>
<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>/templates/template-2/style.css"/>

<div class="slickslideWrapper template-second">
    <ul class="slickslider">
        <?php foreach ($data as $slide) { ?>
            <div>
                <?php if (isset($slide['skin_file'])) { ?>
                    <?php include $slide['skin_file'] ?>

                <?php } ?>
            </div>
        <?php } ?>
    </ul>
</div>
