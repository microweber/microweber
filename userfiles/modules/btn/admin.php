<?php must_have_access(); ?>

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
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <?php
        $style = get_module_option('button_style', $params['id']);
        $size = get_module_option('button_size', $params['id']);
        $action = get_module_option('button_action', $params['id']);
        $align = get_module_option('align', $params['id']);

        $onclick = false;
        if (isset($params['button_onclick'])) {
            $onclick = $params['button_onclick'];
        }


        $url = $url_display = get_module_option('url', $params['id']);
        $url_to_content_id = get_module_option('url_to_content_id', $params['id']);
        $url_to_category_id = get_module_option('url_to_category_id', $params['id']);

        $popupcontent = get_module_option('popupcontent', $params['id']);
        $text = get_module_option('text', $params['id']);
        $url_blank = get_module_option('url_blank', $params['id']);
        $icon = get_module_option('icon', $params['id']);

        $backgroundColor = get_module_option('backgroundColor', $params['id']);
        $color = get_module_option('color', $params['id']);
        $borderColor = get_module_option('borderColor', $params['id']);
        $borderWidth = get_module_option('borderWidth', $params['id']);
        $borderRadius = get_module_option('borderRadius', $params['id']);
        $shadow = get_module_option('shadow', $params['id']);
        $customSize = get_module_option('customSize', $params['id']);


        $hoverbackgroundColor = get_module_option('hoverbackgroundColor', $params['id']);
        $hovercolor = get_module_option('hovercolor', $params['id']);
        $hoverborderColor = get_module_option('hoverborderColor', $params['id']);



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


            #icon-picker i{

                margin-inline-start: -10px;
                margin-inline-end: 8px;
            }

            .button-custom-design .mw-field{
                width: 100%;
            }

            .shadow-selector > div input:checked~.shadow-example{
                border-color: #111;
            }
            .shadow-selector > div{
                padding: 10px;
                position: relative;
                text-align: center;
            }
            .shadow-selector > div input{
                visibility: hidden;
                position: absolute;
            }
            .shadow-selector > div .shadow-example{
                display: inline-flex;
                width: 90px;
                height: 90px;
                vertical-align: middle;
                border-radius: 5px;
                border: 3px solid transparent;
                transition: border-color .3s;
                align-items: center;
                justify-content: center;
            }

            .shadow-selector {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                padding: 20px 0;
            }
            .shadow-selector > div{
                width: 33%;
            }

            #hover-styles{
                display: none;
            }

            #display-url{
                white-space: nowrap;
                max-width: calc(100% - 20px);
                overflow: hidden;
                text-overflow: ellipsis;
                vertical-align: middle;
                direction: ltr;
            }

        </style>

        <script>
            mw.require('icon_selector.js')
            mw.require('wysiwyg.css');
            mw.require('editor.js');
        </script>

        <script>



            var launchEditor = function () {
                if (!window.editorLaunched) {
                    editorLaunched = true;
                    PopUpEditor = mw.Editor({
                        element: document.getElementById('popupcontent'),
                        mode: 'div',
                        smallEditor: false
                    });
                }
            }

            $(window).on('load', function () {
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
            <div class="text-start text-left">
                <div class="row">
                    <div class="form-group col-6">
                        <label class="control-label"><?php _e("Text"); ?></label>
                        <small class="text-muted d-block mb-3"><?php _e('Change your button text.');?></small>
                        <input type="text" name="text" class="mw_option_field form-control" value="<?php print $text; ?>" placeholder="<?php _e("Button"); ?>"/>
                    </div>

                    <?php if (!$onclick): ?>
                        <div class="form-group col-6">
                            <label class="control-label d-block"><?php _e("Action"); ?></label>
                            <small class="text-muted d-block mb-3"><?php _e('Setup action from dropdown.');?></small>
                            <select class="mw_option_field selectpicker" data-width="100%" id="action" name="button_action">
                                <option <?php if ($action == 'url' OR $action == ''): ?>selected<?php endif; ?> value="url"><?php _e("Go to link"); ?></option>
                                <option <?php if ($action == 'popup'): ?>selected<?php endif; ?> value="popup"><?php _e("Open a pop-up window"); ?></option>
                                <option <?php if ($action == 'submit'): ?>selected<?php endif; ?> value="submit"><?php _e("Submit form"); ?></option>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!$onclick): ?>
                    <div id="editor_holder" class="form-group">
                        <label class="control-label"><?php _e("Popup content"); ?></label>
                        <textarea class="mw_option_field form-control" name="popupcontent" id="popupcontent"><?php print $popupcontent; ?></textarea>
                    </div>
                <?php endif; ?>
                <label class="control-label"><?php _e("Align"); ?></label>
                <div class="form-group">

                    <div class="btn-group btn-group-toggle" data-bs-toggle="buttons" dir="ltr">
                        <label class="btn btn-outline-secondary <?php print $align === 'left' ? 'active' : ''; ?>">
                            <input class="mw_option_field" type="radio" value="left"  name="align"> <i class="mdi mdi-format-horizontal-align-left"></i>
                        </label>
                        <label class="btn btn-outline-secondary <?php print ($align === 'center' ? 'active' : ''); ?>">
                            <input type="radio" class="mw_option_field" value="center"  name="align"> <i class="mdi mdi-format-horizontal-align-center"></i>
                        </label>
                        <label class="btn btn-outline-secondary <?php print $align === 'right' ? 'active' : ''; ?>">
                            <input type="radio" class="mw_option_field" value="right"  name="align"> <i class="mdi mdi-format-horizontal-align-right"></i>
                        </label>
                    </div>
                </div>

                <?php if (!$onclick): ?>

                        <div id="btn_url_holder">
                            <div class="form-group">
                                <label class="control-label"><?php _e("Edit url"); ?></label>
                                <small class="text-muted d-block mb-3"><?php _e('Link settings for your url.');?></small>

                                <input type="hidden" id="btn-default_url-show" value="<?php print $url_display; ?>" placeholder="<?php _e("Enter URL"); ?>" class="mw_option_field" />




                                <button type="button" onclick="pickUrl()" id="display-url-edit-btn" class="btn btn-secondary btn-sm btn-rounded  "><i class="mdi mdi-link"></i> <?php _e("Edit link"); ?></button>


                                <span class="mw-ui-link" id="display-url"><?php print $url_display; ?></span>

                                <input type="hidden" name="url" id="btn-default_url" value="<?php print $url; ?>" placeholder="<?php _e("Enter URL"); ?>" class="mw_option_field mw-ui-field"/>

                                <input type="hidden" name="url_to_content_id" id="btn-default_url_content_id" value="<?php print $url_to_content_id; ?>" placeholder="<?php _e("Enter URL"); ?>" class="mw_option_field mw-ui-field"/>
                                <input type="hidden" name="url_to_category_id" id="btn-default_url_category_id" value="<?php print $url_to_category_id; ?>" placeholder="<?php _e("Enter URL"); ?>" class="mw_option_field mw-ui-field"/>





                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="url_blank" value="y" class="mw_option_field custom-control-input" <?php if ($url_blank == 'y'): ?>checked<?php endif; ?> id="url_blank">
                                    <label class="custom-control-label" for="url_blank"><?php _e("Open in new window"); ?></label>
                                </div>
                            </div>
                        </div>
                <?php endif; ?>



                <script>
                    var pickUrl = function () {


                        var linkEditor = new (mw.top()).LinkEditor({
                            mode: 'dialog',
                            controllers: [
                                { type: 'url', config: {text: false, target: false}},
                                { type: 'page', config: {text: false, target: false} },
                                { type: 'post', config: {text: false, target: false} },
                                { type: 'file', config: {text: false, target: false} },
                                { type: 'email', config: {text: false, target: false} },
                                { type: 'layout', config: {text: false, target: false} },

                            ],
                        });

                        linkEditor.setValue({
                            url: mw.$('#btn-default_url-show').val() || ''
                        })

                        linkEditor.promise().then(function (ldata){
                            if (!ldata) {
                                return
                            }



                            var url = ldata.url;
                            var url_display = ldata.url;
                            var btn_category_id;
                            var btn_content_id;


                            if (ldata.data) {
                                if (ldata.data.id) {
                                    if ((ldata.data.type) && ldata.data.type === 'category') {
                                        //url = 'category:' + ldata.data.id;
                                        btn_category_id = ldata.data.id;
                                    } else if ((ldata.data.type) && ldata.data.type === 'page') {
                                        //url = 'content:' + ldata.data.id;
                                        btn_content_id = ldata.data.id;
                                    } else if (ldata.data.content_type) {
                                        //url = 'content:' + ldata.data.id;
                                        btn_content_id = ldata.data.id;
                                    }
                                }

                            }
                            if (!url_display) {
                                url_display = url;
                            }


                            if(typeof btn_category_id != 'undefined'){
                                mw.$('#btn-default_url').val('').trigger('change');
                                mw.$('#btn-default_url_content_id').val('').trigger('change');
                                mw.$('#btn-default_url_category_id').val(btn_category_id).trigger('change');
                            } else if(typeof btn_content_id != 'undefined'){
                                mw.$('#btn-default_url').val('').trigger('change');
                                mw.$('#btn-default_url_content_id').val(btn_content_id).trigger('change');
                                mw.$('#btn-default_url_category_id').val('').trigger('change');
                            } else {
                                mw.$('#btn-default_url').val(url).trigger('change');
                                mw.$('#btn-default_url_content_id').val('').trigger('change');
                                mw.$('#btn-default_url_category_id').val('').trigger('change');
                            }

                            mw.$('#btn-default_url-show').val(url_display);
                            mw.$('#display-url').html(url_display);
                        })

                    };

                    $(window).on('load', function () {
                        var btnUrl = mw.$('#display-url')
                        btnUrl.on('click', function () {
                            pickUrl();
                        });
                        if(!btnUrl.html()) {
                            btnUrl.html(parent.location.href.split('?')[0])
                        }

                    })

                </script>

                <div class="form-group">
                    <span class="btn btn-primary">
                    <script>
                        $(document).ready(function () {
                            mw.iconLoader().init();
                            var picker = mw.iconPicker({iconOptions: false});
                            picker.target = document.createElement('i');
                            picker.on('select', function (data) {
                                data.render();
                                $('[name="icon"]').val(picker.target.outerHTML).trigger('change')
                                document.querySelector('#icon-picker').innerHTML = (picker.target.outerHTML)
                                picker.dialog('hide');
                            });

                            document.querySelector('#icon-picker').parentNode.onclick = function (){
                                picker.dialog();
                            }

                        })
                        var removeIcon = function () {
                            $('[name="icon"]').val('').trigger('change')
                            document.querySelector('#icon-picker').innerHTML = '';
                        }
                    </script>
                        <span id="icon-picker"><?php print $icon ? $icon : ''; ; ?></span> <?php _e("Select Icon"); ?></span>
                    <button class="btn btn-outline-danger" onclick="removeIcon()"><?php _e("Remove icon"); ?></button>

                </div>
                <textarea name="icon" class="mw_option_field" style="display: none"><?php print $icon; ?></textarea>



                <div class="mw-accordion">
                    <div class="mw-accordion-item">
                        <div class="mw-ui-box-header mw-accordion-title">
                            <i class="mw-icon-gear"></i> <?php _e("Custom design"); ?>
                        </div>
                        <div class="mw-accordion-content" >
                            <div class="mw-ui-box mw-ui-box-content">
                                <label class="control-label"><?php _e("Design"); ?></label>

                                <div class="form-group">
                                    <div class="mw-ui-box mw-ui-box-content">
                                        <div class="button-custom-design">
                                            <script>

                                                $(document).ready(function () {
                                                    Array.from(document.querySelectorAll('.button-color-field')).forEach(function (field) {
                                                        mw.colorPicker({
                                                            element: field,
                                                            position: 'bottom-center',
                                                            value: 'red',
                                                            onchange: function (color) {
                                                                $(field).trigger('change')
                                                            }
                                                        })
                                                    });
                                                    document.getElementById('set-hover-button').addEventListener('click', function () {
                                                        document.getElementById('hover-styles').style.display = 'block';
                                                        this.style.display = 'none';
                                                    });

                                                })

                                            </script>
                                            <label class="control-label"><?php _e('Color'); ?></label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label><?php _e('Background'); ?></label>
                                                    <div class="mw-field mw-field-flat">
                                                        <span class="mw-field-color-indicator"><span
                                                                class="mw-field-color-indicator-display"></span></span>
                                                        <input type="text" placeholder="#ffffff"
                                                               class="form-control button-color-field mw_option_field"
                                                               value="<?php print $backgroundColor ?>"
                                                               autocomplete="off" name="backgroundColor">
                                                    </div>
                                                </div>

                                                <div class="col-4">
                                                    <label><?php _e('Text'); ?></label>
                                                    <div class="mw-field mw-field-flat">
                                                        <span class="mw-field-color-indicator"><span
                                                                class="mw-field-color-indicator-display"></span></span>
                                                        <input type="text" placeholder="#ffffff"
                                                               class="button-color-field mw_option_field" name="color"
                                                               value="<?php print $color ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <label><?php _e('Border'); ?></label>
                                                    <div class="mw-field mw-field-flat">

                                                        <span class="mw-field-color-indicator"><span
                                                                class="mw-field-color-indicator-display"></span></span>
                                                        <input type="text" placeholder="#ffffff"
                                                               class="button-color-field mw_option_field"
                                                               name="borderColor" value="<?php print $borderColor ?>"
                                                               autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <span class="mw-ui-link" id="set-hover-button"><br>Set hover styles</span>

                                            <div id="hover-styles">
                                                <br><br>
                                                <label class="control-label"><?php _e('Hover Color'); ?></label>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <label><?php _e('Background'); ?></label>
                                                        <div class="mw-field mw-field-flat">
                                                            <span class="mw-field-color-indicator"><span
                                                                    class="mw-field-color-indicator-display"></span></span>
                                                            <input type="text" placeholder="#ffffff"
                                                                   class="form-control button-color-field mw_option_field"
                                                                   value="<?php print $hoverbackgroundColor ?>"
                                                                   autocomplete="off" name="hoverbackgroundColor">
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <label><?php _e('Text'); ?></label>
                                                        <div class="mw-field mw-field-flat">
                                                            <span class="mw-field-color-indicator"><span
                                                                    class="mw-field-color-indicator-display"></span></span>
                                                            <input type="text" placeholder="#ffffff"
                                                                   class="button-color-field mw_option_field"
                                                                   name="hovercolor" value="<?php print $hovercolor ?>"
                                                                   autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <label><?php _e('Border'); ?></label>
                                                        <div class="mw-field mw-field-flat">

                                                            <span class="mw-field-color-indicator"><span
                                                                    class="mw-field-color-indicator-display"></span></span>
                                                            <input type="text" placeholder="#ffffff"
                                                                   class="button-color-field mw_option_field"
                                                                   name="hoverborderColor"
                                                                   value="<?php print $hoverborderColor ?>"
                                                                   autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <br>
                                            <br>
                                            <label class="control-label"><?php _e('Size'); ?></label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label><?php _e('Button size'); ?></label>
                                                    <div class="mw-field mw-field-flat">

                                                        <input type="number" min="1" max="40"
                                                               class="form-control mw_option_field" name="customSize"
                                                               value="<?php print $customSize ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <label><?php _e('Border'); ?></label>
                                                    <div class="mw-field mw-field-flat">

                                                        <input type="number" min="0" max="100"
                                                               class="form-control mw_option_field" name="borderWidth"
                                                               value="<?php print $borderWidth ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <label><?php _e('Radius'); ?></label>
                                                    <div class="mw-field mw-field-flat">

                                                        <input type="number" min="0" max="100"
                                                               class="form-control mw_option_field" name="borderRadius"
                                                               value="<?php print $borderRadius ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <br>
                                            <br>

                                            <div class="form-group">
                                                <label class="control-label"><?php _e('Shadow'); ?></label>
                                                <div class=" shadow-selector">
                                                    <?php
                                                    $shadows = array(
                                                        'none;',
                                                        'rgba(149, 157, 165, 0.2) 0px 8px 24px;',
                                                        'rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;',
                                                        'rgba(0, 0, 0, 0.35) 0px 5px 15px;',
                                                        'rgba(0, 0, 0, 0.24) 0px 3px 8px;',
                                                        'rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;',
                                                        'rgba(0, 0, 0, 0.1) 0px 4px 12px;',
                                                        'rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;',
                                                        'rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;',
                                                    );

                                                    foreach ($shadows as $shd) {
                                                        print '<div><input class="mw_option_field" type="radio" name="shadow" ' . ($shadow == $shd ? 'checked="true"' : '') . ' value="' . $shd . '" >   </label><span class="shadow-example" style="box-shadow: ' . $shd . '"><span>' . ($shd == 'none;' ? 'None' : '') . '</span></span> </div>';
                                                    }
                                                    ?>
                                                </div>
                                                <script>
                                                    $(document).ready(function () {

                                                        $('.shadow-selector > div').on('click', function () {
                                                            var node = this.querySelector('input');
                                                            node.checked = true;
                                                            $(node).trigger('change')
                                                        })

                                                    })

                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mw-accordion-item">
                        <div class="mw-ui-box-header mw-accordion-title">
                            <i class="mw-icon-gear"></i> <?php _e('Template'); ?>
                        </div>
                        <div class="mw-accordion-content">
                            <div class="mw-ui-box mw-ui-box-content">
                                <module type="admin/modules/templates" simple="true"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
