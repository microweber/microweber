<?php

only_admin_access();


$for = $for_id = $sess_id = false;



if (isset($params['for'])) {
    $for = $params['for'];
}
if (isset($params['for_id'])) {
    $for_id = $params['for_id'];
}

if (isset($params['session_id'])) {
    $sess_id = $params['session_id'];
}





$media = false;

if ($for_id != false) {
    $media = get_pictures("rel_id={$for_id}&rel_type={$for}");
} else {
    $sid = mw()->user_manager->session_id();
    $media = get_pictures("rel_id=0&rel_type={$for}&session_id={$sid}");
}



$init_image_options = array();
$default_image_options = 'Link, Caption, Author, Source, Tags';
$image_options = (isset($params['image-options']) ? $params['image-options'] : (isset($params['data-image-options']) ? $params['data-image-options'] : $default_image_options));


$temp = explode(',', $image_options);
foreach ($temp as $i) {
    array_push($init_image_options, trim($i));
}



$rand = 'pic-sorter-'.uniqid();

?>

<script type="text/javascript">
    $(document).ready(function () {
        mw.module_pictures.init('#admin-thumbs-holder-sort-<?php print $rand; ?>');
    });
</script>

<div class="admin-thumbs-holder" id="admin-thumbs-holder-sort-<?php print $rand; ?>">
<?php if (is_array($media)): ?>
    <?php $default_title = _e("Image title", true); ?>
    <?php foreach ($media as $key => $item): ?>
        <div class="admin-thumb-item admin-thumb-item-<?php print $item['id'] ?>"
             id="admin-thumb-item-<?php print $item['id'] ?>">


            <?php

            $tn = thumbnail($item['filename'], 200, 200, true); ?>
            <span class="mw-post-media-img" style="background-image: url('<?php print $tn; ?>');"></span>
            <?php  if ($key == 0): ?>

           <div class="featured-image"><?php print _e('featured image'); ?></div>

            <?php  endif; ?>
            <span class="mw-icon-gear image-settings tip" data-tip="Image Settings"
                  onclick="imageConfigDialog(<?php print $item['id'] ?>)"></span>
            <span class="mw-icon-close image-settings remove-image tip" data-tip="Delete Image"
                  onclick="mw.module_pictures.del('<?php print $item['id'] ?>');"></span>
            <label class="mw-ui-check">
                <input type="checkbox" onchange="doselect()" data-url="<?php print $item['filename']; ?>"
                       value="<?php print $item['id'] ?>"><span></span>
            </label>
            <div class="mw-post-media-img-edit">

                <div class="image-options">
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"><?php _e("Alt text"); ?></label>
                        <input class="mw-ui-field w100" autocomplete="off" value="<?php if ($item['title'] !== '') {
                            print $item['title'];
                        } else {
                            print $default_title;
                        } ?>"
                               onkeyup="mw.on.stopWriting(this, function(){mw.module_pictures.save_title('<?php print $item['id'] ?>', this.value);});"
                               onfocus="$(this.parentNode).addClass('active');"
                               onblur="$(this.parentNode).removeClass('active');"
                               name="media-description-<?php print $tn; ?>"/>

                    </div>

                    <div id="image-json-options-<?php print  $item['id']; ?>">
                        <div class="image-json-options">
                            <?php
                            $curr = isset($item['image_options']) ? $item['image_options'] : array();
                            foreach ($init_image_options as $name) {
                                $ok = url_title(strtolower($name));
                                ?>
                                <div class="mw-ui-field-holder">
                                    <label class="mw-ui-label"><?php print $name ?></label>
                                    <input type="text" class="mw-ui-field w100" name="<?php print $ok; ?>"
                                           value="<?php print isset($curr[$ok]) ? $curr[$ok] : ''; ?>"/>
                                </div>
                            <?php } ?>

                            <hr>

                            <span class="mw-ui-btn pull-left" onclick="imageConfigDialogInstance.remove()">Cancel</span>
                            <span class="mw-ui-btn mw-ui-btn-notification pull-right"
                                  onclick="saveOptions(<?php print $item['id'] ?>);imageConfigDialogInstance.remove()">Update</span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>



</div>