<?php if (is_admin() == false) {
    return array('error' => 'Not logged in as admin');
}


if (!isset($params['load_list'])) {
    return 'Error: Provide load_list parameter!';
}

?>
<script type="text/javascript">
    mw.require('<?php print $config['url_to_module']; ?>forms_data_manager.js');

</script>
<?php
$def = _e("Search for data", true);
$load_list = $params['load_list'];
?>
<?php
if (trim($load_list) == 'default') {
    $data = array();
    $data['title'] = "Default list";
    $data['id'] = "default";
} else if (trim($load_list) == 'all_lists') {
	$data = array();
	$data['title'] = "All lists";
	$data['id'] = "all_lists";
} else {
    $data = get_form_lists('single=1&id=' . $load_list);
}
?>

<style>
#start-email-campaign {
	padding: 15px 0px 4px;
}
.form-list-toolbar h2 {
	margin-top: 0;
}
</style>

<?php 
$hideEditButton = false;
if ($load_list == 'default') {
	$hideEditButton = true;
} else if ($load_list == 'all_lists') {
	$hideEditButton = true;
}
?>

    <div class="mw-ui-row form-list-toolbar">
        <div class="mw-ui-col" style="width: 20%;">
            <h2 <?php if (!$hideEditButton): ?>id="form_field_title" <?php endif; ?>><?php print ($data['title']); ?></h2>
            
            <?php if (!$hideEditButton): ?>
            <a href="#" onClick='$("#form_field_title").click();' style="color: #0086DB;">Edit list name</a>
            <br />
            <br />
            <?php endif; ?>
            
        </div>
        <div class="mw-ui-col">


            <div class="contact-form-export-search">


                <div class="export-label pull-right">
                    <span><?php _e("Export data"); ?>:</span>
                    <span class="mw-ui-btn mw-ui-btn-small" onclick="javascript:mw.forms_data_manager.export_to_excel('<?php print $data['id'] ?>');"><?php _e("Excel"); ?></span>
                </div>

                <input
                        name="forms_data_keyword"
                        id="forms_data_keyword"
                        autocomplete="off"
                        class="pull-right mw-ui-searchfield"
                        type="search"
                        placeholder='<?php print $def; ?>'
                        onkeyup="mw.form.dstatic(event);mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});" />


            </div>

        </div>
    </div>
