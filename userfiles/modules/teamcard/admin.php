<?php only_admin_access() ?>
<?php

$settings = get_option('settings', $params['id']);

$defaults = array(
    'name' => '',
    'role' => '',
    'bio' => '',
    'file' => ''
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

    teamcards = {
        init: function (item) {
            $(item.querySelectorAll('input[type="text"], textarea')).bind('keyup', function () {
                mw.on.stopWriting(this, function () {
                    teamcards.save();
                });
            });
            var up = mw.uploader({
                filetypes: '*',
                element: item.querySelector('.teamcard-file-up')
            });
            $(up).bind('FileUploaded', function (event, data) {
                item.querySelector('.teamcard-file').value = data.src
                item.querySelector('.js-teamcard-file-preview').src = data.src
                teamcards.save();
            });
        },

        collect: function () {
            var data = {}, all = mwd.querySelectorAll('.teamcard-setting-item'), l = all.length, i = 0;
            for (; i < l; i++) {
                var item = all[i];
                data[i] = {};
                data[i]['name'] = item.querySelector('.teamcard-name').value;
                data[i]['role'] = item.querySelector('.teamcard-role').value;
                data[i]['bio'] = item.querySelector('.teamcard-bio').value;
                data[i]['file'] = item.querySelector('.teamcard-file').value;

            }
            return data;
        },
        save: function () {
            mw.$('#settingsfield').val(JSON.stringify(teamcards.collect())).trigger('change');
        },


        create: function () {
            var last = $('.teamcard-setting-item:last');
            var html = last.html();
            var item = mwd.createElement('div');
            item.className = last.attr("class");
            item.innerHTML = html;
            $(item.querySelectorAll('input')).val('');
            $(item.querySelectorAll('textarea')).val('');
            $(item.querySelectorAll('.mw-uploader')).remove();
            last.before(item);
            teamcards.init(item);
        },

        remove: function (element) {
            var txt;
            var r = confirm("<?php _e('Are you sure?'); ?>");
            if (r == true) {
                $(element).remove();
                teamcards.save();
            }
        },


    }

    $(document).ready(function () {
        var all = mwd.querySelectorAll('.teamcard-setting-item'), l = all.length, i = 0;
        for (; i < l; i++) {
            if (!!all[i].prepared) continue;
            var item = all[i];
            item.prepared = true;
            teamcards.init(item);
        }
    });

    $(document).ready(function () {
        $('#teamcard-settings').sortable({
            handle: '.mw-ui-box-header',
            items: ".teamcard-setting-item",

            update: function (event, ui) {
                teamcards.save();
            }
        });
    });

</script>


<style scoped="scoped">
    #teamcard-settings {
        clear: both;
    }

    #teamcard-settings > div {
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

<div class="mw-accordion mw-accordion-window-height">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-navicon-round"></i> List of Members
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-teamcard-settings">
                <input type="hidden" class="mw_option_field" name="settings" id="settingsfield"/>

                <div class="mw-ui-field-holder add-new-button">
                    <a class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded" href="javascript:teamcards.create()"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></a>
                </div>

                <div id="teamcard-settings">
                    <?php
                    $count = 0;
                    foreach ($json as $slide) {
                        $count++;
                        ?>
                        <div class="mw-ui-box  teamcard-setting-item" id="teamcard-setting-item-<?php print $count; ?>">
                            <div class="mw-ui-box-header"><i class="mw-icon-drag"></i> <?php print _e('Move'); ?> <a class="pull-right" href="javascript:teamcards.remove('#teamcard-setting-item-<?php print $count; ?>');"><i class="mw-icon-close"></i></a></div>
                            <div class="mw-ui-box-content mw-accordion-content">
                                <div class="mw-ui-field-holder">
                                    <label class="mw-ui-label"><?php _e('Member Image'); ?></label>

                                    <div class="mw-flex-row">
                                        <div class="mw-flex-col-xs-5">
                                            <img src="<?php print thumbnail(array_get($slide, 'file'), 75, 75); ?>" style="width: 75px; height: 75px;" class="js-teamcard-file-preview"/>
                                        </div>
                                        <div class="mw-flex-col-xs-7">
                                            <input type="hidden" class="mw-ui-field teamcard-file" value="<?php print array_get($slide, 'file'); ?>"/>
                                            <span class="mw-ui-btn teamcard-file-up">
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
                                                <label class="mw-ui-label"><?php _e('Name'); ?></label>
                                                <input type="text" class="mw-ui-field teamcard-name w100 " value="<?php print array_get($slide, 'name'); ?>">
                                            </div>
                                        </div>
                                        <div class="mw-ui-col">
                                            <div class="mw-ui-col-container">
                                                <label class="mw-ui-label"><?php _e('Position'); ?></label>
                                                <input type="text" class="mw-ui-field teamcard-role w100" value="<?php print array_get($slide, 'role'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mw-ui-field-holder">
                                    <label class="mw-ui-label"><?php _e('Biography'); ?></label>
                                    <textarea class="mw-ui-field teamcard-bio w100"><?php print array_get($slide, 'bio'); ?></textarea>
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
                <i class="mw-icon-beaker"></i> Templates
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>