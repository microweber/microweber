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
$default_image_options = 'Title, Alt Text, Link, Caption, Author, Source, Tags';
$image_options = (isset($params['image-options']) ? $params['image-options'] : (isset($params['data-image-options']) ? $params['data-image-options'] : $default_image_options));


$temp = explode(',', $image_options);
foreach ($temp as $i) {
    array_push($init_image_options, trim($i));
}


$rand = 'pic-sorter-' . uniqid();

?>

<?php include (__DIR__.'/admin_backend_scripts.php')?>






<script>

    var addImagesToPost = () => {
                var dialog;
                var picker = new mw.filePicker({
                    type: 'images',
                    label: false,
                    autoSelect: false,
                    multiple: true,
                    footer: true,
                    _frameMaxHeight: true,
                    disableFileAutoSelect: false,
                    onResult: async function (res) {


                        var url = res.src ? res.src : res;
                        if(!url) return;



                        var urls;
                        if(!Array.isArray(url)) {
                            urls = [url = url.toString()]
                        } else {
                            urls = url
                        }



                        let i = 0; l = urls.length;

                        for ( ; i < l; i++) {

                           await after_upld(urls[i], 'Result', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');
                        }


                        after_upld(urls, 'done');
                        dialog.remove()

                    }
                });
                dialog = mw.top().dialog({
                    content: picker.root,
                    title: mw.lang('Select image'),
                    footer: false,
                    width: 860
                })

                picker.$cancel.on('click', function(){
                    dialog.remove()
                })


            }

</script>
<script>
    $(document).ready(function () {
        mw.module_pictures.init('#admin-thumbs-holder-sort-<?php print $rand; ?>');

            // $('.admin-thumb-item, .admin-thumb-item-placeholder, .admin-thumb-item-uploader-holder, .mw-filepicker-desktop-type-small .mw-uploader-type-holder').each(function () {
            //     $(this).height($(this).width())
            // })


        // setInterval(function () {
        //     $('.admin-thumb-item, .admin-thumb-item-placeholder, .admin-thumb-item-uploader-holder, .mw-filepicker-desktop-type-small .mw-uploader-type-holder').each(function () {
        //         $(this).height($(this).width())
        //     })
        // }, 78)


    });


</script>



<div class="admin-thumbs-holder" id="admin-thumbs-holder-sort-<?php print $rand; ?>">
    <?php if ($media): ?>
        <?php $default_title = _e("Image title", true); ?>
        <?php foreach ($media as $key => $item): ?>
            <div class="admin-thumb-item admin-thumb-item-<?php print $item['id'] ?>"
                 id="admin-thumb-item-<?php print $item['id'] ?>">


                <?php

                $tn = thumbnail($item['filename'], 480, 480, true); ?>
                <span class="mw-post-media-img" style="background-image: url('<?php print $tn; ?>');"></span>
                <?php if ($key == 0): ?>

                    <div class="featured-image"><?php _e('featured image'); ?></div>

                <?php endif; ?>



                  <span class="mdi mdi-cog image-settings settings-img tip" data-tip="Image Settings"
                          onclick="imageConfigDialog(<?php print $item['id'] ?>)"></span>

                <span class="mdi mdi-delete image-settings remove-image tip" data-tip="Delete Image"
                      onclick="mw.module_pictures.del('<?php print $item['id'] ?>');"></span>

                <label class="mw-ui-check">
                    <input type="checkbox" onchange="doselect()" data-url="<?php print $item['filename']; ?>" value="<?php print $item['id'] ?>"><span></span>
                </label>
                <div class="mw-post-media-img-edit">

                    <template class="image-options">

                        <div id="image-json-options-<?php print  $item['id']; ?>">
                            <div class="image-json-options">
                                <?php
                                $curr = isset($item['image_options']) ? $item['image_options'] : array();
                                foreach ($init_image_options as $name) {
                                    $ok = url_title(strtolower($name));
                                    ?>
                                    <div class="form-group">
                                        <label class="form-label"><?php print $name ?></label>
                                        <input type="text" class="form-control" name="<?php print $ok; ?>" value="<?php print isset($curr[$ok]) ? $curr[$ok] : ''; ?>"/>
                                    </div>
                                <?php } ?>

                                <hr class="thin"/>

                                <div class="d-flex justify-content-between" id="image-json-options-dialog-footer">
                                    <button type="button" class="btn btn-outline-secondary " onclick="mw.dialog.get(this).remove()"><?php _e("Cancel"); ?></button>
                                    <button type="button" class="btn btn-success " onclick="mw.dialog.get(this).result(<?php print $item['id'] ?>)"><?php _e("Update"); ?></button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="admin-thumb-item-uploader-holder">
            <div class="dropzone mw-dropzone" id="post-file-picker-small" onclick="addImagesToPost()">
                <div class="dz-message">
                    <h3 class="dropzone-msg-title"><?php _e("Add file"); ?></h3>
                    <span class="dropzone-msg-desc"><?php _e("Click to upload file"); ?></span>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="admin-thumb-item-uploader-holder">
            <div class="dropzone mw-dropzone" id="post-file-picker" onclick="addImagesToPost()">
                <div class="dz-message">
                    <h3 class="dropzone-msg-title"><?php _e("Add file"); ?></h3>
                    <span class="dropzone-msg-desc"><?php _e("Click to upload file"); ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>


</div>
