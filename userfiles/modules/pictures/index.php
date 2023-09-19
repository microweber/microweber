<script>mw.lib.require('material_icons');</script>




<?php

/*
 * <script>mw.moduleCSS("<?php print modules_url(); ?>pictures/styles.css"); </script>
*/


//template_stack_add(modules_url()."pictures/styles.css");


?>

<?php

$no_img = false;

if (isset($params['content_id'])) {
    $params['rel_id'] = intval($params['content_id']);
    $params['rel_type'] = 'content';
}
if (isset($params['no_img'])){
    $no_img = $params['no_img'];
}


if (isset($params['rel'])){
	$params['rel_type'] = $params['rel'];
}
if (isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'post' and defined('POST_ID')) {
    $params['rel_id'] = POST_ID;
    $params['for'] = 'content';
}
if (isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'page' and defined('PAGE_ID')) {
    $params['rel_id'] = PAGE_ID;
    $params['for'] = 'content';
}
if (isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'content' and defined('CONTENT_ID')) {
    $params['rel_id'] = CONTENT_ID;
    $params['for'] = 'content';
}
$default_images = false;
if (isset($params['images']) and trim(strtolower(($params['images']))) != '') {
    $default_images = explode(',', $params['images']);
    $default_images = array_trim($default_images);
}
if (isset($params['for'])) {
    $for = $params['for'];
} else {
    $for = 'modules';
}

$use_from_post = get_option('data-use-from-post', $params['id']);
if ($use_from_post == 'y') {
    if (POST_ID != false) {
        $params['content-id'] = POST_ID;
    } else {
        $params['content-id'] = PAGE_ID;

    }
} elseif (!isset($params['for']) and get_option('data-use-from-post', $params['id']) == '') {
    $for = 'modules';
    $params['rel_id'] = $params['id'];
} else {
    if (!isset($params['for'])) {
        $for = 'modules';
        $params['rel_id'] = $params['id'];
    } else {
        $for = $params['for'];
    }
}

if (isset($params['content-id']) and $params['content-id'] != '') {
    $params['rel_id'] = intval($params['content-id']);
    $for = 'content';
}
if (isset($params['for-id'])) {
    $params['rel_id'] = trim($params['for-id']);
}
if (!isset($params['rel_id']) or $params['rel_id'] == false) {
    $params['rel_id'] = 0;
}


$handle_empty = false;
if (isset($params['handle_empty']) == true) {
$handle_empty = $params['handle_empty'];
}



if (isset($params['rel_id']) == true) {
    $for_id = $params['rel_id'];
    $get_for_session = false;
    if ($for == 'content' and intval($for_id) == 0 and user_id()) {
        $get_for_session = true;
    }
    if ($get_for_session == false) {
        $data = get_pictures('rel_id=' . $params['rel_id'] . '&for=' . $for);

    } else {
        $sid = mw()->user_manager->session_id();
        $data = get_pictures("rel_id=0&rel_type={$for}&session_id={$sid}");
    }

    if (empty($data)) {
        $isDefaultSettingsApplied = get_option('default_settings_is_applied', $params['id']);
        if (!$isDefaultSettingsApplied) {
            $path = modules_path() . 'pictures' . DS . 'default-images' . DS;
            $default_images = scandir($path);
            $default_images_filenames = array_diff($default_images, array('.', '..'));
            foreach ($default_images_filenames as $filename) {
                $default_image_url = $path . '/' . $filename;
                save_media([
                    'for' => 'modules',
                    'for_id' => $params['id'],
                    'media_type' => 'picture',
                    'src' => $default_image_url,
                ]);
                $data = get_pictures('rel_id=' . $params['rel_id'] . '&for=' . $for);
                save_option('default_settings_is_applied', true, $params['id']);
            }
        }
    }

    if (!is_array($data)) {
        if (is_array($default_images) and !empty($default_images)) {
            $data = array();
            foreach ($default_images as $default_image) {
                $data[] = array('filename' => $default_image);
            }
        } else {
            $no_img = true;
        }
    } else {
        $data = mw()->format->add_slashes_recursive($data);

    }

    $module_template = get_option('data-template', $params['id']);
    if ($module_template == false and isset($params['template'])) {
        $module_template = $params['template'];
    }
    if ($module_template != false) {
        $template_file = module_templates($config['module'], $module_template);
    } else {
        $template_file = module_templates($config['module'], 'default');
    }

    if (isset($no_img) and ($no_img) != false) {
			if($handle_empty != false){
				if(isset($template_file) and is_file($template_file) != false){
				include($template_file);
				}
			} else if(in_live_edit()){

        print "<div class='pictures-module-default-view mw-open-module-settings thumbnail' style='padding: 50px 0; background: #f5f5f5;'><img src='". $config['url_to_module'] . "pictures.svg' style='width: 40px; height: 40px;' /></div>";
		}

    } else if ($no_img != true and !empty($data) and isset($template_file) and is_file($template_file) != false) {
        include($template_file);
    } else {

		$template_file = module_templates($config['module'], 'default');

		if(is_file($template_file) != false){
			include($template_file);
		} else {
			print lnotif("No template found. Please choose template.");
		}


    }
}
?>
<?php if (isset($params['ondrop'])): ?>
    <script>
        var _this = document.getElementById('<?php print $params['id']; ?>');
        var _edit = mw.tools.firstParentWithClass(_this, 'edit');
        var rel = mw.tools.mwattr(_edit, 'rel_type');
        var field = mw.tools.mwattr(_edit, 'field');
        var is = (!!rel && !!field) && ( (rel == 'content' || rel == 'page' || rel == 'post') && field == 'content' );
        if (is && (_edit.querySelector('.module[data-type="pictures"][content-id]') === null) && (_edit.querySelector('.module[data-type="pictures"][rel="content"]') === null)) {
            if (is && (_edit.querySelector('.module[type="pictures"][content-id]') === null) && (_edit.querySelector('.module[type="pictures"][rel="content"]') === null)) {
                $(_this).attr('content-id', "<?php print CONTENT_ID ?>");
                mw.reload_module(_this);
            }
        }
    </script>
<?php endif; ?>
