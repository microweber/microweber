<?php
/*
type: layout
name: Slider
description: Pictures slider
*/
?>

<?php if (is_array($data)): ?>
    <?php $id = "slider-" . uniqid(); ?>
    <div class="well mw-module-images slider">
        <div class=" mw-rotator mw-rotator-template-slider" id="<?php print $id; ?>">
            <div class=" mw-gallery-holder">
                <?php foreach ($data as $item): ?>
                    <div class=" mw-gallery-item mw-gallery-item-<?php print $item['id']; ?>">
                        <img src="<?php print thumbnail($item['filename'], 1200); ?>" alt="<?php print addslashes($item['title']); ?>"/>
                        <?php if ($item['title'] != '') { ?><i class="mw-rotator-description mw-rotator-description-content"><?php print $item['title']; ?></i><?php } ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        mw.moduleCSS("<?php print asset('modules/pictures/css/slider.css'); ?>");
        mw.moduleCSS("<?php print asset('modules/pictures/css/style.css'); ?>");
        mw.require("<?php print asset('modules/pictures/js/api.js'); ?>", true);
    </script>
    <script type="text/javascript">
        Rotator = null;
        $(document).ready(function () {
            if ($('#<?php print $id; ?>').find('.mw-gallery-item').length > 1) {
                Rotator = mw.rotator('#<?php print $id; ?>');
                if (!Rotator) return false;
                Rotator.options({
                    paging: true,
                    next: true,
                    prev: true
                });

            }
        });
    </script>
<?php else : ?>
<?php endif; ?>
