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

<style>
    .admin-thumb-item-uploader-holder {
        display: block;
        position: relative;
        float: left;
        width: 18%;
        height: 110px;
        margin: 0 1% 1%;
        overflow: hidden;
    }

    .admin-thumb-item-uploader-holder:hover .dropable-zone.small-zone button {
        text-decoration: underline;
    }

    .admin-thumb-item-uploader-holder:hover .dropable-zone.small-zone {
        border-color: #4592ff;
        background-color: rgba(69, 146, 255, 0.1);
    }
</style>


<div class="mw-drop-zone" id="admin-thumbs-drop-zone-<?php print $rand; ?>" style="display: none"><?php _e("Drop here"); ?></div>


<script>
    $(document).ready(function () {
        mw.module_pictures.init('#admin-thumbs-holder-sort-<?php print $rand; ?>');

        var uploadHolder = mw.$('#admin-thumb-item-uploader<?php print $rand; ?>');
        mw.require('uploader.js');

        mw._postsImageUploaderSmall = mw.upload({
            element: uploadHolder,
            accept: 'image/*',
            multiple: true,
            dropZone: '#admin-thumbs-drop-zone-<?php print $rand; ?>',
            on: {
                fileUploaded: function (xhr) {
                    mw.module_pictures.after_change();
                },
                fileUploadError: function (xhr) {
                    mw.$('.admin-thumb-item-loading:last').remove();
                    mw.module_pictures.after_change();
                }
            }
        });
        mw._postsImageUploaderSmall.$holder = uploadHolder.parent();
        $(mw._postsImageUploaderSmall).on('FileAdded', function (e, res) {
            mw._postsImageUploader._thumbpreload()
        })

        $(mw._postsImageUploaderSmall).on('FileUploaded', function (e, res) {
            var url = res.src ? res.src : res;
            if (window.after_upld) {
                after_upld(url, 'Result', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');
                after_upld(url, 'done');
                mw._postsImageUploader.hide()
            }
        });

        if (!mw.$('#admin-thumbs-holder-sort-<?php print $rand; ?> .admin-thumb-item').length) {
            uploadHolder.hide();
            if (mw._postsImageUploader) {
                mw._postsImageUploader.show();
            }

        }

        var dropdownData = [
            {value: 'url', title: '<?php _e("Add image from URL"); ?>' },
            {value: 'server', title: '<?php _e("Browse uploaded"); ?>' },
            {value: 'library', title: '<?php _e("Choose from Unsplash"); ?>' },
            {value: 'file', title: '<?php _e("Upload file"); ?>' },
        ];

        var dropdownConfig = {
            placeholder: '<?php _e("Add media from"); ?>',
            data: dropdownData,
            element: '#mw-admin-post-media-type-select',
            size: 'small',
            color: 'default',
            showSelected: false
        }
        var slct = mw.select(dropdownConfig)
        slct.on('change', function (value){
            var val = value[0].value;
            if(val !== 'file') {
                mw._postsImageUploader.displayControllerByType(val)
            }
            slct.displayValue('<?php _e("Add media from"); ?>')
        });
        slct.on('optionsReady', function (options) {
            var file = options.find(function (itm){
                return itm.$value.value === 'file';
            });
            if(file) {
                var up = mw.upload({
                    element: file,
                    accept: 'image/*',
                    multiple: true
                });
                $(up).on('FileAdded', function (e, res) {
                    mw._postsImageUploader._thumbpreload()
                })
                $(up).on('FileUploaded', function (e, res) {
                    var url = res.src ? res.src : res;
                    if (window.after_upld) {
                        after_upld(url, 'Result', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');
                        after_upld(url, 'done');
                        mw._postsImageUploader.hide();
                    }
                });
            }
        });

        var dragTimer;
        $(document).on('dragover', function (e) {
            var dt = e.originalEvent.dataTransfer;
            if (dt.types && (dt.types.indexOf ? dt.types.indexOf('Files') !== -1 : dt.types.contains('Files'))) {
                $("#admin-thumbs-drop-zone-<?php print $rand; ?>").show();
                clearTimeout(dragTimer);
            }
        });
        $(document).on('dragleave', function (e) {
            dragTimer = setTimeout(function () {
                $("#admin-thumbs-drop-zone-<?php print $rand; ?>").hide();
            }, 25);
        });

        $("#admin-thumbs-drop-zone-<?php print $rand; ?>").on('drop', function () {
            $("#admin-thumbs-drop-zone-<?php print $rand; ?>").hide();
        });

        setInterval(function () {
            $('.admin-thumb-item, .admin-thumb-item-placeholder, .admin-thumb-item-uploader-holder, .mw-filepicker-desktop-type-small .mw-uploader-type-holder').each(function () {
                $(this).height($(this).width())
            })
        }, 78)


    });


</script>

<div class="admin-thumbs-holder" id="admin-thumbs-holder-sort-<?php print $rand; ?>">
    <?php if (is_array($media)): ?>
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
                    <input type="checkbox" onchange="doselect()" data-url="<?php print $item['filename']; ?>"
                           value="<?php print $item['id'] ?>"><span></span>
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
                                        <label class="control-label"><?php print $name ?></label>
                                        <input type="text" class="form-control" name="<?php print $ok; ?>" value="<?php print isset($curr[$ok]) ? $curr[$ok] : ''; ?>"/>
                                    </div>
                                <?php } ?>

                                <hr class="thin"/>

                                <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="mw.dialog.get(this).remove()"><?php _e("Cancel"); ?></button>
                                <button type="button" class="btn btn-success btn-sm" onclick="mw.dialog.get(this).result(<?php print $item['id'] ?>)"><?php _e("Update"); ?></button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="admin-thumb-item-uploader-holder">
            <div class="dropable-zone small-zone square-zone">
                <div class="holder">
                    <button type="button" class="btn btn-link"><?php _e("Add file"); ?></button>
                </div>
            </div>
            <div class="admin-thumb-item-uploader" id="admin-thumb-item-uploader<?php print $rand; ?>">

            </div>
        </div>
    <?php endif; ?>


</div>
