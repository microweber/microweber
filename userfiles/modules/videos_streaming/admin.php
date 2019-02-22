<?php only_admin_access() ?>
<?php

$settings = get_option('settings', $params['id']);

$defaults = array(
    'video_id' => '',
    'video_title' => '',
    'video_description' => '',
    'video_order' => '',
    'video_file' => ''
);

$json = json_decode($settings, true);

if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}
?>

<script>
    mw.lib.require('font_awesome5');
</script>

<script>

    videosStreaming = {
        init: function (item) {
            $(item.querySelectorAll('input[type="text"], textarea')).bind('keyup', function () {
                mw.on.stopWriting(this, function () {
                    videosStreaming.save();
                });
            });
            var up = mw.uploader({
                filetypes: '*',
                element: item.querySelector('.video-file-up')
            });
            $(up).bind('FileUploaded', function (event, data) {
                item.querySelector('.video-file').value = data.src
                item.querySelector('.js-videostreaming-file-preview').src = data.src
                videosStreaming.save();
            });
        },

        collect: function () {
            var data = {}, all = mwd.querySelectorAll('.video-streaming-setting-item'), l = all.length, i = 0;
            for (; i < l; i++) {
                var item = all[i];
                data[i] = {};
                data[i]['video_title'] = item.querySelector('.video-title').value;
                data[i]['video_id'] = item.querySelector('.video-id').value;
                data[i]['video_order'] = item.querySelector('.video-order').value;
                data[i]['video_file'] = item.querySelector('.video-file').value;
            }
            return data;
        },
        save: function () {
            mw.$('#settingsfield').val(JSON.stringify(videosStreaming.collect())).trigger('change');
        },


        create: function () {
            var last = $('.video-streaming-setting-item:first');
            var html = last.html();
            var item = mwd.createElement('div');
            item.className = last.attr("class");
            item.innerHTML = html;
            $(item.querySelectorAll('input')).val('');
            $(item.querySelectorAll('textarea')).val('');
            $(item.querySelectorAll('.mw-uploader')).remove();
            $(item.querySelectorAll('img')).attr('src', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mN8/h8AAtMB6KonQukAAAAASUVORK5CYII=');
            last.before(item);
            videosStreaming.init(item);
        },

        remove: function (element) {
            if (confirm("<?php _e('Are you sure?'); ?>")) {
                $(element).remove();
                videosStreaming.save();
            }
        },


    }

    $(document).ready(function () {
        var all = mwd.querySelectorAll('.video-streaming-setting-item'), l = all.length, i = 0;
        for (; i < l; i++) {
            if (!!all[i].prepared) continue;
            var item = all[i];
            item.prepared = true;
            videosStreaming.init(item);
        }
    });

    $(document).ready(function () {
        $('#video-streaming-settings').sortable({
            handle: '.mw-ui-box-header',
            items: ".video-streaming-setting-item",

            update: function (event, ui) {
                videosStreaming.save();
            }
        });
    });

</script>


<style scoped="scoped">
    #video-streaming-settings {
        clear: both;
    }

    #video-streaming-settings > div {
        margin-top: 15px;
        clear: both;
    }

    .add-new-button {
        text-align: right;
    }

    .mw-ui-box-header {
        cursor: -moz-grab;
        cursor: -webkit-grab;
        cursor: grab;
    }

    .remove-question {
        color: #f12b1c;
    }
</style>

<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-navicon-round"></i> List of Videos
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-video-streaming-settings">
                <input type="hidden" class="mw_option_field" name="settings" id="settingsfield"/>

                <div class="mw-ui-field-holder add-new-button">
                    <a class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded" href="javascript:videosStreaming.create()"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></a>
                </div>

                <div id="video-streaming-settings">
                    <?php
                    $count = 0;
                    function sort_videos($a, $b)
                    {
                        if ($a['video_order'] == $b['video_order']) return 0;
                        return ($a['video_order'] < $b['video_order']) ? -1 : 1;
                    }

                    //usort($json, "sort_videos");
                    foreach ($json as $video) {
                        $count++;
                        ?>
                        <div class="mw-ui-box  video-streaming-setting-item" id="video-streaming-setting-item-<?php print $count; ?>">
                            <div class="mw-ui-box-header"><i class="mw-icon-drag"></i> <?php print _e('Move'); ?> <a class="pull-right" href="javascript:videosStreaming.remove('#video-streaming-setting-item-<?php print $count; ?>');"><i class="mw-icon-close"></i></a></div>
                            <div class="mw-ui-box-content mw-accordion-content">
                                <div class="mw-ui-field-holder">
                                    <label class="mw-ui-label"><?php _e('Video Image'); ?></label>

                                    <div class="mw-flex-row">
                                        <div class="mw-flex-col-xs-5">
                                            <img src="<?php print thumbnail(array_get($video, 'video_file'), 75, 75); ?>" style="width: 75px; height: 75px;" class="js-videostreaming-file-preview"/>
                                        </div>
                                        <div class="mw-flex-col-xs-7">
                                            <input type="hidden" class="mw-ui-field video-file" value="<?php print array_get($video, 'file'); ?>"/>
                                            <span class="mw-ui-btn video-file-up">
                                                <span class="ico iupload"></span>
                                                <span><?php _e('Upload image'); ?></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mw-ui-field-holder">
                                    <div class="mw-ui-row-nodrop">
                                        <div class="mw-ui-col">
                                            <div class="mw-ui-col-container">
                                                <label class="mw-ui-label"><?php _e('Video Title'); ?></label>
                                                <input type="text" class="mw-ui-field video-title w100 " value="<?php print array_get($video, 'video_title'); ?>">
                                            </div>
                                        </div>

                                        <div class="mw-ui-col">
                                            <div class="mw-ui-col-container">
                                                <label class="mw-ui-label"><?php _e('Vimeo ID'); ?></label>
                                                <input type="text" class="mw-ui-field video-id w100" value="<?php print array_get($video, 'video_id'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mw-ui-field-holder hidden">
                                    <div class="mw-ui-row-nodrop">
                                        <div class="mw-ui-col">
                                            <div class="mw-ui-col-container">
                                                <label class="mw-ui-label"><?php _e('Order'); ?></label>
                                                <input type="text" class="mw-ui-field video-order w100" value="<?php print array_get($video, 'video_order'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!-- Settings Content - End -->
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>