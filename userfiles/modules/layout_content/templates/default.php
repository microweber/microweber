<?php

/*

type: layout
name: default
description: default

*/

?>

<div class="layout-content-holder row">
    <?php
    $count = 0;

    if (isset($data) AND $data) {
        foreach ($data as $slide) {
            $count++;
            ?>
            <div class="col-md-4">
                <?php if ($slide['image']) { ?>
                    <img src="<?php print thumbnail($slide['image'], 200); ?>" />
                <?php } else { ?>
                    <img src="<?php print modules_url() ?>layout_content/templates/default-image.png" />
                <?php } ?>


                <div class="py-4 fs-4" ><?php print array_get($slide, 'title'); ?></div>
                <br />
                <div class="pt-3 italic"> <?php print array_get($slide, 'description'); ?></div>
                <br />
                <a href="<?php print $slide['button_link']; ?>" class="btn btn-primary" target="_blank">
                    <?php print array_get($slide, 'button_text'); ?>
                </a>
            </div>
        <?php }
    } ?>
</div>
