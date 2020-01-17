<div class="send-your-lang">
    <label class="mw-ui-label">
        <small><?php _e('Help us improve Microweber'); ?></small>
    </label>
    <a onclick="send_lang_form_to_microweber()"
       class="mw-ui-btn mw-ui-btn-blue"><?php _e('Send us your translation'); ?></a></div>
<?php only_admin_access(); ?>
<label class="mw-ui-label left">
    <?php _e("Edit your language file"); ?>
</label>
<div class="mw_clear"></div>

<script type="text/javascript">
    mw.require('forms.js', true);
</script>
<script type="text/javascript">

	function import_language_by_namespace(namespace, language) {
		mw.modal({
		    content: '<div id="mw_admin_import_language_modal_content"></div>',
		    title: 'Import Language File',
		    height:200,
		    id: 'mw_admin_import_language_modal'
		});
		var params = {};
		params.namespace = namespace;
		params.language = language; 
		mw.load_module('settings/group/language_import', '#mw_admin_import_language_modal_content', null, params);
	}

	function export_language_by_namespace(namespace, language) {
		$.ajax({
			type: "POST",
			url: mw.settings.api_url + "Microweber/Utils/Language/export",
			data: "namespace=" + namespace + "&language="+language, 
			success: function (data) {
				window.location = data;
			}
		});
	}
	
    function send_lang_form_to_microweber() {

        if (!mw.$(".send-your-lang a").hasClass("disabled")) {

            mw.tools.disable(mwd.querySelector(".send-your-lang a"), "<?php _e('Sending...'); ?>");
            $.each($('.lang-edit-form'), function () {
                mw.form.post($(this), '<?php print api_link('send_lang_form_to_microweber'); ?>',
                    function (msg) {
                        mw.notification.msg(this, 1000, true);
                        mw.tools.enable(mwd.querySelector(".send-your-lang a"));
                    });
            });
        }
        
        return false;
    }


    function save_lang_form($form_id) {
		
    	var formArray = $('#'+$form_id).serializeArray();
		
        $.ajax({   
			type: "POST",
			url: "<?php print api_link('save_language_file_content'); ?>",
			data: {lines: JSON.stringify(formArray) },
			dataType: "json",
			success: function (msg) {
				mw.notification.msg(msg);
			}
		});
        
        return false;
    }
</script>
<style>
    .send-your-lang {
        float: right;
        width: 190px;
        text-align: center;
        margin-top: -77px;
    }

    html[dir="rtl"] .send-your-lang {
        float: left;
    }

    .send-your-lang label {
        text-align: center;
    }


    .mw-ui-table .mw-ui-field {
        background-color: transparent;
        border-color: transparent;
        width: 300px;
        height: 36px;
        resize: none;
    }

    .mw-ui-table .mw-ui-field:hover, .mw-ui-table .mw-ui-field:focus {
        background-color: white;
        border-color: #C6C6C6 #E6E6E6 #E6E6E6;
        resize: vertical;
    }

    .mw_lang_item_textarea_edit {
        width: 100% !important;
    }

</style>
<?php


/*
 * $lang = get_option('language', 'website');
if (!$lang) {
    $lang = 'en';
}
set_current_lang($lang);
*/
$lang = mw()->lang_helper->current_lang();

$cont = mw()->lang_helper->get_language_file_content();
$namespaces = mw()->lang_helper->get_all_language_file_namespaces();


?>
<?php if (!empty($cont)): ?>
    <div id="accordion-<?php print $params['id'] ?>" class="mw-ui-box mw-ui-box-silver-blue active">
        <div class="mw-ui-box-header" onclick="mw.accordion('#accordion-<?php print $params['id'] ?>');">
            <div class="header-holder">
                <i class="mai-setting2"></i>Language file: Global
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box-content" style="">
            <h3>Global language file</h3>
            
            <a onClick="export_language_by_namespace('global', '<?php print $lang ?>');" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-rounded">Export to Excel</a>
            <a onClick="import_language_by_namespace('global', '<?php print $lang ?>');" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-rounded">Import Excel</a>
			<br />
			<br />
            
            <form id="language-form-<?php print $params['id'] ?>" class="lang-edit-form">
                <input name="___lang" value="<?php print $lang ?>" type="hidden">

                <table width="100%" border="0" class="mw-ui-table" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th scope="col" width="20%"><?php _e('Key'); ?></th>
                        <th scope="col"><?php _e('Value'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cont as $k => $item): ?>
                    <tr>
                        <td><?php print $k ?></td>
                        <td><input name="<?php print $k ?>" class="mw-ui-field mw_lang_item_textarea_edit" type="text"
                                   onchange="save_lang_form('language-form-<?php print $params['id'] ?>')"
                                   wrap="soft" rows="1" value="<?php print $item ?>"/>
                    </tr>
                    </tbody>
                    <?php endforeach; ?>
                </table>
            </form>
        </div>
    </div>


<?php endif; ?>




<?php if (!empty($namespaces)): ?>
    <?php foreach ($namespaces as $iter => $ns): ?>

        <?php
        $cont = mw()->lang_helper->get_language_file_content($ns);
        ?>

        <?php if (!empty($cont)): ?>
            <div id="accordion-<?php print $params['id'] . $iter ?>" class="mw-ui-box mw-ui-box-silver-blue active">
                <div class="mw-ui-box-header"
                     onclick="mw.accordion('#accordion-<?php print $params['id'] . $iter ?>');">
                    <div class="header-holder">
                        <i class="mai-setting2"></i>Language file: <?php print $ns ?> 
                    </div>
                </div>
                
                <div class="mw-accordion-content mw-ui-box-content">
					 
					<a onClick="export_language_by_namespace('<?php print $ns; ?>', '<?php print $lang ?>');" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-rounded">Export Excel</a>
					<a onClick="import_language_by_namespace('<?php print $ns; ?>', '<?php print $lang ?>');" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-rounded">Import Excel</a>
					<br />
					<br />
					
                    <form id="language-form-<?php print $params['id'] . $iter ?>" class="lang-edit-form">
                        <input name="___namespace" value="<?php print $ns ?>" type="hidden">
                        <input name="___lang" value="<?php print $lang ?>" type="hidden">
                        <table width="100%" border="0" class="mw-ui-table" cellspacing="0" cellpadding="0">
                            <thead>
                            <tr>
                                <th scope="col" width="20%"><?php _e('Key'); ?></th>
                                <th scope="col"><?php _e('Value'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($cont as $k => $item): ?>
                            <tr>
                                <td><?php print $k ?></td>
                                <td><input name="<?php print $k ?>" class="mw-ui-field mw_lang_item_textarea_edit"
                                           type="text"
                                           onchange="save_lang_form('language-form-<?php print $params['id'] . $iter ?>')"
                                           wrap="soft" rows="1" value="<?php print $item ?>"/>
                            </tr>
                            </tbody>
                            <?php endforeach; ?>
                        </table>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>


<?php endif; ?>


<div class="send-your-lang" style="margin: 40px 0;">
    <label class="mw-ui-label">
        <small><?php _e('Help us improve Microweber'); ?></small>
    </label>
    <a onclick="send_lang_form_to_microweber()" class="mw-ui-btn mw-ui-btn-blue"><?php _e('Send us your translation'); ?></a>
</div>