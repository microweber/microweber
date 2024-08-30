<?php

/*

type: layout

name: Default

description: Default

*/
?>

<?php

$size = ($size == 'auto' ? '' : ' max-width:' . $size . 'px');

$style = ($size == '' ? '' : ' style="' . $size . '"');
?>
    <script>mw.moduleCSS("<?php print $config['url_to_module']; ?>/styles.css"); </script>

<?php if (is_live_edit() and $default_image == '' and $text == ''): ?>
    <?php print lnotif(_lang('Click to add image', "modules/image_rollover/templates",true)); ?>
<?php else: ?>
    <div class="mw-rollover"<?php print $style; ?>>
        <div class="mw-rollover_images">
            <a href="<?php print $href_url; ?>"><img class="mw-rollover-default_image" src="<?php print $default_image; ?>" alt=""<?php print $style; ?>/></a>
            <div class="mw-rollover-overlay">
                <a href="<?php print $href_url; ?>"<?php print $style; ?>><img src="<?php print $rollover_image; ?>" alt=""<?php print $style; ?>/></a>
            </div>
        </div>

        <p class="module-image-rollover-text"><a href="<?php print $href_url; ?>"><?php print $text; ?></a></p>
    </div>
<?php endif; ?>
