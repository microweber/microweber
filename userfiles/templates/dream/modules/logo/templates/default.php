<?php
$size = $size . 'px';

 
?>


<a href="<?php if (!in_live_edit()) {
    print site_url();
} else {
    print 'javascript:mw.drag.module_settings();void(0);';
} ?>">
    <?php if ($logoimage == '' and $text == ''): ?>
        <?php if (is_live_edit()) : ?>
            <span class="mw-logo-no-values"><?php _e('Logo'); ?></span>
        <?php endif; ?>
    <?php else: ?>
        <?php if ($logotype == 'image' or $logotype == false): ?>
            <?php if ($logoimage != '' and $logoimage != false): ?>
                <span class="mw-ui-col" style="width: <?php print $size; ?>">
                    <?php if ($logoimage_inverse != '' and $logoimage_inverse != false): ?>
                        <img src="<?php print $logoimage_inverse; ?>" alt="" style="max-width: 100%; width: <?php print $size; ?>;" class="logo logo-dark" <?php if (in_live_edit()) {
                            print 'onclick="javascript:mw.drag.module_settings();void(0);"';
                        } ?>/>
                    <?php endif; ?>
                    <img src="<?php print $logoimage; ?>" alt="" style="max-width: 100%; width: <?php print $size; ?>;" class="logo logo-light" <?php if (in_live_edit()) {
                        print 'onclick="javascript:mw.drag.module_settings();void(0);"';
                    } ?>/>
                </span>
            <?php else: ?>
                <span class="mw-logo-no-values"><?php _e('Logo'); ?></span>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($logotype == 'text' or $logotype == 'both'): ?>
        <script>
            mw.lib.require('fitty');

            $(document).ready(function () {
                //fitty(document.getElementById('fitty-<?php print $params['id'] ?>-1'));
                //fitty(document.getElementById('fitty-<?php print $params['id'] ?>-2'));
            });
        </script>
        <?php if($text == '') {
            $text = 'Logo';
        } ?>
            <span class="mw-ui-col">
                <div class="logo logo-dark"><span class="logo-text"><span id="fitty-<?php print $params['id'] ?>-1" style="font-family: '<?php print $font_family_safe; ?>';font-size:<?php print $font_size; ?>px;line-height: 66px;"><?php print $text; ?></span></span></div>
                <div class="logo logo-light"><span class="logo-text"><span id="fitty-<?php print $params['id'] ?>-2" style="font-family: '<?php print $font_family_safe; ?>';font-size:<?php print $font_size; ?>px;line-height: 66px;"><?php print $text; ?></span></span></div>
            </span>
        <?php endif; ?>
    <?php endif; ?>
</a>