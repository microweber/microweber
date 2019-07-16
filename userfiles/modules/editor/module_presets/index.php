<?php only_admin_access();


?>



<?php if (isset($params['module_name']) and isset($params['module_id'])): ?>
    <?php
    $module_name = urldecode($params['module_name']);
    $module_name_opener = urldecode($params['module_name']);
    $module_name = str_replace('/admin', '', $module_name);
    $module_id = $params['module_id'];
    $mod_orig_id = $module_id;


    if (isset($params['data-module-original-id']) and $params['data-module-original-id']) {
        $mod_orig_id = $params['data-module-original-id'];
    }


    ?>
    <script type="text/javascript">
        mw.require('forms.js', true);
    </script>
    <script type="text/javascript">

        mod_parent_modal_dd_menu_wrapper_id = 'module-modal-settings-menu-holder';
        mod_id_for_presets = '<?php print $module_id ?>';
        mod_type_for_presets = '<?php print $module_name ?>';
        mod_type_opener_for_presets = '<?php print $module_name_opener ?>';
        mod_id_orig = window.parent.mw.$('#' + mod_id_for_presets).attr("data-module-original-id");


        mw.module_preset_apply_actions_after_id_change = function (id, attrs) {
             var parent_el = window.top.document.getElementById(mod_id_for_presets);


            if (parent_el) {
                var ed_field = window.top.mw.tools.firstParentWithClass(parent_el, 'edit');
                if (ed_field) {
                    window.top.mw.$(ed_field).addClass('changed');
                    if (parent !== self && !!window.top.mw) {
                        if (window.top.mw.drag != undefined && window.top.mw.drag.save != undefined) {
                         //   window.top.mw.drag.save();
                        }
                       //  window.top.mw.askusertostay = false;
                        window.top.mw.askusertostay = true;
                    }
                }
            }


            window.parent.mw.reload_module("#" + id);
            window.parent.mw.reload_module_parent("#" + id);


            window.top.mw.reload_module("#" + id);


            //mw.reload_module("#<?php print $params['id'] ?>")
            window.parent.mw.reload_module("#" + id);
            window.parent.mw.reload_module("#" + mod_id_for_presets);
           // window.top.mw.reload_module("#" + mod_id_for_presets);

            /*

            reloading of iframe

            if (
                typeof(window.top.module_settings_modal_reference_window) != 'undefined'
                && typeof(window.top.module_settings_modal_reference_preset_editor_modal_id) != 'undefined'
                && window.top.module_settings_modal_reference_preset_editor_modal_id
            ) {
                var orig_attrs_str = '';
                var parent_el = window.parent.document.getElementById(id);
                if (parent_el != null) {
                    var orig_attrs = window.parent.mw.tools.getAttrs(parent_el);
                    var orig_attrs_str = $.param(orig_attrs);

                }


                if (window.URL) {
                    var url = new URL(window.top.module_settings_modal_reference_window.location.href);

                    var query_string = url.search;

                    var search_params = new URLSearchParams(query_string);

                    search_params.set('id', id);

                    url.search = search_params.toString();
                    var new_url = url.toString();
                    var src_new_modal_settings  = new_url;

                } else {
                    var src_new_modal_settings = mw.settings.site_url + 'api/module?id=' + id + '&live_edit=true&view=admin&is_mw_changed_preset_id=admin&module_settings=true&type=' + mod_type_opener_for_presets + '&autosize=true&' + orig_attrs_str;
            }


              window.top.module_settings_modal_reference_window.location.href = src_new_modal_settings
            }*/

        }
        mw.module_preset_set_release = function (id) {

            var orig_id = window.top.mw.$('#' + mod_id_for_presets).attr("data-module-original-id");
            //  var orig_id = mod_id_orig;
            var orig_attr = window.top.mw.$('#' + mod_id_for_presets).attr("data-module-original-attrs");
            //    var orig_id = id;
            // var orig_id = mod_id_for_presets;





            if (orig_id) {

                window.top.mw.$('#' + mod_id_for_presets).removeAttr("data-module-original-id");
                window.top.mw.$('#' + mod_id_for_presets).removeAttr("data-module-original-attrs");
                if (orig_attr) {
                    var orig_attrs_decoded = JSON.parse(window.atob(orig_attr));
                    if (orig_attrs_decoded) {
                        window.top.mw.$('#' + mod_id_for_presets).attr(orig_attrs_decoded);

                    }
                }
                window.top.mw.$('#' + mod_id_for_presets).removeAttr("data-module-original-id");
                window.top.mw.$('#' + mod_id_for_presets).removeAttr("data-module-original-attrs");
                window.top.mw.$('#' + mod_id_for_presets).attr("id", orig_id);
                window.top.mw.$('#' + mod_id_for_presets).attr("id", orig_id);

                window.top.mw.reload_module("#" + orig_id);
                mod_id_for_presets = orig_id;
                mw.module_preset_apply_actions_after_id_change(mod_id_for_presets)

            }
        }


        mw.module_preset_set_use = function (is_use, use_attrs) {

            var orig_attrs;
            var orig_attrs_encoded;
            var parent_el = window.top.document.getElementById(mod_id_for_presets);
            var parent_el2 = null;
            var parent_el2_window = window;


            if (
                typeof(window.top.module_settings_modal_reference_window) != 'undefined'
                && typeof(window.top.module_settings_modal_reference_preset_editor_modal_id) != 'undefined'
                && window.top.module_settings_modal_reference_preset_editor_modal_id
            ) {
              var parent_el2_window = window.top.module_settings_modal_reference_window;

              var parent_el2 = parent_el2_window.document.getElementById(mod_id_for_presets);
            }



            if (parent_el != null) {
                var orig_attrs = window.top.mw.tools.getAttrs(parent_el);
                if (orig_attrs) {
                    var orig_attrs_encoded = window.btoa(JSON.stringify(orig_attrs));
                }
            }

            var set_orig_id = window.top.mw.$(parent_el).attr("id");
            var have_orig_id = window.top.mw.$(parent_el).attr("data-module-original-id");
            var have_orig_attr = window.top.mw.$(parent_el).attr("data-module-original-attrs");

            if (use_attrs) {
                window.top.mw.$(parent_el).attr(use_attrs);
            }
            if (!have_orig_attr && orig_attrs_encoded) {
                window.top.mw.$(parent_el).attr("data-module-original-attrs", orig_attrs_encoded);
            }
            if (!have_orig_id) {
                ///   alert(set_orig_id);
                window.top.mw.$(parent_el).attr("data-module-original-id", set_orig_id);
            }
            window.top.mw.$(parent_el).attr("id", is_use);
            if(parent_el2){
            parent_el2_window.mw.$(parent_el2).attr("id", is_use);
            if (use_attrs) {
                parent_el2_window.mw.$(parent_el2).attr(use_attrs);
            }
            }




            // window.top.mw.$(parent_el).css("background", 'red');
             mod_id_for_presets = is_use;
            mw.module_preset_apply_actions_after_id_change(mod_id_for_presets)

        }


        $(document).ready(function () {








            $('.module-presets-action-btn').on('click',function () {

                var is_del = $(this).attr('delete');
                var btn_mod_id = $(this).attr('js-mod-id');
                var temp_form1 = mw.tools.firstParentWithClass(this, 'module-presets-add-new-holder');
                var save_module_as_template_url = '<?php print site_url('api') ?>/save_module_as_template';
                var saved_module_attrs_json = $("[name='module_attrs']", temp_form1).val();


                var attrs;
                var parent_el = window.top.document.getElementById(btn_mod_id);




                if (parent_el != null) {
                    attrs = window.top.mw.tools.getAttrs(parent_el);
                }


                if (is_del != undefined) {
                    var save_module_as_template_url = '<?php print site_url('api') ?>/delete_module_as_template';
                }

                var is_use = $(this).attr('use');
                var is_release = $(this).attr('release');
                var is_del = $(this).attr('delete');







                if (is_release != undefined) {


                    mw.module_preset_set_release(is_release);
                    //$(this).attr('release','btn_mod_id')
                } else if (is_use != undefined) {

                } else {


                    if(is_del){
                         mw.module_preset_apply_actions_after_id_change(is_del)
                        if(temp_form1){
                            $(temp_form1).remove();
                        }
                         //    mw.module_preset_set_release(is_release);
                    }




                    if (attrs) {
                         var attrs_json = (JSON.stringify(attrs));
                        var append_attrs_field = '<textarea style="display: none" name="module_attrs">' + attrs_json + '</textarea>';
                        $(temp_form1).append(append_attrs_field);

                    }

                    //save
                    window.parent.mw.form.post(temp_form1, save_module_as_template_url, function () {
                        // window.parent.mw.reload_module("#<?php print $params['id'] ?>");
                        mw.reload_module_everywhere("#<?php print $params['id'] ?>");
                        mw.reload_module("#<?php print $params['id'] ?>")

                        mw.module_preset_apply_actions_after_id_change(mod_id_for_presets);
                        cancelCreatePreset()

                    });

                }








                if(typeof(saved_module_attrs_json) != 'undefined'){
                    var use_attrs = JSON.parse(saved_module_attrs_json);
                    mw.module_preset_set_use(is_use, use_attrs);

                }











                return false;
            });

        });




        function mw_preset_edit_name(input_obj) {



            var save_module_as_template_url = '<?php print site_url('api') ?>/save_module_as_template';
            var btn_mod_id = $(input_obj).attr('js-mod-id');
            var temp_form1 = mw.tools.firstParentWithClass(input_obj, 'module-presets-add-new-holder');
            window.parent.mw.form.post(temp_form1, save_module_as_template_url, function () {
                window.parent.mw.notification.success('Preset name is updated');
            });
        }



    </script>
    <?php $fffound = false; ?>
    <div id="module-saved-presets" class="mw-ui-box mw-ui-box-content">
        <?php /*<input type="button" value="release" release="<?php print  $mod_orig_id ?>" id="js-release-btn"
                   class="module-presets-action-btn"/>*/ ?>




        <?php $saved_modules = get_saved_modules_as_template("module={$module_name}"); ?>
        <?php


        if (is_array($saved_modules)): ?>



        <?php foreach ($saved_modules as $item): ?>
            <div class="mw-flex-row mw-presets-list">
                <?php
                $fffound = false;
                if (!isset($item['module_attrs'])) {
                    $item['module_attrs'] = '';
                }

                if ($item['module_id'] == $module_id) {
                    $fffound = 1;
                }

                ?>

                <div class="mw-flex-col-xs-2 module-presets-add-new-holder ">



                    <div class="box " style="width: 35px; margin: 0 auto;">


                        <button type="button" value="1" js-mod-id="<?php print  $item['module_id'] ?>" use="<?php print  $item['module_id'] ?>" class="mw-ui-btn mw-ui-btn-small module-presets-action-btn  module-presets-action-btn-use">use</button>





                        <input type="hidden" name="id" value="<?php print  $item['id'] ?>">
                        <input type="hidden" name="module" value="<?php print  $item['module'] ?>">
                        <textarea name="module_attrs" style="display: none"><?php print  $item['module_attrs'] ?></textarea>
                    </div>






                </div>
                <div class="mw-flex-col-xs-6 module-presets-add-new-holder">
                    <div class="box">




                        <?php

                        /*
                        <?php if($fffound) { ?>

                            <b><?php print  $item['name'] ?></b>

                        <?php } else { ?>
                            <?php print  $item['name'] ?>
                        <?php } ?>
*/
                        ?>

                        <input type="hidden" name="id" value="<?php print  $item['id'] ?>">
                        <input type="hidden" name="module" value="<?php print  $item['module'] ?>">


                        <input   type="text" onkeyup="mw.on.stopWriting(this,function(){mw_preset_edit_name(this)});"  js-mod-id="<?php print  $item['module_id'] ?>" class="mw-ui-field js-module-presets-edit-name mw-ui-field-medium" name="name" value="<?php print  $item['name'] ?>" />


                        <?php
                        /*  <input class="mw-ui-field module-presets-name-field mw-ui-field-medium" name="name" value="<?php print  $item['name'] ?>">

                        <textarea name="module_attrs" style="display: none"><?php print  $item['module_attrs'] ?></textarea>
                        <input type="hidden" name="module_id" value="<?php print  $item['module_id'] ?>">
                        <?php if ($item['module_id'] == $module_id) : ?>
                        <?php else : ?>
                        <?php endif; ?>
                        <?php if ($item['module_id'] == $module_id) {
                            $fffound = 1;
                        }
                        ?>*/



                        ?>

                    </div>
                </div>
                <div class="mw-flex-col-xs-4 module-presets-add-new-holder">
                    <div class="box">
                        <input type="hidden" name="id" value="<?php print  $item['id'] ?>">
                        <input type="hidden" name="module" value="<?php print  $item['module'] ?>">

                        <button
                            delete="<?php print  $item['module_id'] ?>"
                            js-mod-id="<?php print  $item['module_id'] ?>"
                            class="mw-icon-trash-b mw-ui-btn mw-ui-btn-important mw-ui-btn-small module-presets-action-btn module-presets-action-btn-delete "></button>
                    </div>
                </div>
            </div>


            <div class="mw-ui-row mw-presets-list m-t-10 hidden">
                <?php

                if (!isset($item['module_attrs'])) {
                    $item['module_attrs'] = '';
                }

                ?>
                <div class="mw-ui-col module-presets-add-new-holder" style="width: 15px !important;">
                    <label class="mw-ui-check">
                        <input type="checkbox" value="1" title="" js-mod-id="<?php print  $item['module_id'] ?>" use="<?php print  $item['module_id'] ?>" class="module-presets-action-btn-use"><span></span><span></span>
                    </label>
                    <input type="hidden" name="id" value="<?php print  $item['id'] ?>">
                    <input type="hidden" name="module" value="<?php print  $item['module'] ?>">

                    <textarea name="module_attrs" style="display: none"><?php print  $item['module_attrs'] ?></textarea>
                </div>

                <div class="mw-ui-col module-presets-add-new-holder" style="width: 50px !important;">
                    <input class="mw-ui-field module-presets-name-field mw-ui-field-medium" name="name" value="<?php print  $item['name'] ?>">

                    <textarea name="module_attrs" style="display: none"><?php print  $item['module_attrs'] ?></textarea>
                    <input type="hidden" name="module_id" value="<?php print  $item['module_id'] ?>">
                    <?php if ($item['module_id'] == $module_id) : ?>
                    <?php else : ?>
                    <?php endif; ?>
                    <?php if ($item['module_id'] == $module_id) {
                        $fffound = $module_id;
                    }
                    ?>
                </div>



                <div class="mw-ui-col module-presets-add-new-holder" style="width: 40px !important;">
                    <input type="hidden" name="id" value="<?php print  $item['id'] ?>">
                    <input type="hidden" name="module" value="<?php print  $item['module'] ?>">

                    <span delete="<?php print  $item['module_id'] ?>" js-mod-id="<?php print  $item['module_id'] ?>" class="mw-icon-trash-b mw-ui-btn mw-ui-btn-important mw-ui-btn-medium module-presets-action-btn module-presets-action-btn-delete "></span>
                </div>
            </div>
        <?php endforeach; ?>


        <?php if (($fffound) != false): ?>
            <div class="mw-ui-row">
                <div class="mw-ui-col">
                    <div class="module-presets-bottom-holder">
                        <input type="hidden" name="module_id" value="<?php print $module_id ?>">
                        <button
                            type="button"
                            js-mod-id="<?php print  $fffound;  ?>"
                            release="<?php print  $fffound;  ?>"
                            id="js-release-btn"
                            class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info module-presets-action-btn">Clear use of preset</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
    </div>


    <?php if (($fffound) != false): ?>
        <script>
            $(document).ready(function () {
                $("#module-modal-preset-linked-icon").addClass('is-linked').show();

            });
        </script>
    <?php else : ?>
        <script>
            $(document).ready(function () {
                $("#module-modal-preset-linked-icon").removeClass('is-linked').hide();
            });
        </script>
    <?php endif; ?>

