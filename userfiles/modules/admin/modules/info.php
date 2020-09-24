<?php
if (isset($params['for-module'])) {
    $params['parent-module'] = $params['for-module'];
}
if (!isset($params['parent-module'])) {
    return;
}

$v_mod = $params['parent-module'];

$module = mw()->module_manager->get('one=1&ui=any&module=' . $v_mod);
?>

<div class="position-relative">
    <div class="main-toolbar" id="mw-modules-toolbar">
        <?php if (is_admin()): ?>
            <a <?php if (isset($params['history_back'])) { ?>href="javascript:;" onClick="history.go(-1)"<?php } else { ?> href="<?php print admin_url(); ?>view:modules"<?php } ?> class="btn btn-link text-silver"><i class="mdi mdi-chevron-left"></i> <?php _e("Back"); ?></a>
            <?php $active = mw()->url_manager->param('view'); ?>
        <?php endif; ?>

        <?php /*<module type="admin/settings_search"/>*/ ?>
    </div>
</div>