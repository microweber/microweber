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
<div class="mw-toowbar-back-button-wrapper">
    <div class="main-toolbar mw-modules-toolbar-back-button-holder mb-3" id="mw-modules-toolbar" style="display: none">
        <?php if (is_admin()): ?>
            <a class="link link--arrowed" href="<?php print $backButtonUrl ?>">
                <svg class="arrow-icon" xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 32 32">
                    <g fill="none" stroke="#000000" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                        <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
                        <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                    </g>
                </svg>


<!--                <svg class="arrow-icon" xmlns="http://www.w3.org/2000/svg" height="40" viewBox="0 96 960 960" width="40">-->
<!--                    <g fill="none" stroke="#000000" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">-->
<!--                        <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>-->
<!--                        <path class="arrow-icon--arrow" d="M360 856 80 576l280-280 47 46.666-200.001 200.001H880v66.666H206.999l199.667 200.001L360 856Z"> </path>-->
<!--                    </g>-->
<!--                </svg>-->

                <?php $active = mw()->url_manager->param('view'); ?>

            </a>
        <?php endif; ?>
    </div>
</div>

