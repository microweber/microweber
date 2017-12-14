<?php
$size = $size . 'px';
?>

    <?php if ($logoimage == '' and $text == '') {
        if (is_live_edit()) { ?><span class="mw-logo-no-values"><?php _e('Click to add logo'); ?></span><?php }
    } else { ?>
        <span class="mw-ui-col" style="width: <?php print $size; ?>">
            <img src="<?php print $logoimage; ?>" alt="" style="max-width: 100%;width: <?php print $size; ?>;" class="logo logo-light" <?php if (in_live_edit()) { print 'onclick="javascript:mw.drag.module_settings();void(0);"'; } ?>/>
        </span>
    <?php } ?>
