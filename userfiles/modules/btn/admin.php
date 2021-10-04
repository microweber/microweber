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

            .shadow-selector{
                max-height: 70vh;
                overflow: auto;

            }
            .shadow-selector > div .shadow-example{
                display: inline-block;
                width: 150px;
                height: 50px;
                vertical-align: middle;
            }
            .shadow-selector > div + div{
                border-top: 1px solid rgba(0, 0, 0, 0.125) ;
            }
            .shadow-selector > div{
                padding: 20px;
                cursor: pointer;
            }

        </style>

        <script>
            mw.require('icon_selector.js')
            mw.require('wysiwyg.css');
        </script>

        <script>

            mw.require('editor.js');

            launchEditor = function () {
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
            <div class="text-start">
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

                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
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
                                <span class="mdi mdi-pencil"></span>
                                <span class="mw-ui-link" id="display-url"><?php print $url_display; ?></span>

                                <input type="hidden" name="url" id="btn-default_url" value="<?php print $url; ?>" placeholder="<?php _e("Enter URL"); ?>" class="mw_option_field mw-ui-field"/>
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
                            /*controllers: [
                                { type: 'url', config: {target: false, text: false}},
                                { type: 'page', config: {target: false, text: false} },
                                { type: 'post', config: {target: false, text: false}},
                                { type: 'layout', config: {target: false, text: false} }
                            ]*/
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
                            if (ldata.object) {
                                if (ldata.object.id) {
                                    if (ldata.object.type && ldata.data.type === 'category') {
                                        url = 'category:' + ldata.data.id;
                                    } else if (ldata.data.content_type) {
                                        url = 'content:' + ldata.data.id;
                                    }
                                }

                            }
                            if (!url_display) {
                                url_display = url;
                            }
                            mw.$('#btn-default_url').val(url).trigger('change');
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

                <div class="button-custom-design">
                    <script>

                        $(document).ready(function (){
                            Array.from(document.querySelectorAll('.button-color-field')).forEach(function (field){
                                mw.colorPicker({
                                    element: field,
                                    position: 'bottom-center',
                                    value: 'red',
                                    onchange: function (color) {
                                        $(field).trigger('change')
                                    }
                                })
                            })

                        })


                    </script>
                    <div class="form-group">
                        <label class="control-label"><?php _e('Background color'); ?></label>
                        <input type="text" class="form-control button-color-field mw_option_field" value="<?php print $backgroundColor ?>" autocomplete="off" name="backgroundColor">
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e('Text color'); ?></label>
                        <input type="text" class="form-control button-color-field mw_option_field" name="color" value="<?php print $color ?>" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e('Border color'); ?></label>
                        <input type="text" class="form-control button-color-field mw_option_field" name="borderColor" value="<?php print $borderColor ?>" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e('Border size'); ?></label>
                        <input type="number" min="0" max="100" class="form-control mw_option_field" name="borderWidth" value="<?php print $borderWidth ?>" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e('Radius'); ?></label>
                        <input type="number" min="0" max="100" class="form-control mw_option_field" name="borderRadius" value="<?php print $borderRadius ?>" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e('Shadow'); ?></label>
                        <div class="mw-ui-box shadow-selector">
                            <?php
                                $shadows = array(
                                  'rgba(149, 157, 165, 0.2) 0px 8px 24px;',
                                  'rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;',
                                  'rgba(0, 0, 0, 0.35) 0px 5px 15px;',
                                  'rgba(0, 0, 0, 0.24) 0px 3px 8px;',
                                  'rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;',
                                  'rgba(0, 0, 0, 0.1) 0px 4px 12px;',
                                  'rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;',
                                  'rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;',
                                  'rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;',
                                  'rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;',
                                  'rgba(50, 50, 93, 0.25) 0px 30px 60px -12px, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px;',
                                  'rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;',
                                  'rgba(0, 0, 0, 0.1) 0px 20px 25px -5px, rgba(0, 0, 0, 0.04) 0px 10px 10px -5px;',
                                );

                                foreach ($shadows as $shd) {
                                    print '<div><label class="mw-ui-check" ><input class="mw_option_field" type="radio" name="shadow" '.($shadow == $shd ? 'checked="true"' : '').' value="'.$shd.'" > <span></span> </label><span class="shadow-example" style="box-shadow: '.$shd.'"></span> </div>';
                                }
                            ?>
                        </div>
                         <script>
                            $(document).ready(function () {

                                $('.shadow-selector > div').on('click', function (){
                                    var node = this.querySelector('input');
                                    node.checked = true;
                                    $(node).trigger('change')
                                })

                            })

                        </script>
                    </div>






                </div>

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


            </div>
        </div>
    </div>
</div>
