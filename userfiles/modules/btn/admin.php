<?php only_admin_access(); ?>

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
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <?php
        $style = get_option('button_style', $params['id']);
        $size = get_option('button_size', $params['id']);
        $action = get_option('button_action', $params['id']);

        $onclick = false;
        if (isset($params['button_onclick'])) {
            $onclick = $params['button_onclick'];
        }

        $url = $url_display = get_option('url', $params['id']);
        $popupcontent = get_option('popupcontent', $params['id']);
        $text = get_option('text', $params['id']);
        $url_blank = get_option('url_blank', $params['id']);
        $icon = get_option('icon', $params['id']);


        $link_to_content_by_id = 'content:';
        $link_to_category_by_id = 'category:';


        if (substr($url, 0, strlen($link_to_content_by_id)) === $link_to_content_by_id) {
            $link_to_content_by_id = substr($url, strlen($link_to_content_by_id));
            if ($link_to_content_by_id) {
                $url_display = content_link($link_to_content_by_id);
            }
        } else if (substr($url, 0, strlen($link_to_category_by_id)) === $link_to_category_by_id) {
            $link_to_category_by_id = substr($url, strlen($link_to_category_by_id));

            if ($link_to_category_by_id) {
                $url_display = category_link($link_to_category_by_id);
            }
        }
        ?>

        <style>
            #editor_holder {
                display: none;
            }

            .mw-iframe-editor {
                width: 100%;
                height: 300px;
            }

            #icon-picker ul {
                height: 220px;
            }

            #icon-picker li {
                margin: 5px 0;
                float: left;
                width: 33.333%;
                text-align: center;
            }

            #icon-picker .mw-ui-btn > *:first-child {
                margin-right: 7px;
            }

            #icon-picker input,
            #icon-picker {
                width: 250px;
            }
        </style>

        <script>
            mw.require('icon_selector.js')
            mw.require('wysiwyg.css')
        </script>

        <script>
            launchEditor = function () {
                if (!window.editorLaunched) {
                    editorLaunched = true;
                    PopUpEditor = mw.editor({
                        element: document.getElementById('popupcontent'),
                        hideControls: ['format', 'fontsize']
                    });
                }
            }

            $(document).ready(function () {
                btn_action = function () {
                    var el = mw.$("#action");
                    if (el.val() == 'url') {
                        $("#editor_holder").hide();
                        mw.$("#btn_url_holder").show();
                    } else if (el.val() == 'popup') {
                        $("#editor_holder").show();
                        mw.$("#btn_url_holder").hide();
                        launchEditor();
                    } else {
                        $("#editor_holder").hide();
                        mw.$("#btn_url_holder").hide();
                    }
                }

                btn_action();
                mw.$("#action").change(function () {
                    btn_action();
                });
            });
        </script>

        <div class="module-live-edit-settings module-btn-settings">
            <div class="text-left">
                <div class="form-group">
                    <label class="control-label"><?php _e("Text"); ?></label>
                    <input type="text" name="text" class="mw_option_field form-control" value="<?php print $text; ?>" placeholder="<?php _e("Button"); ?>"/>
                </div>

                <?php if (!$onclick): ?>
                    <div class="form-group">
                        <label class="control-label d-block"><?php _e("Action"); ?></label>
                        <select class="mw_option_field selectpicker" data-width="100%" id="action" name="button_action">
                            <option <?php if ($action == 'url' OR $action == ''): ?>selected<?php endif; ?> value="url"><?php _e("Go to link"); ?></option>
                            <option <?php if ($action == 'popup'): ?>selected<?php endif; ?> value="popup"><?php _e("Open a pop-up window"); ?></option>
                            <option <?php if ($action == 'submit'): ?>selected<?php endif; ?> value="submit"><?php _e("Submit form"); ?></option>
                        </select>
                    </div>
                <?php endif; ?>

                <?php if (!$onclick): ?>
                    <div id="editor_holder" class="form-group">
                        <label class="control-label"><?php _e("Popup content"); ?></label>
                        <textarea class="mw_option_field form-control" name="popupcontent" id="popupcontent"><?php print $popupcontent; ?></textarea>
                    </div>
                <?php endif; ?>

                <?php if (!$onclick): ?>
                    <div class="form-group">
                        <div id="btn_url_holder">
                            <div class="mw-ui-btn-nav">
                                <input type="text" readonly="readonly" disabled="disabled" id="btn-default_url-show" value="<?php print $url_display; ?>" placeholder="<?php _e("Enter URL"); ?>" class="mw_option_field mw-ui-field" style="min-width: 350px"/> <a href="javascript:;" class="mw-ui-btn"><span class="mw-icon-gear"></span></a>
                                <input type="hidden" name="url" id="btn-default_url" value="<?php print $url; ?>" placeholder="<?php _e("Enter URL"); ?>" class="mw_option_field mw-ui-field"/>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!$onclick): ?>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="url_blank" value="y" class="mw_option_field custom-control-input" <?php if ($url_blank == 'y'): ?>checked<?php endif; ?> id="url_blank">
                            <label class="custom-control-label" for="url_blank"><?php _e("Open in new window"); ?></label>
                        </div>
                    </div>
                <?php endif; ?>

                <script>
                    mw.top().require('instruments.js');
                    var pickUrl = function () {
                        var picker = mw.component({
                            url: 'link_editor_v2',
                            options: {
                                target: false,
                                text: false,
                                controllers: 'page, custom, content, section, layout, emial'
                            }
                        });
                        $(picker).on('Result', function (e, ldata) {
                            if (ldata) {
                                var url = ldata.url;
                                var url_display = ldata.url;
                                if (ldata.object) {
                                    if (ldata.object.id) {
                                        if (ldata.object.type && ldata.object.type == 'category') {
                                            url = 'category:' + ldata.object.id;
                                        } else if (ldata.object.content_type) {
                                            url = 'content:' + ldata.object.id;
                                        }
                                    }

                                }

                                if (!url_display) {
                                    url_display = url;
                                }

                                mw.$('#btn-default_url').val(url).trigger('change');
                                mw.$('#btn-default_url-show').val(url_display);
                            }
                        });
                    };

                    $(window).on('load', function () {
                        //mw.$('#btn_url_holder').find('a').on('click', function(){
                        mw.$('#btn_url_holder').on('click', function () {
                            pickUrl();
                        });
                    })

                </script>

                <div class="form-group">
                    <label class="control-label"><?php _e("Select Icon"); ?></label>
                    <script>
                        $(document).ready(function () {
                            mw.iconSelector.iconDropdown("#icon-picker", {
                                onchange: function (iconClass) {
                                    $('[name="icon"]').val(iconClass).trigger('change')
                                },
                                mode: 'absolute',
                                value: '<?php print $icon; ?>'
                            });
                        })
                    </script>
                    <textarea name="icon" class="mw_option_field" style="display: none"><?php print $icon; ?></textarea>
                    <div id="icon-picker"></div>
                </div>

                <div class="form-group">
                    <module type="admin/modules/templates" simple="true"/>
                </div>
            </div>
        </div>
    </div>
</div>
