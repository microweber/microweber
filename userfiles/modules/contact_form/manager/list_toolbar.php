<?php
if (is_admin() == false) {
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

$hideEditButton = false;
if ($load_list == 'default') {
    $hideEditButton = true;
} else if ($load_list == 'all_lists') {
    $hideEditButton = true;
}
?>

<div class="form-list-toolbar row d-flex align-items-center">
    <div class="col-sm-6">
        <label class="control-label d-inline-block"><?php _e("Entries for list:") ?> <span <?php if (!$hideEditButton): ?>id="form_field_title"<?php endif; ?>><?php print ($data['title']); ?></span></label>

        <?php if (!$hideEditButton): ?>
            <a href="#" onClick='$("#form_field_title").click();'>
                <small>(edit name)</small>
            </a>
        <?php endif; ?>
    </div>

    <div class="col-sm-6 text-end text-right">
        <div class="contact-form-export-search text-end text-right d-inline-block">
            <div class="form-inline">
                <div class="form-group mr-1 mb-2">
                    <input name="forms_data_keyword" id="forms_data_keyword" autocomplete="off" class="form-control form-control-sm" type="search" placeholder='<?php print $def; ?>'/>
                </div>

                <button type="submit" class="btn btn-primary btn-icon btn-sm mb-2" onclick="mw.url.windowHashParam('search', $('#forms_data_keyword').val());"><i class="mdi mdi-magnify"></i></button>
            </div>
        </div>
    </div>
</div>
