<script>
function importSelectType(type)
{
    if (type == 'wordpress') {

    }

    $('.import-modal-vendors').fadeOut();
}
</script>
<h5>
    <?php _e("Select type of import"); ?>
</h5>
<div class="import-modal-vendors">
    <div class="import-modal-vendor-btn" onclick="importSelectType('wordpress')">
        <div class="import-modal-vendor-btn-image">
            <img src="<?php echo modules_url(); ?>/admin/import_export_tool/images/wordpress.svg"/>
        </div>
        <div class="import-modal-vendor-btn-title"><?php _e("Wordpress"); ?></div>
    </div>
    <div class="import-modal-vendor-btn" onclick="importSelectType('woocommerce')">
        <div class="import-modal-vendor-btn-image">
            <img src="<?php echo modules_url(); ?>/admin/import_export_tool/images/woocommerce.svg"/>
        </div>
        <div class="import-modal-vendor-btn-title"><?php _e("WooCommerce"); ?></div>
    </div>
    <div class="import-modal-vendor-btn" onclick="importSelectType('shopify')">
        <div class="import-modal-vendor-btn-image">
            <img src="<?php echo modules_url(); ?>/admin/import_export_tool/images/shopify.svg"/>
        </div>
        <div class="import-modal-vendor-btn-title"><?php _e("Shopify"); ?></div>
    </div>
    <div class="import-modal-vendor-btn" onclick="importSelectType('Feed')">
        <div class="import-modal-vendor-btn-image">
            <img src="<?php echo modules_url(); ?>/admin/import_export_tool/images/feed.svg"/>
        </div>
        <div class="import-modal-vendor-btn-title"><?php _e("Feed"); ?></div>
    </div>
</div>
