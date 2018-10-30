<?php only_admin_access() ?>

<script>mw.lib.require('font_awesome5');</script>

<script type="text/javascript">
    mw.require("<?php print mw_includes_url(); ?>css/wysiwyg.css");
    mw.require('icon_selector.js')
</script>

<?php

$settings = get_option('settings', $params['id']);

if ($settings == false) {
    if (isset($params['settings'])) {
        $settings = $params['settings'];
    }
}

$defaults = array(
    'title' => '',
    'id' => 'tab-' . uniqid(),
    'icon' => ''
);

$json = json_decode($settings, true);

if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}

?>

<script>

    tabs = {
        init: function (item) {
            $(item.querySelectorAll('input[type="text"],textarea')).bind('keyup', function () {
                mw.on.stopWriting(this, function () {
                    tabs.save();
                });
            });

            $('.tab-icon').on("change", function (e, el) {
                tabs.save();
            });
            $('.tab-icon').on("click", function (e, el) {
                el = $(this)[0];
                mw.iconSelector._activeElement = el;
                mw.iconSelector.settingsUI(true);
            });


        },

        collect: function () {
            var data = {}, all = mwd.querySelectorAll('.tab-setting-item'), l = all.length, i = 0;
            for (; i < l; i++) {
                var item = all[i];
                data[i] = {};
                data[i]['title'] = item.querySelector('.tab-title').value;
                data[i]['icon'] = item.querySelector('.tab-icon').value;
                data[i]['id'] = item.querySelector('.tab-id').value;
            }
            return data;
        },
        save: function () {
            mw.$('#settingsfield').val(JSON.stringify(tabs.collect())).trigger('change');
        },


        create: function () {
            var last = $('.tab-setting-item:last');
            var html = last.html();
            var item = mwd.createElement('div');
            item.className = last.attr("class");
            item.innerHTML = html;
            $(item.querySelectorAll('input')).val('');
            $(item.querySelectorAll('[name=id]')).val('tab-' + mw.random());

            $(item.querySelectorAll('.mw-uploader')).remove();
            last.after(item);
            tabs.init(item);
        },

        remove: function (element) {
            var txt;
            var r = confirm("Are you sure?");
            if (r == true) {
                $(element).remove();
                tabs.save();
            }
        },


    }


    $(document).ready(function () {
        var all = mwd.querySelectorAll('.tab-setting-item'), l = all.length, i = 0;
        for (; i < l; i++) {
            if (!!all[i].prepared) continue;
            var item = all[i];
            item.prepared = true;
            tabs.init(item);
        }
    });


    $(document).ready(function () {
        if (typeof(window.parent.mw) != 'undefined') {
            window.parent.mw.drag.save();
        }

        $('#tab-settings').sortable({
            handle: '.mw-ui-box-header',
            items: ".tab-setting-item",

            update: function (event, ui) {
                tabs.save();
            }
        });
    });

</script>

<style>
    .mw-ui-box-header {
        cursor: -moz-grab;
        cursor: -webkit-grab;
        cursor: grab;
    }
</style>


<div class="mw-accordion">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> Settings
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-accordion-settings">
                <input type="hidden" class="mw_option_field" name="settings" id="settingsfield"/>

                <div class="mw-ui-field-holder add-new-button text-right m-b-10">
                    <a class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-rounded" href="javascript:tabs.create()"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></a>
                </div>


                <div id="tab-settings">
                    <?php
                    $count = 0;
                    foreach ($json as $slide) {
                        $count++;

                        if (!isset($slide['id'])) {
                            $slide['id'] = 'tab-' . uniqid();
                        }
                        ?>
                        <div class="mw-ui-box tab-setting-item m-b-20" id="tab-setting-item-<?php print $count; ?>">
                            <div class="mw-ui-box-header"><i class="mw-icon-drag"></i> <?php print _e('Move'); ?> <a class="pull-right" href="javascript:tabs.remove('#tab-setting-item-<?php print $count; ?>');"><i class="mw-icon-close"></i></a></div>
                            <div class="mw-ui-box-content mw-accordion-content">
                                <div class="mw-ui-field-holder">
                                    <label class="mw-ui-label">Title</label>
                                    <input type="text" class="mw-ui-field tab-title mw-full-width" value="<?php print $slide['title']; ?>">
                                </div>

                                <div class="mw-ui-field-holder">
                                    <label class="mw-ui-label">Icon</label>
                                    <input type="hidden" name="id" class="tab-id" value="<?php print $slide['id']; ?>">


                                    <script>
                                        $(document).ready(function () {
                                            mw.iconSelector.iconDropdown("#icon-picker", {
                                                onchange: function (iconClass) {
                                                    $('.tab-icon').val(iconClass).trigger('change')
                                                },
                                                mode: 'absolute',
                                                value: '<?php print $slide['icon']; ?>'
                                            });
                                            $("#icon-picker input").val($('.tab-icon').val())
                                        })
                                    </script>
                                    <textarea class="tab-icon" style="display: none"><?php print $slide['icon']; ?></textarea>
                                    <div id="icon-picker"></div>
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