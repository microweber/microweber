<script type="text/javascript">
    mw.require("tools.js", true);
    mw.require("shop.js", true);
    mw.require("events.js", true);
</script>
<script type="text/javascript">
    $(document).ready(function () {
        mw.on.moduleReload('cart_fields_<?php print $params['id'] ?>', function () {
            mw.reload_module('#<?php print $params['id'] ?>');
        });
    })

</script>

<script>mw.require('https://fonts.googleapis.com/icon?family=Material+Icons&.css', 'material_icons');</script>
<script>mw.moduleCSS("<?php print modules_url(); ?>shop/cart_add/styles.css"); </script>

<?php
$for_id = false;
$for = 'content';
if (isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'post' and defined('POST_ID')) {
    $params['content-id'] = POST_ID;
    $for = 'content';
}

if (isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'page' and defined('PAGE_ID')) {
    $params['content-id'] = PAGE_ID;
    $for = 'content';
}

$module_template = get_option('data-template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}


if ($module_template != false and $module_template != 'none') {
    $template_file = module_templates($config['module'], $module_template);

} else {
    $template_file = module_templates($config['module'], 'default');

}
if (isset($params['content-id'])) {
    $for_id = $params['content-id'];
}


if (isset($params['for'])) {
    $for = $params['for'];
}
if (isset($params['button_text'])) {
    $button_text = $params['button_text'];
} else {
    $button_text = false;
}

if ($for_id == false and defined('CONTENT_ID')) {
    $for_id = CONTENT_ID;

}
$content_data = content_data($for_id);
$in_stock = true;


if (isset($content_data['qty']) and $content_data['qty'] != 'nolimit' and intval($content_data['qty']) == 0) {

    $in_stock = false;
}

?>
<?php if (isset($for_id) !== false and isset($for) !== false): ?>

    <div class="mw-add-to-cart-holder mw-add-to-cart-<?php print $params['id'] ?>">
        <?php if ($for == 'content' and intval($for_id) == 0) {
            $for_id = 0;
        } ?>
        <?php $data = get_custom_fields("field_type=price&for={$for}&for_id=" . $for_id . ""); ?>
        <?php if (is_array($data) == true): ?>
            <input type="hidden" name="for" value="<?php print $for ?>"/>
            <input type="hidden" name="for_id" value="<?php print $for_id ?>"/>
        <?php endif; ?>

        <?php if (isset($template_file) and is_file($template_file) != false) : ?>
            <?php include($template_file); ?>
        <?php else: ?>
            <?php print lnotif('No default template for ' . $config['module'] . ' is found'); ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
