<?php  only_admin_access(); ?>

<div class="mw-module-admin-wrap">
    <?php if (isset($params['backend'])): ?>
        <module type="admin/modules/info"/>
    <?php endif; ?>
    <style scoped="scoped">

        .contact-form-export-search {
            overflow: hidden;
            padding: 0 0 20px 0;

        }

        .contact-form-export-search .export-label {
            margin: 8px 0 0 12px
        }

    </style>
    <div id="mw_index_contact_form" class="admin-side-content" style="max-width:100%;">
        <div>
            <?php
            $mw_notif = (url_param('mw_notif'));
            if ($mw_notif != false) {
                $mw_notif = mw()->notifications_manager->read($mw_notif);
            }
            mw()->notifications_manager->mark_as_read('contact_form');
            ?>
            <?php if (is_array($mw_notif) and isset($mw_notif['rel_id'])): ?>
                <script type="text/javascript">
                    $(document).ready(function () {
                        window.location.href = "<?php print $config['url']; ?>/load_list:<?php print $mw_notif['rel_id']; ?>";
                    });
                </script>
            <?php else : ?>
            <?php endif; ?>
            <?php
            mw()->notifications_manager->mark_as_read('contact_form');
            $load_list = 'default';
            if ((url_param('load_list') != false)) {
                $load_list = url_param('load_list');
            }
            ?>
            <?php
            $mod_action = '';
            $load_mod_action = false;
            if ((url_param('mod_action') != false)) {
                $mod_action = url_param('mod_action');
                if ($mod_action == 'browse' or $mod_action == 'add_new' or $mod_action == 'settings') {
                    $load_list = false;
                    $load_mod_action = $mod_action;
                }
            }
            ?>

            <div >
            <div class="mw-ui-btn-nav m-b-10">
                <a class="mw-ui-btn <?php if ($load_list == 'default') { ?>active<?php } ?>" href="<?php print $config['url']; ?>/load_list:default"><?php _e('Default list'); ?></a>
                <?php $data = get_form_lists('module_name=contact_form'); ?>
                <?php if (is_array($data)): ?>
                    <?php foreach ($data as $item): ?>
                        <a class="mw-ui-btn <?php if ($load_list == $item['id']) { ?> active <?php } ?>" href="<?php print $config['url']; ?>/load_list:<?php print $item['id']; ?>"><?php print $item['title']; ?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>


            <div class="mw-ui-btn-nav  m-b-10 pull-right">
                <a href="<?php print $config['url']; ?>/mod_action:settings" class="<?php if($mod_action == 'settings'){ ?> active <?php }?> mw-ui-btn"><?php _e("Settings"); ?></a>
             </div>
            </div>



            <?php /*<div class="mw-ui-btn-nav">
          <a href="<?php print $config['url']; ?>/mod_action:browse" class="<?php if($mod_action == 'browse'){ ?> active <?php }?> mw-ui-btn"><?php _e("My mod_action"); ?></a>
          <a href="<?php print $config['url']; ?>/mod_action:add_new" class="<?php if($mod_action == 'add_new'){ ?> active <?php }?>mw-ui-btn" onclick="Alert(<?php _e("Coming soon"); ?>)"><?php _e("Get more mod_action"); ?></a>
        </div>*/ ?>
        </div>
        <div class="mw-content-container">
            <div class="mw-ui-box mw-ui-box-content">
                <?php if ($load_list): ?>
                    <script type="text/javascript">
                        mw.on.hashParam('search', function () {
                            var field = mwd.getElementById('forms_data_keyword');
                            if (!field.focused) {
                                field.value = this;
                            }
                            if (this != '') {
                                $('#forms_data_module').attr('keyword', this);
                            }
                            else {
                                $('#forms_data_module').removeAttr('keyword');
                            }
                            $('#forms_data_module').removeAttr('export_to_excel');
                            mw.reload_module('#forms_data_module', function () {
                                mw.$("#forms_data_keyword").removeClass('loading');
                            });
                        });
                        $(document).ready(function () {
                            $("#form_field_title").click(function () {
                                mw.tools.liveEdit(this, false, function () {
                                    var new_title = this
                                    mw.forms_data_manager.rename_form_list('<?php print $load_list ?>', new_title);
                                });
                            });
                        });

                    </script>
                    <module type="contact_form/manager/list_toolbar" load_list="<?php print $load_list ?>"/>
                    <module type="contact_form/manager/list" load_list="<?php print $load_list ?>" for_module="<?php print $config["the_module"] ?>" id="forms_data_module"/>
                <?php if (strtolower(trim($load_list)) != 'default'): ?>
                    <span class="mw-ui-delete right" onclick="mw.forms_data_manager.delete_list('<?php print addslashes($load_list); ?>');"><?php _e("Delete list"); ?></span>
                <?php endif; ?>
                <?php endif; ?>
                <?php if ($load_mod_action == true): ?>

                    <?php if ($load_mod_action == 'settings'): ?>
                <module type="settings/list" for_module="contact_form" for_module_id="contact_form_default" >
                    <module type="contact_form/settings"  for_module_id="contact_form_default"  />

                    <?php endif; ?>
                <?php else : ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
