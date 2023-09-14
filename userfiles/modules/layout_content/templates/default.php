<?php

/*

type: layout
name: default
description: default

*/

?>

<div class="layout-content-holder">

    <?php if (isset($title)): ?>
    <div class="mt-6">
        <div class="text-<?php echo $align; ?>">
            <h2><?php echo $title; ?></h2>
            <p><?php echo $description; ?></p>
        </div>
    </div>
    <?php endif; ?>

    <div class="row mt-4">
    <?php
    $count = 0;

    if (!empty($contents)) {
        foreach ($contents as $content) {
            $count++;
            $colMdCalculate = 12 / $maxColumns;

            $contentTitle = isset($content['title']) ? $content['title'] : '';
            $contentDescription = isset($content['description']) ? $content['description'] : '';
            $contentImage = isset($content['image']) ? $content['image'] : '';
            $contentButtonLink = isset($content['buttonLink']) ? $content['buttonLink'] : '';
            $contentButtonText = isset($content['buttonText']) ? $content['buttonText'] : '';
            ?>
            <div class="col-md-<?php echo $colMdCalculate; ?> text-<?php echo $align; ?>">
                <?php if ($contentImage) { ?>
                    <img src="<?php echo thumbnail($contentImage, 600, 400, true); ?>" />
                <?php } else { ?>
                    <img src="<?php echo modules_url() ?>layout_content/templates/default-image.png" />
                <?php } ?>
                <div class="mt-2">
                    <h4><?php echo $contentTitle; ?></h4>
                    <p><?php echo $contentDescription; ?></p>
                </div>
                <?php if (!empty($contentButtonText)) : ?>
                <a href="<?php echo $contentButtonLink; ?>" class="btn btn-primary" target="_blank">
                    <?php echo $contentButtonText; ?>
                </a>
                <?php endif; ?>
            </div>
        <?php }
    } ?>
    </div>

    <?php if (isset($buttonText) && !empty($buttonText)): ?>
    <div class="text-<?php echo $align; ?> mt-6">
        <a href="<?php echo $buttonLink; ?>" class="btn btn-primary w-25" target="_blank">
            <?php echo $buttonText; ?>
        </a>
    </div>
    <?php endif; ?>

</div>