<script>
    var btn = document.createElement('span');
    btn.className = 'mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification tip';
    btn.dataset.tip = "Create preset";
    btn.dataset.tipposition = "bottom-right";
    btn.id = 'create-presets-btn';
    btn.innerHTML = '<span class="mw-icon-plus-round"></span>';
    btn.onclick = function(){
        createPreset()
    };
    parent.document.querySelector('.mw-module-presets-content').appendChild(btn);
    var t$win = window.parent;

    cancelCreatePreset = function(){
        t$win.$('.create-presets-holder').hide();
        $('.create-presets-holder').hide();

        $('#module-saved-presets').show();
        t$win.$('#module-saved-presets').show();

        $('#create-presets-btn').show();
        t$win.$('#create-presets-btn').show();
    };

    createPreset = function(){
        $('.create-presets-holder').show();
        t$win.$('.create-presets-holder').show();

        $('#module-saved-presets').hide();
        t$win.$('#module-saved-presets').hide();

        $('#create-presets-btn').hide();
        t$win.$('#create-presets-btn').hide();
    }
</script>

    <?php //if (($fffound) == false): ?>


        <div class="create-presets-holder" style="display: none;">
            <b>Create new preset</b>
            <div class="mw-flex-row m-t-10 module-presets-add-new-holder">
                <div class="mw-flex-col-xs-9 ">
                    <input type="hidden" name="module" value="<?php print $module_name ?>">
                    <input type="hidden" name="module_id" value="<?php print $module_id ?>">
                    <input type="text" name="name" value="" placeholder="<?php _e('Title'); ?>" class="mw-ui-field mw-ui-field-medium w100">
                </div>
                <div class="mw-flex-col-xs-3">
                    <div class="mw-ui-btn-nav">
                        <span
                            onclick="cancelCreatePreset()"
                            class="mw-ui-btn mw-ui-btn-medium">Cancel</span>
                        <span
                            js-mod-id="<?php print  $module_id ?>"
                            class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification module-presets-action-btn">Save</span>
                    </div>
                </div>
            </div>
        </div>
    <?php //endif; ?>

<?php else : ?>
    error $params['module_name'] is not set or $params['module_id'] is not set
<?php endif; ?>
