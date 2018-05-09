<?php
$module = mw()->modules->get('one=1&ui=any&module=' . $v_mod);
?>

<div id="mw-modules-toolbar">
    <?php if (is_admin()): ?>
        <div class="mw-ui-row-nodrop">
            <div class="mw-ui-col">
                <?php if (isset($module['icon'])): ?><img src="<?php print $module['icon'] ?>" alt="" /><?php endif; ?>
                <span class="module-toolbar-info-description"><?php if (isset($module['name'])) {
                        print $module['name'];
                    }; ?></span>

                <a title="<?php _e("Back"); ?>" href="<?php print admin_url(); ?>view:modules" class="mw-ui-btn mw-ui-btn-info mw-ui-btn-medium m-l-10 btn-back">
                    <span class="mw-icon-arrowleft"></span>
                    <span><?php _e("Back"); ?></span>
                </a>
            </div>
        </div>
        <?php $active = mw()->url_manager->param('view'); ?>
    <?php endif; ?>
</div>
