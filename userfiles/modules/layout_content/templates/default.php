<?php

/*

type: layout
name: default
description: default

*/

?>

<div class="layout-content-holder">

    <div class="mt-6">
        <div class="text-center">
            <h2><?php echo $title; ?></h2>
            <p><?php echo $description; ?></p>
        </div>
    </div>

    <div class="row mt-8">
    <?php
    $count = 0;

    if (!empty($contents)) {
        foreach ($contents as $content) {
            $count++;
            ?>
            <div class="col-md-4 text-center">
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

    <div class="d-flex justify-content-center align-items-center mt-6">
        <a href="" class="btn btn-primary w-25" target="_blank">
            See all
        </a>
    </div>

</div>
