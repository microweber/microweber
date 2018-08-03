<?php only_admin_access();


?>

<div class=" mw-normalize-css">


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
            mod_id_orig = '<?php print $mod_orig_id ?>';



            mw.module_preset_apply_actions_after_id_change = function (id, attrs) {
                var parent_el = window.parent.document.getElementById(mod_id_for_presets);

                window.parent.mw.reload_module("#" + id);
                window.parent.mw.reload_module_parent("#" + id);


                window.top.mw.reload_module("#" + id);
                if (parent_el) {
                    var ed_field = window.parent.mw.tools.firstParentWithClass(parent_el, 'edit');
                    if (ed_field) {
                        window.parent.mw.$(ed_field).addClass('changed');
                    }
                }
                //mw.reload_module("#<?php print $params['id'] ?>")
                window.parent.mw.reload_module("#" + id);
                window.parent.mw.reload_module("#" + mod_id_for_presets);
//alert(1);
                if (
                    typeof(window.parent.module_settings_modal_reference_window) != 'undefined'
                    && typeof(window.parent.module_settings_modal_reference_preset_editor_modal_id) != 'undefined'
                    && window.parent.module_settings_modal_reference_preset_editor_modal_id
                ) {
                    var orig_attrs_str = '';
                    var parent_el = window.parent.document.getElementById(id);
                    if (parent_el != null) {
                        var orig_attrs = window.parent.mw.tools.getAttrs(parent_el);
                        var orig_attrs_str = $.param(orig_attrs);
//alert(orig_attrs_str);
                    }

                   // window.parent.$('#module-modal-settings-menu-holder').remove();

                    var src_new_modal_settings = mw.settings.site_url + 'api/module?id=' + id + '&live_edit=true&view=admin&is_mw_changed_preset_id=admin&module_settings=true&type=' + mod_type_opener_for_presets + '&autosize=true&' + orig_attrs_str;
                    window.parent.module_settings_modal_reference_window.location.href = src_new_modal_settings
                }


            }
            mw.module_preset_set_release = function (id) {

                var orig_id = window.parent.mw.$('#' + mod_id_for_presets).attr("data-module-original-id");
                //  var orig_id = mod_id_orig;
                var orig_attr = window.parent.mw.$('#' + mod_id_for_presets).attr("data-module-original-attrs");
                //    var orig_id = id;
                // var orig_id = mod_id_for_presets;


                if (orig_id) {

                    window.parent.mw.$('#' + mod_id_for_presets).removeAttr("data-module-original-id");
                    window.parent.mw.$('#' + mod_id_for_presets).removeAttr("data-module-original-attrs");
                    if (orig_attr) {
                        var orig_attrs_decoded = JSON.parse(window.atob(orig_attr));
                        if (orig_attrs_decoded) {
                            window.parent.mw.$('#' + mod_id_for_presets).attr(orig_attrs_decoded);

                        }
                    }
                    window.parent.mw.$('#' + mod_id_for_presets).removeAttr("data-module-original-id");
                    window.parent.mw.$('#' + mod_id_for_presets).removeAttr("data-module-original-attrs");
                    window.parent.mw.$('#' + mod_id_for_presets).attr("id", orig_id);
                    window.top.mw.$('#' + mod_id_for_presets).attr("id", orig_id);

                    window.parent.mw.reload_module("#" + orig_id);
                    mod_id_for_presets = orig_id;
                    mw.module_preset_apply_actions_after_id_change(mod_id_for_presets)

                }
            }


            mw.module_preset_set_use = function (is_use, use_attrs) {

                var orig_attrs;
                var orig_attrs_encoded;
                var parent_el = window.parent.document.getElementById(mod_id_for_presets);
                if (parent_el != null) {
                    var orig_attrs = window.parent.mw.tools.getAttrs(parent_el);
                    if (orig_attrs) {
                        var orig_attrs_encoded = window.btoa(JSON.stringify(orig_attrs));
                    }
                }

                var set_orig_id = window.parent.mw.$(parent_el).attr("id");
                var have_orig_id = window.parent.mw.$(parent_el).attr("data-module-original-id");
                var have_orig_attr = window.parent.mw.$(parent_el).attr("data-module-original-attrs");

                if (use_attrs) {
                    window.parent.mw.$(parent_el).attr(use_attrs);
                }
                if (!have_orig_attr && orig_attrs_encoded) {
                    window.parent.mw.$(parent_el).attr("data-module-original-attrs", orig_attrs_encoded);
                }
                if (!have_orig_id) {
                    ///   alert(set_orig_id);
                    window.parent.mw.$(parent_el).attr("data-module-original-id", set_orig_id);
                }
                window.parent.mw.$(parent_el).attr("id", is_use);
                // window.parent.mw.$(parent_el).css("background", 'red');

                mod_id_for_presets = is_use;
                mw.module_preset_apply_actions_after_id_change(mod_id_for_presets)
            }


            $(document).ready(function () {


                mw.$('.module-presets-action-btn').click(function () {
                    var is_del = $(this).attr('delete');
                    var btn_mod_id = $(this).attr('js-mod-id');
                    var temp_form1 = mw.tools.firstParentWithClass(this, 'module-presets-add-new-holder');
                    var save_module_as_template_url = '<?php print site_url('api') ?>/save_module_as_template';
                    var saved_module_attrs_json = $("[name='module_attrs']", temp_form1).val();


                    var attrs;
                    var parent_el = window.parent.document.getElementById(btn_mod_id);


                    if (parent_el != null) {
                        var attrs = window.parent.mw.tools.getAttrs(parent_el);
                    }


                    if (is_del != undefined) {
                        var save_module_as_template_url = '<?php print site_url('api') ?>/delete_module_as_template';
                    }

                    var is_use = $(this).attr('use');

                    var is_release = $(this).attr('release');
                    if (is_release != undefined) {

                        mw.module_preset_set_release(is_release);
                        //$(this).attr('release','btn_mod_id')
                    } else if (is_use != undefined) {

                        var use_attrs = JSON.parse(saved_module_attrs_json);
                        mw.module_preset_set_use(is_use, use_attrs);


                    } else {

                        if (attrs) {
                            var attrs_json = (JSON.stringify(attrs));
                            var append_attrs_field = '<textarea style="display: none" name="module_attrs">' + attrs_json + '</textarea>';
                            $(temp_form1).append(append_attrs_field);

                        }

                        //save
                        window.parent.mw.form.post(temp_form1, save_module_as_template_url, function () {
                            // window.parent.mw.reload_module("#<?php print $params['id'] ?>");
                            window.parent.mw.reload_module("#<?php print $params['id'] ?>");
                            mw.reload_module("#<?php print $params['id'] ?>")
                        });

                    }


                    return false;
                });

            });


        </script>
    <?php $fffound = false; ?>
        <div id="module-saved-presets">
            <?php /*<input type="button" value="release" release="<?php print  $mod_orig_id ?>" id="js-release-btn"
                   class="module-presets-action-btn"/>*/ ?>




            <?php $saved_modules = get_saved_modules_as_template("module={$module_name}"); ?>
            <?php if (is_array($saved_modules)): ?>
                <ul class="mw-presets-list">


                    <?php foreach ($saved_modules as $item): ?>

                        <?php

                        if (!isset($item['module_attrs'])) {
                            $item['module_attrs'] = '';
                        }

                        ?>
                        <li>

                                <div class="module-presets-add-new-holder">
                                    <span js-mod-id="<?php print  $item['module_id'] ?>" use="<?php print  $item['module_id'] ?>" class="module-presets-action-btn module-presets-action-btn-use"></span>

                                    <input type="hidden" name="id" value="<?php print  $item['id'] ?>">

                                    <input type="hidden" name="module" value="<?php print  $item['module'] ?>">

                                    <input type="text" class="module-presets-name-field " name="name" value="<?php print  $item['name'] ?>">

                                    <textarea name="module_attrs"
                                              style="display: none"><?php print  $item['module_attrs'] ?></textarea>

                                    <input type="hidden" name="module_id" value="<?php print  $item['module_id'] ?>">
                                    <?php if ($item['module_id'] == $module_id) : ?>


                                    <?php else : ?>
                                    <?php endif; ?>
                                    <?php if ($item['module_id'] == $module_id) {
                                        $fffound = 1;

                                    }

                                    ?>
                                    <span delete="1" js-mod-id="<?php print  $item['module_id'] ?>" class="mw-icon-trash-b module-presets-action-btn module-presets-action-btn-delete"></span>
                                </div>
                        </li>
                    <?php endforeach; ?>

                    <?php if (($fffound)!= false): ?>
                        <li>
                            <div class="module-presets-add-new-holder">

                            <input type="button" value="release" release="<?php print  $mod_orig_id ?>" id="js-release-btn"
                                   class="module-presets-action-btn"/>
                            </div>
                        </li>
                    <?php endif; ?>


                </ul>
            <?php endif; ?>
        </div>


    <?php if (($fffound)!= false): ?>
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




    <?php if (($fffound) == false): ?>
        <div class="module-presets-add-new-holder">
            <input type="hidden" name="module" value="<?php print $module_name ?>">
            <input type="text" name="name" value="" class="mw-ui-field mw-ui-field-medium" xonfocus="setVisible(event);"
                   xonblur="setVisible(event);">
            <input type="hidden" name="module_id" value="<?php print $module_id ?>">
            <span type="button" js-mod-id="<?php print  $module_id ?>" value="Save"
                   class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification module-presets-action-btn">Save</span>
        </div>
    <?php endif; ?>
    <?php else : ?>
        error $params['module_name'] is not set or $params['module_id'] is not set
    <?php endif; ?>
</div>