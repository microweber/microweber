<?php

/*

type: layout

name: Skin-1

description: Skin-1

*/

?>

<?php if (is_array($data)): ?>
    <?php $rand = uniqid(); ?>

    <div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center">
        <?php foreach ($data as $item): ?>
            <?php
            if (isset($item['image_options']) and is_array($item['image_options'])) {
                if (isset($item['image_options']['title'])) {
                    $itemTitle = $item['image_options']['title'];
                } else {
                    $itemTitle = false;
                }

                if (isset($item['image_options']['caption'])) {
                    $itemDescription = $item['image_options']['caption'];
                } else {
                    $itemDescription = false;
                }

                if (isset($item['image_options']['link'])) {
                    $itemLink = $item['image_options']['link'];
                } else {
                    $itemLink = false;
                }
            } else {
                $itemTitle = false;
                $itemDescription = false;
                $itemLink = false;
            }
            ?>
            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                <div class="d-block position-relative show-on-hover-root">
                    <div class="img-as-background   mh-400 mb-3">
                        <img src="<?php print thumbnail($item['filename'], 1080, 1080, true); ?>"/>
                    </div>

                    <div class="show-on-hover position-absolute bg-body border   mh-400 w-100 top-0 mb-3 p-5 text-center align-items-center justify-content-center d-flex flex-column">
                        <?php if ($itemTitle): ?>
                            <h4 class="mb-1"><?php print $itemTitle; ?></h4>
                        <?php endif; ?>
                        <?php if ($itemDescription): ?>
                            <p class="mb-3"><?php print $itemDescription; ?></p>
                        <?php endif; ?>
                        <?php if ($itemLink): ?>
                            <a href="<?php print $itemLink; ?>" class="btn btn-link">Learn More</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>
