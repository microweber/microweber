<?php

if (isset($params['rel'])) {
    $params['rel_type'] = $params['rel'];
}

$is_post = false;
$the_content_id = false;
if (defined('POST_ID') and POST_ID != false) {
    $is_post = true;
    $the_content_id = POST_ID;
} else if (defined('PAGE_ID') and PAGE_ID != false) {
    $the_content_id = PAGE_ID;
}

$for = $mod_name = $config['module'];
$for_module_id = $mod_id = $params['id'];


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


$use_from_post = get_option('data-use-from-post', $params['id']) == 'y';
$use_from_post_forced = false;

if (isset($params['rel_type'])) {
    if ((trim($params['rel_type']) == 'page') or (trim($params['rel_type']) == 'content') or ((trim($params['rel_type']) == 'post') or trim($params['rel_type']) == 'post') or trim($params['rel_type']) == 'post') {
        $use_from_post = true;
        $use_from_post_forced = 1;
        unset($params['rel_type']);
    }
}
if (isset($params['content-id']) and $params['content-id'] != 0) {
    $use_from_post = true;
    $use_from_post_forced = 1;

} else if ($use_from_post == true) {
    if (POST_ID != false) {
        $params['content-id'] = POST_ID;
    } else {
        $params['content-id'] = intval(PAGE_ID);
    }
}

if (isset($params['content-id'])) {
    $for_module_id = $for_id = $params['content-id'];
    $for = 'content';
} else {
    $for_module_id = $for_id = $params['id'];
    $for = 'modules';
}
if (!isset($quick_add)) {
    $quick_add = false;
}
if (isset($params['quick-add'])) {
    $quick_add = $params['quick-add'];
}

$rand = uniqid();
?>


<script type="text/javascript">
//    mw.require('options.js');
</script>
<script type="text/javascript">

    mw.module_pictures_upload_time = null;
    __mw_pics_save_msg = function () {


        if (mw.notification != undefined) {
            mw.notification.success('Picture settings are saved!');
        }

        var pics_from_post = $('#mw-use-post-pics:checked').val();
        if (pics_from_post != undefined && pics_from_post == 'y') {
            $("#mw-pics-list-live-ed").attr('for', 'content');
            $("#mw-pics-list-live-ed").attr('for-id', '<?php print $the_content_id ?>');

        } else {
            $("#mw-pics-list-live-ed").attr('for', 'modules');
            $("#mw-pics-list-live-ed").attr('for-id', '<?php print $mod_id ?>');
        }

        //  mw.reload_module_parent("#<?php print $params['id'] ?>");

        mw.reload_module("#<?php print $params['id'] ?>");

        clearTimeout(mw.module_pictures_upload_time)
        mw.module_pictures_upload_time = setTimeout(function () {
            mw.reload_module_parent("pictures");
            mw.reload_module("#mw-pics-list-live-ed");


        }, 1500)


    }

    $(document).ready(function () {

        mw.options.form('#mw-pic-scope', __mw_pics_save_msg);
        mw.tabs({
            tabs: '.tab',
            nav: '.mw-ui-btn-nav-tabs a'
        });
    });
</script>
<div class="pictures-admin-container">
    <?php if ($quick_add == false): ?>
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e("My pictures"); ?></a>
            <?php if ($quick_add == false): ?>
                <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>

    <div class="tab-content py-3">
        <div class="tab-pane fade show active" id="list">
            <?php if ($is_post): ?>
                <?php if ($quick_add == false and $use_from_post_forced == false): ?>
                    <div class="form-group" id="mw-pic-scope">
                        <table width="100%">
                            <tr>
                                <td width="50%">
                                    <div class="custom-control custom-checkbox" id="mw-use-post-pics-scope" >
                                        <input autocomplete="off"  type="checkbox" reload="#mw-pics-list-live-ed" id="mw-use-post-pics" name="data-use-from-post" value="y" class="mw_option_field custom-control-input" <?php if ($use_from_post): ?>   checked="checked"  <?php endif; ?> />
                                        <label class="custom-control-label" for="mw-use-post-pics"><?php _e("Use pictures from post"); ?></label>
                                    </div>
                                </td>
                                <td width="50%" align="right">
                                    <div class="pull-right">
                                        <module id="edit-post-gallery-main-source-selector-holder" type="pictures/admin_upload_pic_source_selector" />
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <module type="pictures/admin_backend" for="<?php print $for ?>" for-id="<?php print $for_id ?>" id="mw-pics-list-live-ed"/>
        </div>

        <?php if ($quick_add == false): ?>
            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
                <module type="settings/list" for_module="<?php print $config['module'] ?>" for_module_id="<?php print $params['id'] ?>">
            </div>
        <?php endif; ?>
    </div>
</div>



