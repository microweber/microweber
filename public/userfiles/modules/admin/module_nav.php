<?php
$module = mw()->module_manager->get('one=1&ui=any&module=' . $v_mod);
?>

<div class="position-relative">
    <div class="main-toolbar" id="mw-modules-toolbar">
        <?php if (is_admin()): ?>
            <a href="<?php print admin_url(); ?>view:modules" class="btn btn-link text-silver px-0"><i class="mdi mdi-chevron-left"></i> Modules</a>
            <?php $active = mw()->url_manager->param('view'); ?>
        <?php endif; ?>

        <module type="admin/settings_search"/>
    </div>
</div>
