<?php

// DEPRECATED
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
// file moved in userfiles/modules/editor/module_presets/index.php
return;

?>

<?php only_admin_access(); ?>
<?php if (isset($params['module_name']) and isset($params['module_id'])): ?>
    <?php


    $module_name = $params['module_name'];
    $module_id = $params['module_id'];

    ?>
    <script type="text/javascript">
        mw.require('forms.js', true);
    </script>
    <script type="text/javascript">


        mod_id_for_presets = '<?php print $module_id ?>';

        mw.module_preset_apply_actions_after_id_change = function (id) {
            var parent_el = window.parent.document.getElementById(mod_id_for_presets);

            //d(window.parent.mw.$('#'+id))
            window.parent.mw.reload_module("#" + id);
            window.parent.mw.reload_module_parent("#" + id);


            mw.top().reload_module("#" + id);
            if (parent_el) {
                var ed_field = window.parent.mw.tools.firstParentWithClass(parent_el, 'edit');
                if (ed_field) {
                    window.parent.mw.$(ed_field).addClass('changed');
                }
            }
        }
        mw.module_preset_set_release = function (id) {

            var orig_id = window.parent.mw.$('#' + mod_id_for_presets).attr("data-module-original-id");
            var orig_attr = window.parent.mw.$('#' + mod_id_for_presets).attr("data-module-original-attrs");

            if (orig_id) {

                window.parent.mw.$('#' + mod_id_for_presets).removeAttr("data-module-original-id");
                window.parent.mw.$('#' + mod_id_for_presets).removeAttr("data-module-original-attrs");
                if (orig_attr) {


                    var orig_attrs_decoded = JSON.parse(decodeURIComponent(window.atob(orig_attr)));
                    if (orig_attrs_decoded) {
                        window.parent.mw.$('#' + mod_id_for_presets).attr(orig_attrs_decoded);

                    }
                }
                window.parent.mw.$('#' + mod_id_for_presets).removeAttr("data-module-original-id");
                window.parent.mw.$('#' + mod_id_for_presets).removeAttr("data-module-original-attrs");
                window.parent.mw.$('#' + mod_id_for_presets).attr("id", orig_id);
                mw.top().$('#' + mod_id_for_presets).attr("id", orig_id);
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
                    var orig_attrs_encoded = window.btoa(encodeURIComponent(JSON.stringify(orig_attrs)));
                }
            }

            var orig_id = window.parent.mw.$(parent_el).attr("id");
            var have_orig_id = window.parent.mw.$(parent_el).attr("data-module-original-id");
            var have_orig_attr = window.parent.mw.$(parent_el).attr("data-module-original-attrs");
            if (!have_orig_attr && orig_attrs_encoded) {
                window.parent.mw.$(parent_el).attr("data-module-original-attrs", orig_attrs_encoded);
            }
            if (!have_orig_id) {
                window.parent.mw.$(parent_el).attr("data-module-original-id", orig_id);
            }
            if (use_attrs) {
                window.parent.mw.$(parent_el).attr(use_attrs);
            }
            window.parent.mw.$(parent_el).attr("id", is_use);
            // window.parent.mw.$(parent_el).css("background", 'red');

            mod_id_for_presets = is_use;
            mw.module_preset_apply_actions_after_id_change(mod_id_for_presets)
        }


        $(document).ready(function () {
            //   window.parent.mw.$('body').css("background", 'red');

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
                if (!!is_release) {

                    mw.module_preset_set_release();
                    /*     var orig_id = window.parent.mw.$('#
                    <?php print $module_id ?>').attr("data-module-original-id");

                     if (orig_id) {

                     window.parent.mw.$('#
                    <?php print $module_id ?>').attr("id", orig_id);
                     mw.module_preset_apply_actions_after_id_change(orig_id);
                     }
                     //*/
                } else if (!!is_use) {

                    var use_attrs = JSON.parse(saved_module_attrs_json);
                    mw.module_preset_set_use(is_use, use_attrs);
                    /*  if (mw.reload_module != undefined) {
                     if (!window.parent.mw.$('#
                    <?php print $module_id ?>').attr("data-module-original-id")) {
                     window.parent.mw.$('#
                    <?php print $module_id ?>').attr("data-module-original-id", is_use);
                     }

                     window.parent.mw.$('#
                    <?php print $module_id ?>').attr("module-id", is_use);
                     //  	window.parent.mw.$('#
                    <?php print $module_id ?>').attr("id",is_use);
                     //  window.parent.mw.$('#
                    <?php print $module_id ?>').attr("id", is_use);
                     //window.parent.mw.reload_module("#"+is_use);
                     mw.module_preset_apply_actions_after_id_change('
                    <?php print $module_id ?>');

                     }
                     */

                } else {

                    if (attrs) {
                        var attrs_json = (JSON.stringify(attrs));
                        var append_attrs_field = '<textarea style="display: none" name="module_attrs">' + attrs_json + '</textarea>';
                        $(temp_form1).append(append_attrs_field);

                    }


                    //
//                    module_attrs
                    //save
                    window.parent.mw.form.post(temp_form1, save_module_as_template_url, function () {
                        mw.reload_module_everywhere("#<?php print $params['id'] ?>");
                    });

                }


                return false;
            });

        });


    </script>
    <?php $fffound = false; ?>
<?php if (is_array($saved_modules)): ?>
    <div id="module-saved-presets" class="mw-ui-box mw-ui-box-content">
        <?php /*
        <input type="button" value="release" release="<?php print  $module_id ?>" class="module-presets-action-btn"/>
        <?php $saved_modules = get_saved_modules_as_template("module={$module_name}"); ?>*/ ?>



            <ul>
                <?php foreach ($saved_modules as $item): ?>
                    <li>
                        <div class="module-presets-add-new-holder">

                            <span js-mod-id="<?php print  $item['module_id'] ?>"
                                  use="<?php print  $item['module_id'] ?>"
                                  class="module-presets-action-btn module-presets-action-btn-use"></span>

                            <input type="hidden" name="id" value="<?php print  $item['id'] ?>">

                            <input type="hidden" name="module" value="<?php print  $item['module'] ?>">

                            <input type="text" class="module-presets-name-field" name="name" value="<?php print  $item['name'] ?>">

                            <textarea name="module_attrs"
                                      style="display: none"><?php print  $item['module_attrs'] ?></textarea>

                            <input type="hidden" name="module_id" value="<?php print  $item['module_id'] ?>">
                            <?php if ($item['module_id'] == $module_id) : ?>


                            <?php else : ?>
                            <?php endif; ?>
                            <?php if ($item['module_id'] == $module_id) {
                                $fffound = 1;

                            }

                            // d($item);
                            ?>

                            <span delete="1" js-mod-id="<?php print  $item['module_id'] ?>"
                                  class="mw-icon-trash-b module-presets-action-btn module-presets-action-btn-delete"></span>




                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
    </div>
        <?php endif; ?>

    <?php if (($fffound) == false): ?>
        <div class="module-presets-add-new-holder">
            <input type="hidden" name="module" value="<?php print $module_name ?>">
            <input type="text" name="name" value="" class="mw-ui-field">
            <input type="hidden" name="module_id" value="<?php print $module_id ?>">
            <input type="button" js-mod-id="<?php print  $module_id ?>" value="Save"
                   class="mw-ui-btn mw-ui-btn-notification module-presets-action-btn"/>
        </div>
    <?php endif; ?>
<?php else : ?>
    error $params['module_name'] is not set or $params['module_id'] is not set
<?php endif; ?>
