<?php

/*

type: layout

name: Skin-2

description: Skin-2

*/

?>

<?php if (is_array($data)): ?>
    <?php $rand = uniqid(); ?>

    <?php
    $click_image_event = 'fullscreen';
    $get_click_image_event = get_option('click_image_event', $params['id']);
    if ($get_click_image_event != false) {
        $click_image_event = $get_click_image_event;
    }
    ?>

    <div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center">
        <?php foreach ($data as $item): ?>
            <?php
            $itemTitle = false;
            $itemDescription = false;
            $itemLink = false;
            $itemAltText = 'Open';
            if (isset($item['image_options']) and is_array($item['image_options'])) {
                if (isset($item['image_options']['title'])) {
                    $itemTitle = $item['image_options']['title'];
                }
                if (isset($item['image_options']['caption'])) {
                    $itemDescription = $item['image_options']['caption'];
                }
                if (isset($item['image_options']['link'])) {
                    $itemLink = $item['image_options']['link'];
                }
                if (isset($item['image_options']['alt-text'])) {
                    $itemAltText = $item['image_options']['alt-text'];
                }
            }
            ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="d-block position-relative show-on-hover-root">
                    <div class="img-as-background   mh-350 mb-3">
                        <img   src="<?php print thumbnail($item['filename'], 350, 350, true); ?>"/>
                    </div>

                    <?php if ($itemTitle || $itemDescription || $itemLink): ?>

                        <div class="show-on-hover position-absolute bg-body border   mh-350 w-100 top-0 mb-3 p-5 text-center align-items-center justify-content-center d-flex flex-column">
                            <?php if ($itemTitle): ?>
                                <h4 class="mb-1"><?php print $itemTitle; ?></h4>
                            <?php endif; ?>
                            <?php if ($itemDescription): ?>
                                <p class="mb-3"><?php print $itemDescription; ?></p>
                            <?php endif; ?>

                            <?php if ($itemLink): ?>

                                <a
                                    <?php if ($click_image_event == 'link_target_blank'): ?> target="_blank" <?php endif; ?>

                                    href="<?php print $itemLink; ?>" class="btn btn-link"><?php print $itemAltText; ?></a>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>
