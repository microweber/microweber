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

<?php $backButtonUrl = '';

if (isset($params['history_back'])) {
    $backButtonUrl = 'javascript:mw.admin.back();';
} elseif (isset($params['back_button_url'])) {
    $backButtonUrl = admin_url() . $params['back_button_url'];
} else {
    $backButtonUrl = admin_url() . 'view:modules';
}


?>
<div class="mw-toolbar-back-button-wrapper">
    <div class="main-toolbar mw-modules-toolbar-back-button-holder mb-3 " id="mw-modules-toolbar" style="display: none">
        <?php if (is_admin()): ?>
            <a href="<?php print $backButtonUrl ?>">
                <svg xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 96 960 960" width="28"><path d="M480 896 160 576l320-320 47 46.666-240.001 240.001H800v66.666H286.999L527 849.334 480 896Z"/></svg>
                <?php $active = mw()->url_manager->param('view'); ?>

            </a>
        <?php endif; ?>
    </div>
</div>

