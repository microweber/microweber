<?php
only_admin_access();
$data = false;
if (isset($params['content-id'])) {
    $data = get_content_by_id($params["content-id"]);

}


$available_content_types = false;
$available_content_subtypes = false;
/* FILLING UP EMPTY CONTENT WITH DATA */
if ($data == false or empty($data)) {
    $is_new_content = true;
    include('_empty_content_data.php');
} else {

    $available_content_types = get_content('group_by=content_type');
    $available_content_subtypes = get_content('group_by=subtype');


}
/* END OF FILLING UP EMPTY CONTENT  */
$show_page_settings = false;
if (isset($params['content-type']) and $params['content-type'] == 'page') {
    $show_page_settings = 1;
}

$template_config = mw()->template->get_config();
$data_fields_conf = false;
$data_fields_values = false;

if (!empty($template_config)) {
    if (isset($params['content-type'])) {
        if (isset($template_config['data-fields-' . $params['content-type']]) and is_array($template_config['data-fields-' . $params['content-type']])) {
            $data_fields_conf = $template_config['data-fields-' . $params['content-type']];
            if (isset($params['content-id'])) {
                $data_fields_values = content_data($params['content-id']);
            }
        }
    }
}

?>

    <script type="text/javascript">
        mw.reset_current_page = function (a, callback) {
            mw.tools.confirm("<?php _e("Are you sure you want to Reset the content of this page?  All your text will be lost forever!!"); ?>", function () {
                var obj = {id: a}
                $.post(mw.settings.site_url + "api/content/reset_edit", obj, function (data) {
                    mw.notification.success("<?php _e('Content was resetted!'); ?>");

                    if (typeof(mw.edit_content) == 'object') {
                        mw.edit_content.load_editor()
                    }


                    //

                    typeof callback === 'function' ? callback.call(data) : '';
                });
            });
        }
        mw.copy_current_page = function (a, callback) {
            mw.tools.confirm("<?php _e("Are you sure you want to copy this page?"); ?>", function () {
                var obj = {id: a}
                $.post(mw.settings.site_url + "api/content/copy", obj, function (data) {
                    mw.notification.success("<?php _e('Content was copied'); ?>");
                    if (data != null) {
                        var r = confirm("<?php _e('Go to the new page?'); ?>");
                        if (r == true) {
                            if (self != top) {
                                top.window.location = mw.settings.site_url + "api/content/redirect_to_content?id=" + data;

                            } else {
                                mw.url.windowHashParam('action', 'editpage:' + data);

                            }
                            //content/redirect_to_content_id

                        } else {

                        }
                    }
                    typeof callback === 'function' ? callback.call(data) : '';
                });
            });
        }
        mw.del_current_page = function (a, callback) {
            mw.tools.confirm("<?php _e("Are you sure you want to delete this"); ?>", function () {
                var arr = (a.constructor === [].constructor) ? a : [a];
                var obj = {ids: arr}
                $.post(mw.settings.site_url + "api/content/delete", obj, function (data) {
                    mw.notification.warning("<?php _e('Content was sent to Trash'); ?>");
                    typeof callback === 'function' ? callback.call(data) : '';
                });
            });
        }

        mw.adm_cont_type_change_holder_event = function (el) {
            mw.tools.confirm("<?php _e("Are you sure you want to change the content type"); ?>? <?php _e("Please consider the documentation for more info"); ?>", function () {
                var root = mwd.querySelector('#<?php print $params['id']; ?>');
                var form = mw.tools.firstParentWithClass(root, 'mw_admin_edit_content_form');
                var ctype = $(el).val()
                if (form != undefined && form.querySelector('input[name="content_type"]') != null) {
                    form.querySelector('input[name="content_type"]').value = ctype;
                }
            });
        }
        mw.adm_cont_subtype_change_holder_event = function (el) {
            mw.tools.confirm("<?php _e("Are you sure you want to change the content subtype"); ?>? <?php _e("Please consider the documentation for more info"); ?>", function () {
                var root = mwd.querySelector('#<?php print $params['id']; ?>');
                var form = mw.tools.firstParentWithClass(root, 'mw_admin_edit_content_form');
                var ctype = $(el).val();
                if (form != undefined && form.querySelector('input[name="subtype"]') != null) {
                    form.querySelector('input[name="subtype"]').value = ctype
                }
            });
        }
        mw.adm_cont_enable_edit_of_created_at = function () {
            $('.mw-admin-edit-post-change-created-at-value').removeAttr('disabled').show();
            $('.mw-admin-edit-post-display-created-at-value').remove();


        }

        mw.adm_cont_enable_edit_of_updated_at = function () {
            $('.mw-admin-edit-post-change-updated-at-value').removeAttr('disabled').show();
            $('.mw-admin-edit-post-display-updated-at-value').remove();


        }
    </script>

