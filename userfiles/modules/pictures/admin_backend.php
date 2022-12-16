<script type="text/javascript">
    mw.require('<?php print $config['url_to_module']; ?>pictures.js', true);
</script>

<style>
    .image-settings {
        color: #ffffff;
        font-size: 20px;
        cursor: pointer;
        position: absolute;
        top: 0px;
        right: 3px;
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        transition: all 0.3s;
        opacity: .7;
        z-index: 5;
        text-shadow: 0 0 2px rgba(0, 0, 0, .5);
    }


    .image-settings.settings-img {
        margin-right: 23px
    }

    .admin-thumb-item:hover .image-settings {
        opacity: 1;
    }

    .admin-thumb-item:hover .image-settings.remove-image {
        color: #ff4f52;
    }
</style>

<?php

$use_from_post = get_option('data-use-from-post', $params['id']) == 'y';
$use_from_post_forced = false;

if (!isset($for_id)) {
    $for_id = 0;
}
if (isset($params['for'])) {
    $for = $params['for'];
} else {
    $for = 'modules';
}

if (!isset($for)) {
    $for = 'content';
}

if (isset($params['content_id'])) {
    $for = $params['for'] = 'content';
    $for_id = $for_module_id = $params['content_id'];
} elseif (isset($params['content-id'])) {
    $for_id = $for_module_id = $params['content-id'];
    $for = 'content';
}

$for = mw()->database_manager->assoc_table_name($for);
if ($for == 'post' or $for == 'posts' or $for == 'page' or $for == 'pages') {
    $for = 'content';
} elseif ($for == 'category' or $for == 'categories') {
    $for = 'category';
}

if (isset($params['for-id'])) {
    $for_id = $params['for-id'];
}
if (isset($params['rel-id'])) {
    $for_id = $params['rel-id'];
}
if (isset($params['rel_id'])) {
    $for_id = $params['rel_id'];
}

$rand = uniqid();

if ($for_id != false) {
    $media = get_pictures("rel_id={$for_id}&rel_type={$for}");
} else {
    $sid = mw()->user_manager->session_id();
    $media = get_pictures("rel_id=0&rel_type={$for}&session_id={$sid}");
}
?>

<script>
    mw.require("files.js");
</script>

<script>
    after_upld = function (a, e, f, id, module_id) {
        if (e !== 'done') {
            var data = {};
            data['for'] = f;
            data.src = Array.isArray(a) ? a[0] : a;
            data.media_type = 'picture';

            if (!id) {
                data.for_id = 0;
            } else {
                data.for_id = (id);

            }
            mw.module_pictures.after_upload(data);
        }
        if (e === 'done') {
            setTimeout(function () {
                if (typeof load_iframe_editor === 'function') {
                    load_iframe_editor();
                }
                mw.reload_module_everywhere('pictures/admin_backend_sortable_pics_list');

                mw.reload_module_parent('pictures');
                if (self !== top && typeof parent.mw === 'object') {
                    mw.parent().reload_module('pictures');
                    mw.reload_module_parent("pictures/admin");
                    if (self !== top && typeof parent.mw === 'object') {
                        mw.parent().reload_module_everywhere('posts');
                        mw.parent().reload_module_everywhere('shop/products');
                        mw.parent().reload_module_everywhere('content', function () {
                            mw.reload_module_everywhere('#' + module_id);
                            mw.parent().reload_module_everywhere('pictures');
                        });
                    }
                }

                $('.admin-thumb-item-loading').remove();
                $('[data-type="pictures/admin"]').trigger('change')


            }, 1300);
        }
    }
</script>

<script>
    mw_admin_pictures_upload_browse_existing = function () {
        // var dialog = mw.top().dialogIframe({
        var dialog = mw.top().dialogIframe({
            url: '<?php print site_url() ?>module/?type=files/admin&live_edit=true&remeber_path=true&ui=basic&start_path=media_host_base&from_admin=true&file_types=images&id=mw_admin_pictures_upload_browse_existing_modal<?php print $params['id'] ?>&from_url=<?php print site_url() ?>',
            title: "Browse pictures",
            id: 'mw_admin_pictures_upload_browse_existing_modal<?php print $params['id'] ?>',
            height: 'auto',
            autoHeight: true
        });

        $(dialog.iframe).on('load', function () {
            this.contentWindow.mw.on.hashParam('select-file', function (pval) {
                after_upld(pval, 'save', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');

                after_upld(pval, 'done', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');
                mw.notification.success('<?php _ejs('The image is added to the gallery') ?>');

                dialog.remove();
            })
        })
    };

    var getMediaImage = function () {
        var dialog = mw.top().tools.moduleFrame('pictures/media_library');
        dialog.title('Media library');
        $(dialog.iframe).on('load', function () {
            this.contentWindow.mw.on.hashParam('select-file', function (pval) {
                after_upld(pval, 'save', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');
                after_upld(pval, 'done', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');
                mw.notification.success('<?php _ejs('The image is added to the gallery') ?>');
                dialog.remove();
            });
        });
    };


    var toggleAll = function () {
        var all = mw.$(".admin-thumb-item input[type='checkbox']");

        if (all.length === all.filter(':checked').length) {
            all.each(function () {
                this.checked = false
            })
        } else {
            all.each(function () {
                this.checked = true
            })
        }

        doselect()
    }

</script>
<?php
if (!isset($data["thumbnail"])) {
    $data['thumbnail'] = '';
}
?>


<input name="thumbnail" type="hidden" value="<?php print ($data['thumbnail']) ?>"/>

<span class="post-media-select-all-pictures tip" data-tip="Select all" data-tipposition="top-left" onclick='toggleAll()'>
    <span>0</span>
    picture selected
</span>

<div class="select_actions_holder">
    <div class="select_actions">
        <a href="javascript:;" class="btn btn-sm btn-link text-danger" onclick="deleteSelected()">
            <span><?php _e('Delete') ?> <?php _e('selected') ?></span>
        </a>
        <span>/</span>
        <a href="javascript:;" class="btn btn-sm btn-link" onclick="downloadSelected('none')">
            <span><?php _e('Download') ?> <?php _e('selected') ?></span>
        </a>
    </div>
</div>


<script>
    window.imageOptions = {};
</script>

<div class="pull-right">
    <module id="edit-post-gallery-main-source-selector-holder" type="pictures/admin_upload_pic_source_selector" />
</div>



<div class="left pt-3" style="clear:both" id="admin-thumbs-holder-sort-<?php print $rand; ?>">

    <div class="relative post-thumb-uploader m-t-10" id="backend_image_uploader_<?php print $rand?>"></div>



    <div class="admin-thumbs-holder">
        <?php if ($for_id != false) : ?>
            <module type="pictures/admin_backend_sortable_pics_list" for="<?php print $for ?>" for_id="<?php print $for_id ?>"/>
        <?php else: ?>
            <module type="pictures/admin_backend_sortable_pics_list" for="<?php print $for ?>" session_id="<?php print $sid ?>"/>
        <?php endif; ?>
    </div>
    <script>mw.require("files.js", true);</script>
     <?php include (__DIR__.'/admin_backend_scripts.php')?>
</div>
