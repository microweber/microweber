<?php
only_admin_access();
$data = false;
if (isset($params['content-id'])) {
    $data = get_content_by_id(intval($params["content-id"]));
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


?>
<script type="text/javascript">

    mw.copy_current_page = function (a, callback) {
        mw.tools.confirm("<?php _e("Are you sure you want to copy this page?"); ?>", function () {
            var obj = {id: a}
            $.post(mw.settings.site_url + "api/content/copy", obj, function (data) {
                mw.notification.success("<?php _e('Content was copied'); ?>.");
				
				if(data != null){
				
					var r = confirm("Go to the new page?");
					if (r == true) {
						mw.url.windowHashParam('action','editpage:'+data);
					} else {
						 
					}
				}
				
				
				//
				
                typeof callback === 'function' ? callback.call(data) : '';
            });
        });
    }
    mw.del_current_page = function (a, callback) {
        mw.tools.confirm("<?php _e("Are you sure you want to delete this"); ?>", function () {
            var arr = (a.constructor === [].constructor) ? a : [a];
            var obj = {ids: arr}
            $.post(mw.settings.site_url + "api/content/delete", obj, function (data) {
                mw.notification.warning("<?php _e('Content was sent to Trash'); ?>.");
                typeof callback === 'function' ? callback.call(data) : '';
            });
        });
    }

    mw.adm_cont_type_change_holder_event = function (el) {
        mw.tools.confirm("<?php _e("Are you sure you want to change the content type"); ?>? <?php _e("Please consider the documentation for more info"); ?>.", function () {
            var root = mwd.querySelector('#<?php print $params['id']; ?>');
            var form = mw.tools.firstParentWithClass(root, 'mw_admin_edit_content_form');
            var ctype = $(el).val()
            if (form != undefined && form.querySelector('input[name="content_type"]') != null) {
                form.querySelector('input[name="content_type"]').value = ctype;
            }
        });
    }
    mw.adm_cont_subtype_change_holder_event = function (el) {
        mw.tools.confirm("<?php _e("Are you sure you want to change the content subtype"); ?>? <?php _e("Please consider the documentation for more info"); ?>.", function () {
            var root = mwd.querySelector('#<?php print $params['id']; ?>');
            var form = mw.tools.firstParentWithClass(root, 'mw_admin_edit_content_form');
            var ctype = $(el).val();
            if (form != undefined && form.querySelector('input[name="subtype"]') != null) {
                form.querySelector('input[name="subtype"]').value = ctype
            }
        });
    }


</script>

<div class="mw-ui-field-holder">
    <label class="mw-ui-label">
        <?php _e("Description"); ?>
        <small class="mw-help mw-help-right-bottom" data-help="Short description for yor content.">(?)</small>
    </label>
    <textarea
        class="mw-ui-field" name="description"
        placeholder="<?php _e("Describe your page in short"); ?>"><?php if ($data['description'] != '') print ($data['description'])?></textarea>
</div>
<div class="mw-ui-field-holder">
    <label class="mw-ui-label">
        <?php _e("Meta Title"); ?>
        <small class="mw-help"
               data-help="Title for this <?php print $data['content_type'] ?> that will appear on the search engines on social networks.">
            (?)
        </small>
    </label>
    <textarea class="mw-ui-field" name="content_meta_title"
              placeholder="<?php _e("Title to appear on the search engines results page"); ?>."><?php if (isset($data['content_meta_title']) and $data['content_meta_title'] != '') print ($data['content_meta_title'])?></textarea>
</div>
<div class="mw-ui-field-holder">
    <label class="mw-ui-label">
        <?php _e("Meta Keywords"); ?>
        <small class="mw-help"
               data-help="Keywords for this <?php print $data['content_type'] ?> that will help the search engines to find it. Ex: ipad, book, tutorial">
            (?)
        </small>
    </label>
    <textarea class="mw-ui-field" name="content_meta_keywords"
              placeholder="<?php _e("Type keywords that describe your content - Example: Blog, Online News, Phones for Sale etc"); ?>."><?php if (isset($data['content_meta_keywords']) and $data['content_meta_keywords'] != '') print ($data['content_meta_keywords'])?></textarea>
</div>
<div class="mw_clear vSpace"></div>
<?php if ($show_page_settings != false): ?>
    <div class="mw-ui-check-selector">
        <div class="mw-ui-label">
            <?php _e("Is Home"); ?>
            <small class="mw-help" data-help="<?php _e("If yes this page will be your Home"); ?>">(?)</small>
        </div>
        <label class="mw-ui-check">
            <input name="is_home" type="radio"
                   value="n" <?php if ('' == trim($data['is_home']) or 'n' == trim($data['is_home'])): ?>   checked="checked"  <?php endif; ?> />
            <span></span><span>
    <?php _e("No"); ?>
    </span></label>
        <label class="mw-ui-check">
            <input name="is_home" type="radio"
                   value="y" <?php if ('y' == trim($data['is_home'])): ?>   checked="checked"  <?php endif; ?> />
            <span></span><span>
    <?php _e("Yes"); ?>
    </span></label>
    </div>
    <div class="mw_clear vSpace"></div>
    <div class="mw-ui-check-selector">
        <div class="mw-ui-label">
            <?php _e("Is Shop"); ?>
            <small class="mw-help" data-help="<?php _e("If yes this page will accept products to be added to it"); ?>">
                (?)
            </small>
        </div>
        <label class="mw-ui-check">
            <input name="is_shop" type="radio"
                   value="n" <?php if ('' == trim($data['is_shop']) or 'n' == trim($data['is_shop'])): ?>   checked="checked"  <?php endif; ?> />
            <span></span><span>
    <?php _e("No"); ?>
    </span></label>
        <label class="mw-ui-check">
            <input name="is_shop" type="radio"
                   value="y" <?php if ('y' == trim($data['is_shop'])): ?>   checked="checked"  <?php endif; ?> />
            <span></span><span>
    <?php _e("Yes"); ?>
    </span></label>
    </div>
    <div class="mw_clear vSpace"></div>
<?php endif; ?>
<div class="mw-ui-check-selector">
    <div class="mw-ui-label">
        <?php _e("Require login"); ?>
        <small class="mw-help"
               data-help="<?php _e("If set to yes - this page will require login from a registered user in order to be opened"); ?>">
            (?)
        </small>
    </div>
    <label class="mw-ui-check">
        <input name="require_login" type="radio"
               value="n" <?php if ('' == trim($data['require_login']) or 'n' == trim($data['require_login'])): ?>   checked="checked"  <?php endif; ?> />
        <span></span><span>
    <?php _e("No"); ?>
    </span></label>
    <label class="mw-ui-check">
        <input name="require_login" type="radio"
               value="y" <?php if ('y' == trim($data['require_login'])): ?>   checked="checked"  <?php endif; ?> />
        <span></span><span>
    <?php _e("Yes"); ?>
    </span></label>
</div>
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
        <small class="mw-help"
               data-help="<?php _e("If set this, the user will be redirected to the new URL when visits the page"); ?>">
            (?)
        </small>
    </label>
    <input name="original_link" class="mw-ui-field" type="text" value="<?php print $data['original_link'] ?>">
</div>
<div class="mw_clear vSpace"></div>
<?php if (isset($data['position'])): ?>
    <input name="position" type="hidden" value="<?php print ($data['position']) ?>"/>
<?php endif; ?>
<?php /* PAGES ONLY  */ ?>
<?php event_trigger('mw_admin_edit_page_advanced_settings', $data); ?>
<?php if (isset($data['id']) and $data['id'] > 0): ?>
    <div class="mw-ui-field-holder"><br/>
        <small>
            <?php _e("Id"); ?>
            : <?php print ($data['id'])?></small>
        <a class="mw-ui-btn mw-ui-btn-small"
           href="javascript:mw.copy_current_page('<?php print ($data['id']) ?>');">Copy</a><a
            class="mw-ui-btn mw-ui-btn-small"
            href="javascript:mw.del_current_page('<?php print ($data['id']) ?>');">Delete</a></div>
<?php endif; ?>
<?php if (isset($data['created_on'])): ?>
    <div class="mw-ui-field-holder">
        <br/>
        <small>
            <?php _e("Created on"); ?>
            : <?php print mw('format')->date($data['created_on'])?></small>
        <?php if (isset($data['updated_on'])): ?>
            <br/>
            <small>
                <?php _e("Updated on"); ?>
                : <?php print mw('format')->date($data['updated_on'])?></small>
        <?php endif; ?>
    </div>
<?php endif; ?>



<?php if (is_array($available_content_types) and !empty($available_content_types)): ?>
    <div class="mw-ui-field-holder"><br/>
        <small>
            <?php _e("Content type"); ?>
            :
        </small>
        <a
            href="javascript:$('.mw_adm_cont_type_change_holder').toggle(); void(0);"> <?php print($data['content_type'])?>
            <span
                class="mw-ui-arr mw-ui-arr-down" style="opacity:0.3"></span> </a>

        <div class="mw_adm_cont_type_change_holder" style="display:none"><em>Warning! Advanced action!<br/>
                Do not change these settings unless you know what you are doing.</em>
            <label class="mw-ui-label">
                <?php _e("Change content type"); ?>
                <small class="mw-help"
                       data-help="Changing the content type to different than '<?php print $data['content_type'] ?>' is advanced action. Please read the documentation and consider not to change the content type">
                    (?)
                </small>
            </label>
            <select class="mw-ui-field" name="change_content_type"
                    onchange="mw.adm_cont_type_change_holder_event(this)">
                <?php foreach ($available_content_types as $item): ?>
                    <option
                        value="<?php print $item['content_type']; ?>"  <?php if ($item['content_type'] == trim($data['content_type'])): ?>   selected="selected"  <?php endif; ?>><?php print $item['content_type'];  ?></option>
                <?php endforeach; ?>
            </select>
            <label class="mw-ui-label">
                <?php _e("Change content sub type"); ?>
                <small class="mw-help"
                       data-help="Changing the content subtype to different than '<?php print $data['subtype'] ?>' is advanced action. Please read the documentation and consider not to change the content type">
                    (?)
                </small>
            </label>
            <select class="mw-ui-field" name="change_contentsub_type"
                    onchange="mw.adm_cont_subtype_change_holder_event(this)">
                <?php foreach ($available_content_subtypes as $item): ?>
                    <option
                        value="<?php print $item['subtype']; ?>"  <?php if ($item['subtype'] == trim($data['subtype'])): ?>   selected="selected"  <?php endif; ?>><?php print $item['subtype'];  ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
<?php endif; ?>
<?php /* PRODUCTS ONLY  */ ?>