<?php event_trigger('mw.admin.content.edit.advanced_settings', $data); ?>

<?php if (isset($params['content-type']) and isset($params['content-id'])): ?>


    <module type="content/views/settings_from_template" content-type="<?php print $params['content-type'] ?>"
            content-id="<?php print $params['content-id'] ?>"/>


<?php endif; ?>

<?php
/*
<?php if(is_array($data_fields_conf)): ?>

<div class="mw-ui-row">
  <div class="mw-ui-col">
    <div class="mw-ui-col-container">
      <?php foreach($data_fields_conf as $item): ?>
        <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
		<?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
        <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
        <?php $type = (isset($item['type'])) ? ($item['type']) : 'text'; ?>
        <?php $default_value = (isset($item['default_value'])) ? ($item['default_value']) : ''; ?>
        <?php $type = (isset($item['type'])) ? ($item['type']) : ''; ?>
        <?php $name = (isset($item['name'])) ? ($item['name']) : url_title($item['title']); ?>
        <?php $value = (isset($item['value'])) ? ($item['value']) : false; ?>


      <?php 
	  
	  if(is_array($data_fields_values) and isset($data_fields_values[$name])){
		  $value = $data_fields_values[$name];
	  }
	 
	  ?>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">
          <?php print $title; ?>
         </label>
         
         <?php if($type=='textarea') { ?>
         <textarea name="data_<?php print $name; ?>" class="mw-ui-field w100" placeholder="<?php print $default_value ?>"><?php print $value ?></textarea>
              

         <?php } else { ?>
         
        <input name="data_<?php print $name; ?>" class="mw-ui-field w100" type="text" placeholder="<?php print $default_value ?>" value="<?php print $value ?>">
        <?php } ?>
      </div>
      
      
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>*/

