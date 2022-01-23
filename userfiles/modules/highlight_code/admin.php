<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <?php
        $text = get_option('text', $params['id']);

        if ($text == false) {
            $text = '<?php print "Hello World"; ?>';
        }
        ?>

        <div class="module-live-edit-settings module-highlight-code-settings">
            <div class="form-group">
                <label class="control-label"><?php _lang('Enter some text', "modules/highlight_code"); ?></label>
                <textarea class="mw_option_field form-control" rows="20" name="text"><?php _lang($text, 'modules/highlight_code'); ?></textarea>
            </div>
        </div>
    </div>
</div>
