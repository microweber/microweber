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
<script>

    $( document ).ready(function() {
        var el = $('.mw-modules-toolbar-back-button-holder').first();

        if(el){
            if(!el.is(":visible")){
            $('.mw-modules-toolbar-back-button-holder').show();
            }
        }
    })

</script>
<div class="position-relative">
    <div class="main-toolbar mw-modules-toolbar-back-button-holder mb-3" id="mw-modules-toolbar" style="display: none">
        <?php if (is_admin()): ?>
            <a <?php if (isset($params['history_back'])) { ?>href="javascript:;" onClick="mw.admin.back()"<?php } else { ?> href="<?php print admin_url(); ?>view:modules"<?php } ?><i class="mdi mdi-chevron-left fs-1 btn btn-outline-dark btn-sm"></i> </a>
            <?php $active = mw()->url_manager->param('view'); ?>
        <?php endif; ?>

        <?php /*<module type="admin/settings_search"/>*/ ?>
    </div>
</div>
