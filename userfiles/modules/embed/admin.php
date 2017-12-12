<?php
only_admin_access();

$rand_id = md5(serialize($params));
$hide_in_live_edit = get_option('hide_in_live_edit', $params['id']);
if($hide_in_live_edit != '' OR $hide_in_live_edit != false){
    $hide_in_live_edit == true;
}
?>

<div class="module-live-edit-settings">
    <div id="mw_email_source_code_editor<?php print $rand_id ?>">
        <label class="mw-ui-label"><?php _e("Insert Embed Code"); ?></label>
        <textarea name="source_code" class="mw_option_field mw-ui-field w100" style="height:86px;" data-refresh="embed"><?php print get_option('source_code', $params['id']) ?></textarea>

        <hr/>
        <label class="mw-ui-label"><?php _e("Hide in Live Edit"); ?></label>
        <input type="checkbox" name="hide_in_live_edit" class="mw_option_field" value="true" data-refresh="embed" <?php if($hide_in_live_edit): ?>checked<?php endif; ?> /> Hide
    </div>
</div>
