<?php must_have_access() ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php _e($module_info['name']); ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <?php

        $settings = get_module_option('settings', $params['id']);

        $defaults = array(
            'name' => '',
            'role' => '',
            'bio' => '',
            'file' => '',
            'id' => $params['id'] . uniqid(),
        );

        $json = json_decode($settings, true);

        if (isset($json) == false or count($json) == 0) {
            $json = array(0 => $defaults);
        }
        ?>

        <script>

            teamcards = {
                init: function (item) {
                    $(item.querySelectorAll('input[type="text"], textarea')).on('keyup', function () {
                        mw.on.stopWriting(this, function () {
                            teamcards.save();
                        });
                    });
                    var up = mw.uploader({
                        filetypes: '*',
                        element: item.querySelector('.teamcard-file-up')
                    });
                    $(up).on('FileUploaded', function (event, data) {
                        item.querySelector('.teamcard-file').value = data.src
                        item.querySelector('.js-teamcard-file-preview').src = data.src
                        teamcards.save();
                    });
                },

                collect: function () {
                    var data = {}, all = document.querySelectorAll('.teamcard-setting-item'), l = all.length, i = 0;
                    for (; i < l; i++) {
                        var item = all[i];
                        data[i] = {};
                        data[i]['name'] = item.querySelector('.teamcard-name').value;
                        data[i]['role'] = item.querySelector('.teamcard-role').value;
                        data[i]['bio'] = item.querySelector('.teamcard-bio').value;
                        data[i]['file'] = item.querySelector('.teamcard-file').value;
                        data[i]['id'] = item.querySelector('.teamcard-id').value || mw.random();
                    }
                    return data;
                },
                save: function () {
                    mw.$('#settingsfield').val(JSON.stringify(teamcards.collect())).trigger('change');
                },


                create: function () {
                    var last = $('.teamcard-setting-item:first');
                    var html = last.html();
                    var item = document.createElement('div');
                    item.className = last.attr("class");
                    item.innerHTML = html;
                    $(item.querySelectorAll('input')).val('');
                    $(item.querySelectorAll('textarea')).val('');
                    $(item.querySelectorAll('.teamcard-id')).val(mw.random());
                    $(item.querySelectorAll('.mw-uploader')).remove();
                    $(item.querySelectorAll('img')).attr('src', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mN8/h8AAtMB6KonQukAAAAASUVORK5CYII=');
                    last.before(item);
                    teamcards.init(item);
                },

                remove: function (element) {
                    if (confirm("<?php _ejs('Are you sure?'); ?>")) {
                        $(element).remove();
                        teamcards.save();
                    }
                },


            }

            $(document).ready(function () {
                var all = document.querySelectorAll('.teamcard-setting-item'), l = all.length, i = 0;
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
            .teamcard-file-up{
                overflow: hidden;
            }
        </style>

<!--        <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs m-1 w-100">-->
<!--            <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">-->
<!--                <a class="btn btn-outline-secondary justify-content-center show active active-info" data-toggle="tab" href="javascript:;"><i class="mw-icon-navicon-round"></i>&nbsp;--><?php //_e('List of Members'); ?><!--</a>-->
<!--                <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="javascript:;"><i class="mw-icon-beaker"></i>&nbsp;--><?php //_e('Templates'); ?><!-- </a>-->
<!--            </nav>-->
<!--        </div>-->

        <div class="mw-modules-tabs">
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
                            <a class="btn btn-success btn-rounded icon-left" href="javascript:teamcards.create()"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></a>
                        </div>

                        <div id="teamcard-settings">
                            <?php
                            $count = 0;
                            foreach ($json as $slide) {
                                $count++;
                                ?>
                                <div class="mw-ui-box teamcard-setting-item" id="teamcard-setting-item-<?php print $count; ?>">
                                    <div class="mw-ui-box-header"><i class="mw-icon-drag"></i> <?php _e('Move'); ?> <a class="pull-right" href="javascript:teamcards.remove('#teamcard-setting-item-<?php print $count; ?>');"><i class="mw-icon-close"></i></a></div>
                                    <div class="mw-ui-box-content mw-accordion-content">
                                        <div class="mw-ui-field-holder">
                                            <label class="control-label"><?php _e('Member Image'); ?></label>

                                            <div class="mw-flex-row">
                                                <div class="mw-flex-col-xs-5">
                                                    <img src="<?php print thumbnail(array_get($slide, 'file'), 75, 75); ?>" style="width: 75px; height: 75px;" class="js-teamcard-file-preview"/>
                                                </div>
                                                <div class="mw-flex-col-xs-7">
                                                    <button value="<?php print array_get($slide, 'file'); ?>" type="button" class="btn btn-outline-primary teamcard-file-up ico iupload" ><?php _e("Upload image"); ?></button>
                                            </span>
                                                </div>
                                                <input type="hidden" class="teamcard-id" value="<?php print array_get($slide, 'id'); ?>">
                                            </div>
                                        </div>

                                        <div class="mw-ui-field-holder">
                                            <div class="mw-ui-row-nodrop">
                                                <div class="mw-ui-col">
                                                    <div class="mw-ui-col-container">
                                                        <label class="control-label"><?php _e('Name'); ?></label>
                                                        <input type="text" class="form-control" value="<?php print array_get($slide, 'name'); ?>">
                                                    </div>
                                                </div>
                                                <div class="mw-ui-col">
                                                    <div class="mw-ui-col-container">
                                                        <label class="control-label"><?php _e('Position'); ?></label>
                                                        <input type="text" class="form-control" value="<?php print array_get($slide, 'role'); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mw-ui-field-holder">
                                            <label class="control-label"><?php _e('Biography'); ?></label>
                                            <textarea class="form-control"><?php print array_get($slide, 'bio'); ?></textarea>
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
                        <i class="mw-icon-beaker"></i> <?php _e('Templates'); ?>
                    </div>
                </div>
                <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                    <module type="admin/modules/templates"/>
                </div>
            </div>
        </div>

    </div>
</div>
