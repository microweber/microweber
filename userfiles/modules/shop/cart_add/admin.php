<?php

$for_id = false;
$for = 'content';

if (isset($params['rel'])) {
    $params['rel_type'] = $params['rel'];
}


if (isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'post' and defined('POST_ID')) {
    $for_id = $params['content-id'] = POST_ID;
    $for = 'content';
}

if (isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'page' and defined('PAGE_ID')) {
    $for_id = $params['content-id'] = PAGE_ID;
    $for = 'content';
}


?>

<script>
    $(document).ready(function () {
        $(window).on("custom_fields.save", function (event) {
            mw.reload_module_parent('#<?php print $params['id'] ?>');
        });
    });
</script>


<div class="mw-modules-tabs">
    <?php if ($for_id): ?>
        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-gear"></i> <?php _e('Custom fields'); ?>
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-cart-add-settings">
                    <module type="custom_fields/admin" data-content-id="<?php print intval($for_id); ?>"/>
                </div>
                <!-- Settings Content - End -->
            </div>
        </div>
    <?php endif; ?>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>