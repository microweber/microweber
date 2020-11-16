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

    .image-settings.remove-image {
        left: 20px;
        display: none;
    }

    .admin-thumb-item:hover .image-settings {
        opacity: 1;
    }
</style>

<?php
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
            data.src = a;
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
                // mw.reload_module('#' + module_id);
                mw.reload_module('pictures/admin_backend_sortable_pics_list');

                //


                mw.reload_module_parent('pictures');
                if (self !== top && typeof parent.mw === 'object') {
                    parent.mw.reload_module('pictures');
                    mw.reload_module_parent("pictures/admin");
                    if (self !== top && typeof parent.mw === 'object') {
                        parent.mw.reload_module('posts');
                        parent.mw.reload_module('shop/products');
                        parent.mw.reload_module('content', function () {
                            mw.reload_module('#' + module_id);
                            parent.mw.reload_module('pictures');
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
            this.contentWindow.mw.on.hashParam('select-file', function () {
                after_upld(this, 'save', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');

                after_upld(this, 'done', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');
                mw.notification.success('<?php _ejs('The image is added to the gallery') ?>');

                dialog.remove();
            })
        })
    };

    var getMediaImage = function () {
        var dialog = mw.top().tools.moduleFrame('pictures/media_library');
        dialog.title('Media library');
        $(dialog.iframe).on('load', function () {
            this.contentWindow.mw.on.hashParam('select-file', function () {
                after_upld(this, 'save', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');
                after_upld(this, 'done', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');
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


<div class="left m-t-20" id="admin-thumbs-holder-sort-<?php print $rand; ?>">
    <div class="relative post-thumb-uploader m-t-10" id="backend_image_uploader_<?php print $rand?>"></div>

    <div class="admin-thumbs-holder">
        <?php if ($for_id != false) : ?>
            <module type="pictures/admin_backend_sortable_pics_list" for="<?php print $for ?>" for_id="<?php print $for_id ?>"/>
        <?php else: ?>
            <module type="pictures/admin_backend_sortable_pics_list" for="<?php print $for ?>" session_id="<?php print $sid ?>"/>
        <?php endif; ?>
    </div>

    <script>mw.require("files.js", true);</script>
    <script>
        imageConfigDialogInstance = null;
        imageConfigDialog = function (id) {
            var el = mw.$('#admin-thumb-item-' + id + ' .image-options');
            imageConfigDialogInstance = mw.dialog({
                overlay: true,
                content: el.html(),
                template: 'default',
                height: 'auto',

                title: '<?php print _e('Image Settings'); ?>'
            })
        }

        saveOptions = function (id) {
            var data = {};
            var root = $(imageConfigDialogInstance.dialogContainer);
            root.find('input').each(function () {
                data[this.name] = this.value;
            })
            mw.module_pictures.save_options(id, data);
            mw.reload_module('#<?php print $params['id'] ?>');
            mw.reload_module('pictures/admin')
            mw.top().reload_module('pictures')
        }


        deleteSelected = function () {
            mw.module_pictures.del(doselect());
        }
        downloadSelected = function () {
            mw.$(".admin-thumb-item .mw-ui-check input:checked").each(function () {
                var a = $("<a>")
                    .attr("href", $(this).dataset('url'))
                    .attr("download", $(this).dataset('url'))
                    .appendTo("body");

                a[0].click();
                a.remove();
            });

        }
        doselect = function () {
            var final = [];
            var all = mw.$(".admin-thumb-item:visible")

            all.each(function () {
                var check = $('.mw-ui-check input:checked', this);
                if (check.length) {
                    final.push(check[0].value);
                    $(this).addClass('checked')
                } else {
                    $(this).removeClass('checked');

                }
            });

            var allPicker = mw.$('.post-media-select-all-pictures');

            allPicker[final.length !== 0 ? 'addClass' : 'removeClass']('active').find('span').html(final.length);
            allPicker[final.length === all.length ? 'addClass' : 'removeClass']('all-selected');
            mw.$('#post-media-card-header')[final.length === 0 ? 'removeClass' : 'addClass']('active');
            $(".select_actions")[final.length === 0 ? 'removeClass' : 'addClass']('active');
            $(".select_actions_holder").stop()[final.length === 0 ? 'hide' : 'show']();
            return final;
        }
        editImageTags = function (event) {
            var parent = null;
            mw.tools.foreachParents(event.target, function (loop) {

                if (mw.tools.hasClass(this, 'admin-thumb-item')) {
                    parent = this;
                    mw.tools.stopLoop(loop);
                }

            });
            if (parent !== null) {
                $(".image-tags", parent).show()
            }

        }



        selectItems = function (val) {
            if (val === 'all') {
                mw.$(".admin-thumb-item .mw-ui-check input").each(function () {
                    this.checked = true;
                })
            }
            else if (val === 'none') {
                mw.$(".admin-thumb-item .mw-ui-check input").each(function () {
                    this.checked = false;
                })
            }
            doselect()
        }


        $(document).ready(function () {

            var $root = mw.$('#admin-thumbs-holder-sort-<?php print $rand; ?>');

            mw.require('filepicker.js');

            mw._postsImageUploader = new mw.filePicker({
                element: '#backend_image_uploader_<?php print $rand?>',
                nav: 'dropdown',
                footer: false,
                boxed: <?php print isset($params['boxed']) ? $params['boxed'] : 'false'; ?>,
                dropDownTargetMode: 'dialog',
                label: mw.lang('Media'),
                hideHeader: <?php print isset($params['hideHeader']) ? $params['hideHeader'] : 'true'; ?>,
                uploaderType: <?php print isset($params['uploaderType']) ? '"' . $params['uploaderType'] . '"' : '"big"'; ?>,
                multiple: true,
                accept: 'image/*',
            })

            mw._postsImageUploader._thumbpreload = function () {
                var el = mw.$('<div class="admin-thumb-item admin-thumb-item-loading"><span class="mw-post-media-img" style=""></span></div>');
                mw.$($root).find('.admin-thumb-item-uploader-holder').before(el);

                mw.spinner({
                    element: el,
                    size: 32,
                    color: '#4592ff'
                });

            }

            $(mw._postsImageUploader).on('FileAdded', function (e, file) {
                mw._postsImageUploader._thumbpreload()
            });
            $(mw._postsImageUploader).on('FileUploaded', function (e, file) {
                mw.$('.admin-thumb-item-loading:last', $root).remove();
            });
            $(mw._postsImageUploader).on('Result', function (e, res) {
                var url = res.src ? res.src : res;
                after_upld(url, 'Result', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');
                after_upld(url, 'done');
                if (mw._postsImageUploader.settings.hideHeader) {
                    mw._postsImageUploader.hide()
                } else {
                    mw._postsImageUploader.hideUploaders()
                }
            });

            var thumbs = mw.$('.admin-thumb-item', $root);

            if (thumbs.length) {
                if (mw._postsImageUploader.settings.hideHeader) {
                    mw._postsImageUploader.hide()
                } else {
                    mw._postsImageUploader.hideUploaders()
                }
            }


            $(".image-tag-view").remove();
            $(".image-tags").each(function () {
                $(".mw-post-media-img", mw.tools.firstParentWithClass(this, 'admin-thumb-item'))
                    .append('<span class="image-tag-view tip" onclick="editImageTags(event)" data-tip="Tags: ' + this.value + '" ><span class="mw-icon-app-pricetag"></span></span>');
                $(this).on('change', function () {
                    $(".image-tag-view", mw.tools.firstParentWithClass(this, 'admin-thumb-item')).attr('data-tip', 'Tags: ' + this.value);
                });

            });

            doselect()
        });
    </script>
</div>
