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
        foreach ($data as $content) {
            $count++;
            ?>
            <div class="col-md-4">
                <?php if ($content['image']) { ?>
                    <img src="<?php echo thumbnail($content['image'], 600, 400, true); ?>" />
                <?php } else { ?>
                    <img src="<?php echo modules_url() ?>layout_content/templates/default-image.png" />
                <?php } ?>
                <div class="mt-2">
                    <h4>
                        <?php echo $content['title']; ?>
                    </h4>
                    <p>
                        <?php echo $content['description']; ?>
                    </p>
                </div>
                <a href="<?php echo $content['button_link']; ?>" class="btn btn-primary" target="_blank">
                    <?php echo $content['button_text']; ?>
                </a>
            </div>
        <?php }
    } ?>
</div>
