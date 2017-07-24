<?php

/*

  type: layout

  name: Default

  description: Default template for slick slider


*/

?>
<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>/templates/default/style.css"/>

<div class="slickslider template-default">
    <?php foreach ($data as $slide) { ?>
        <?php if (isset($slide['skin_file'])) { ?>
            <?php include $slide['skin_file'] ?>
        <?php } ?>
    <?php } ?>
</div>