?>
    <div class="mw-ui-row advanced-settins-tab">
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">
                        <?php _e("Description"); ?>
                        <small class="mw-help mw-help-right-bottom" data-help="Short description for yor content.">(?)</small>
                    </label>
                    <textarea class="mw-ui-field" name="description" placeholder="<?php _e("Describe your page in short"); ?>"><?php if ($data['description'] != '') print ($data['description']) ?></textarea>
                </div>
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">
                        <?php _e("Meta Title"); ?>
                        <small class="mw-help" data-help="Title for this <?php print $data['content_type'] ?> that will appear on the search engines on social networks.">(?)</small>
                    </label>
                    <textarea class="mw-ui-field" name="content_meta_title" placeholder="<?php _e("Title to appear on the search engines results page"); ?>"><?php if (isset($data['content_meta_title']) and $data['content_meta_title'] != '') print ($data['content_meta_title']) ?></textarea>
                </div>
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">
                        <?php _e("Meta Keywords"); ?>
                        <small class="mw-help" data-help="Keywords for this <?php print $data['content_type'] ?> that will help the search engines to find it. Ex: ipad, book, tutorial"> (?)</small>
                    </label>
                    <textarea class="mw-ui-field" name="content_meta_keywords"
                              placeholder="<?php _e("Type keywords that describe your content - Example: Blog, Online News, Phones for Sale etc"); ?>"><?php if (isset($data['content_meta_keywords']) and $data['content_meta_keywords'] != '') print ($data['content_meta_keywords']) ?></textarea>
                </div>


            </div>
        </div>

        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <?php if (isset($data['id']) and $data['id'] > 0): ?>
                    <div class="mw-ui-field-holder pull-right" style="width: 100%;">
                        <div class="pull-left bold">More options:</div>
                        <div class="pull-right">
                            <a class="mw-ui-btn btn-small" href="javascript:mw.copy_current_page('<?php print ($data['id']) ?>');"><?php _e("Duplicate"); ?></a>&nbsp;
                            <a class="mw-ui-btn btn-small" href="javascript:mw.reset_current_page('<?php print ($data['id']) ?>');"><?php _e("Reset Content"); ?></a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($show_page_settings == false): ?>
                    <module type="content/views/content_tags" content-type="<?php print $params['content-type'] ?>" content-id="<?php print $params['content-id'] ?>"/>
                <?php endif; ?>

                <div class="mw-clear" style="height: 12px;"></div>
                <?php if ($show_page_settings != false): ?>
                    <div class="mw-ui-check-selector">
                        <div class="mw-ui-label">
                            <?php _e("Is Home"); ?>
                            <small class="mw-help" data-help="<?php _e("If yes this page will be your Home"); ?>">(?)</small>
                        </div>
                        <label class="mw-switch mw-switch-action">
                            <input type="checkbox" name="is_home" data-value-checked="1" data-value-unchecked="0" <?php if ('1' == trim($data['is_home'])): ?>checked="1"<?php endif; ?>>
                            <span class="mw-switch-off">NO</span>
                            <span class="mw-switch-on">YES</span>
                            <span class="mw-switcher"></span>
                        </label>

                    </div>
                    <div class="mw_clear vSpace"></div>
                    <div class="mw-ui-check-selector">
                        <div class="mw-ui-label">
                            <?php _e("Is Shop"); ?>
                            <small class="mw-help" data-help="<?php _e("If yes this page will accept products to be added to it"); ?>">(?)</small>
                        </div>
                        <label class="mw-switch mw-switch-action">
                            <input type="checkbox" name="is_shop" data-value-checked="1" data-value-unchecked="0" <?php if ('1' == trim($data['is_shop'])): ?>checked="1"<?php endif; ?>>
                            <span class="mw-switch-off">NO</span>
                            <span class="mw-switch-on">YES</span>
                            <span class="mw-switcher"></span>
                        </label>

                    </div>
                    <div class="mw_clear vSpace"></div>
                <?php endif; ?>

                <div class="mw-ui-row">
                    <div class="mw-ui-col">
                        <div class="mw-ui-field-holder">
                            <label class="mw-ui-label">
                                <?php _e("Set password"); ?>
                                <small class="mw-help" data-help="<?php _e("If set this, the user will be redirected to the new URL when visits the page"); ?>">(?)</small>
                            </label>

                            <div class="password-input">
                                <script>
                                    $('.btn-password').on('click', function () {
                                        var currentInputType = $(this).parent().find('input');
                                        console.log(currentInputType);
                                        if (currentInputType.attr('type') == 'password') {
                                            currentInputType.prop('type', 'text');
                                        } else {
                                            currentInputType.prop('type', 'password');
                                        }
                                    });
                                </script>
                                <input name="original_link" class="mw-ui-field" type="password" placeholder="<?php _e('Type password here'); ?>" value="<?php print $data['original_link'] ?>">
                                <button type="button" class="mai-eye2 btn-password"></button>
                            </div>
                        </div>
                    </div>

                    <div class="mw-ui-col" style="width: 200px;">
                        <div class="mw-ui-check-selector" style="padding: 12px 30px;">
                            <div class="mw-ui-label">
                                <?php _e("Require password"); ?>
                                <small class="mw-help" data-help="<?php _e("If set to yes - this page will require login from a registered user in order to be opened"); ?>">(?)</small>
                            </div>

                            <label class="mw-switch mw-switch-action">
                                <input type="checkbox" name="require_password" data-value-checked="1" data-value-unchecked="0" <?php if ('1' == trim($data['require_login'])): ?>checked="1"<?php endif; ?>>
                                <span class="mw-switch-off">NO</span>
                                <span class="mw-switch-on">YES</span>
                                <span class="mw-switcher"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mw-ui-row">
                    <div class="mw-ui-col">
                        <?php
                        $redirected = false;
                        if (isset($data['original_link']) and $data['original_link'] != '') {
                            $redirected = true;
                        } else {
                            $data['original_link'] = '';
                        }
                        ?>

                        <div class="mw-ui-field-holder">
                            <label class="mw-ui-label">
                                <?php _e("Redirect to url"); ?>
                                <small class="mw-help" data-help="<?php _e("If set this, the user will be redirected to the new URL when visits the page"); ?>">(?)</small>
                            </label>

                            <input name="original_link" class="mw-ui-field" type="text" placeholder="<?php _e('http://yoursite.com'); ?>" value="<?php print $data['original_link'] ?>">
                        </div>
                    </div>

                    <div class="mw-ui-col" style="width: 200px;">
                        <div class="mw-ui-check-selector" style="padding: 12px 30px;">
                            <div class="mw-ui-label">
                                <?php _e("Require login"); ?>
                                <small class="mw-help" data-help="<?php _e("If set to yes - this page will require login from a registered user in order to be opened"); ?>">(?)</small>
                            </div>

                            <label class="mw-switch mw-switch-action">
                                <input type="checkbox" name="require_login" data-value-checked="1" data-value-unchecked="0" <?php if ('1' == trim($data['require_login'])): ?>checked="1"<?php endif; ?>>
                                <span class="mw-switch-off">NO</span>
                                <span class="mw-switch-on">YES</span>
                                <span class="mw-switcher"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <?php if (isset($data['position'])): ?>
                    <input name="position" type="hidden" value="<?php print ($data['position']) ?>"/>
                <?php endif; ?>

                <?php /* PAGES ONLY  */ ?>
                <?php event_trigger('mw_admin_edit_page_advanced_settings', $data); ?>
            </div>
        </div>
    </div>

    <div class="mw-ui-row">
        <?php if (is_array($available_content_types) and !empty($available_content_types)): ?>
            <div class="mw-ui-col">
                <div class="mw-ui-field-holder"><br/>
                    <span class="bold"><?php _e("Content type"); ?>: &nbsp;</span>

                    <a class="mw-ui-btn mw-ui-btn-small btn-small" href="javascript:$('.mw_adm_cont_type_change_holder').toggle();void(0);"> <?php print($data['content_type']) ?>
                        <span class="mw-ui-arr mw-ui-arr-down" style="opacity:0.3"></span>
                    </a>

                    <div class="mw_adm_cont_type_change_holder mw-ui-box mw-ui-box-content" style="display:none;margin-top: 12px;">
                        <div class="mw-ui-field-holder"><?php _e("Warning! Advanced action!"); ?><br/><?php _e("Do not change these settings unless you know what you are doing."); ?></div>

                        <div class="mw-ui-row">
                            <div class="mw-ui-col" style="width: 200px;">
                                <div class="mw-ui-col-container">
                                    <label class="mw-ui-label">
                                        <?php _e("Change content type"); ?>
                                        <small class="mw-help" data-help="<?php _e("Changing the content type to different than"); ?> '<?php print $data['content_type'] ?>' <?php _e("is advanced action. Please read the documentation and consider not to change the content type"); ?>">(?)</small>
                                    </label>

                                    <select class="mw-ui-field" name="change_content_type" style="width: 190px;" onchange="mw.adm_cont_type_change_holder_event(this)">
                                        <?php foreach ($available_content_types as $item): ?>
                                            <option value="<?php print $item['content_type']; ?>" <?php if ($item['content_type'] == trim($data['content_type'])): ?>   selected="selected"  <?php endif; ?>><?php print $item['content_type']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mw-ui-col">
                                <div class="mw-ui-col-container">
                                    <label class="mw-ui-label">
                                        <?php _e("Change content sub type"); ?>
                                        <small class="mw-help" data-help="<?php _e("Changing the content type to different than"); ?> '<?php print $data['subtype'] ?>' <?php _e("is advanced action. Please read the documentation and consider not to change the content type"); ?>">(?)</small>
                                    </label>

                                    <select class="mw-ui-field" name="change_contentsub_type" onchange="mw.adm_cont_subtype_change_holder_event(this)">
                                        <?php foreach ($available_content_subtypes as $item): ?>
                                            <option value="<?php print $item['subtype']; ?>" <?php if ($item['subtype'] == trim($data['subtype'])): ?>   selected="selected"  <?php endif; ?>><?php print $item['subtype']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($data['id']) and $data['id'] != 0): ?>
            <div class="mw-ui-col p-t-20">
                <?php if (isset($data['created_at'])): ?>
                    <div class="mw-admin-edit-post-created-at" onclick="mw.adm_cont_enable_edit_of_created_at()">
                        <small>
                            <?php _e("Created on"); ?>: <span class="mw-admin-edit-post-display-created-at-value"><?php print $data['created_at'] ?></span>
                            <input class="mw-admin-edit-post-change-created-at-value" style="display:none" type="datetime" name="created_at" value="<?php print $data['created_at'] ?>" disabled="disabled">
                        </small>
                    </div>
                <?php endif; ?>

                <?php if (isset($data['updated_at'])): ?>
                    <div class="mw-admin-edit-post-updated-at" onclick="mw.adm_cont_enable_edit_of_updated_at()">
                        <small><?php _e("updated on"); ?>: <span class="mw-admin-edit-post-display-updated-at-value"><?php print $data['updated_at'] ?></span>
                            <input class="mw-admin-edit-post-change-updated-at-value" style="display:none" type="datetime" name="updated_at" value="<?php print $data['updated_at'] ?>" disabled="disabled">
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

<?php $custom = mw()->modules->ui('mw.admin.content.edit.advanced_settings.end'); ?>

<?php if (!empty($custom)): ?>
    <div>
        <?php foreach ($custom as $item): ?>
            <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
            <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
            <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
            <?php print $html; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>