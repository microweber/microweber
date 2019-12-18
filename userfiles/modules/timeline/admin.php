<?php only_admin_access() ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<script>mw.lib.require('font_awesome5');</script>

<?php
$settings = get_option('settings', 'timeline');

$defaults = array(
    'icon' => '',
    'date' => '',
    'description' => ''
);

$json = json_decode($settings, true);

if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}
?>

<script>

    timeline = {
        init: function (item) {

            /*            $(item.querySelectorAll('.timeline-setting-item textarea')).each(function () {
             mw.editor({
             element: this,
             height: 320,
             width: '100%',
             addControls: false,
             hideControls: false,
             ready: function () {

             }
             })
             })*/

            $(item.querySelectorAll('input[type="text"], textarea')).bind('change', function () {
                mw.on.stopWriting(this, function () {
                    timeline.save();
                });
            });
        },

        collect: function () {
            var data = {}, all = mwd.querySelectorAll('.timeline-setting-item'), l = all.length, i = 0;
            for (; i < l; i++) {
                var item = all[i];
                data[i] = {};
                data[i]['icon'] = item.querySelector('.timeline-icon').value;
                data[i]['date'] = item.querySelector('.timeline-date').value;
                data[i]['description'] = item.querySelector('.timeline-description').value;

            }
            return data;
        },
        save: function () {
            mw.$('#settingsfield').val(JSON.stringify(timeline.collect())).trigger('change');
        },


        create: function () {
            var last = $('.timeline-setting-item:first');
            var html = last.html();
            var item = mwd.createElement('div');
            item.className = last.attr("class");
            item.innerHTML = html;
            $(item.querySelectorAll('input')).val('');
            $(item.querySelectorAll('textarea')).val('');
            $(item.querySelectorAll('.mw-uploader')).remove();
            $(item.querySelectorAll('.mw-iframe-editor')).remove();
            last.before(item);
            timeline.init(item);
        },

        remove: function (element) {
            var txt;
            var r = confirm("<?php _ejs('Are you sure?'); ?>");
            if (r == true) {
                $(element).remove();
                timeline.save();
            }
        },


    }


    $(document).ready(function () {
        var all = mwd.querySelectorAll('.timeline-setting-item'), l = all.length, i = 0;
        for (; i < l; i++) {
            if (!!all[i].prepared) continue;
            var item = all[i];
            item.prepared = true;
            timeline.init(item);
        }
    });


    $(document).ready(function () {

        $('#timeline-settings').sortable({
            handle: '.mw-ui-box-header',
            items: ".timeline-setting-item",

            update: function (event, ui) {
                timeline.save();
            }
        });
    });
</script>

<style scoped="scoped">
    #timeline-settings {
        clear: both;
    }

    #timeline-settings > div {
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

<div class="admin-side-content">
    <div class="mw-modules-tabs <?php if ($from_live_edit): ?><?php else: ?><?php endif; ?>">
        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-navicon-round"></i> List of Questions
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-timeline-settings">
                    <input type="hidden" class="mw_option_field" name="settings" option-group="timeline" id="settingsfield"/>

                    <div class="mw-ui-field-holder add-new-button">
                        <a class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded" href="javascript:timeline.create()"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></a>
                    </div>

                    <div id="timeline-settings">
                        <?php $count = 0; ?>
                        <?php if ($json and is_array($json)): ?>
                            <?php foreach ($json as $slide): ?>
                                <?php $count++; ?>
                                <script>
                                    $(document).ready(function () {
                                        mw.iconSelector.iconDropdown("#timeline-setting-item-<?php echo $count; ?> #icon-picker", {
                                            onchange: function (iconClass) {
                                                $('#timeline-setting-item-<?php echo $count; ?> [name="icon"]').val(iconClass).trigger('change')
                                            },
                                            mode: 'absolute',
                                            value: '<?php print $slide['icon']; ?>'
                                        });
                                        $("#timeline-setting-item-<?php echo $count; ?> #icon-picker input").val($('#timeline-setting-item-<?php echo $count; ?> [name="icon"]').val())
                                    })
                                </script>
                                <div class="mw-ui-box  timeline-setting-item" id="timeline-setting-item-<?php print $count; ?>">
                                    <div class="mw-ui-box-header"><span class="mw-icon-drag ui-sortable-handle"></span> <a class="pull-right remove-question tip" data-tipposition="left-center" href="javascript:timeline.remove('#timeline-setting-item-<?php print $count; ?>');" title="Remove"><i
                                                    class="mw-icon-close"></i></a></div>
                                    <div class="mw-ui-box-content">
                                        <div class="mw-ui-field-holder">
                                            <label class="mw-ui-label"><?php _e('Icon'); ?></label>

                                            <textarea name="icon" class="mw_option_field timeline-icon" style="display: none"><?php print $slide['icon']; ?></textarea>
                                            <div id="icon-picker"></div>
                                        </div>

                                        <div class="mw-ui-field-holder">
                                            <label class="mw-ui-label"><?php _e('Date'); ?></label>
                                            <input type="text" class="mw-ui-field timeline-date w100 " value="<?php print $slide['date']; ?>">
                                        </div>

                                        <div class="mw-ui-field-holder">
                                            <label class="mw-ui-label"><?php _e('Description'); ?></label>
                                            <textarea class="mw-ui-field timeline-description w100" id="textarea<?php print $count; ?>"><?php print $slide['description']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
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
</div>