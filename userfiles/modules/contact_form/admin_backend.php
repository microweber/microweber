<?php
if (!user_can_access('module.contact_form.index')) {
    return;
}
?>

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
        <div id="mw_index_contact_form">

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
            if (url_param('load_list') === false) {
                $load_list = 'all_lists';
            }
            ?>

            <?php
            $mod_action = '';
            $load_mod_action = false;
            if ((url_param('mod_action') != false)) {
                $mod_action = url_param('mod_action');
                if ($mod_action == 'browse' or $mod_action == 'add_new' or $mod_action == 'settings' or $mod_action == 'integrations') {
                    $load_list = false;
                    $load_mod_action = $mod_action;
                }
            }

            if ($mod_action == 'integrations') {
                $load_list = false;
            }

            $showTab = 'list';
            if (isset($_GET['tab'])) {
                $showTab = $_GET['tab'];
            }
            ?>

            <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
                <a class="btn btn-outline-secondary justify-content-center <?php if ($showTab =='list'):?> active <?php endif; ?>" data-bs-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e("Your form lists"); ?></a>
                <a class="btn btn-outline-secondary justify-content-center <?php if ($showTab =='settings'):?> active <?php endif; ?>" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Global settings'); ?></a>
                <a class="btn btn-outline-secondary justify-content-center <?php if ($showTab =='integrations'):?> active <?php endif; ?>" data-bs-toggle="tab" href="#integrations"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e("E-mail Integrations"); ?></a>
            </nav>

            <div class="tab-content py-3">
                <div class="tab-pane fade <?php if ($showTab =='list'):?> show active <?php endif; ?>" id="list">

                    <div class=" mb-3">
                        <div class="form-group">
                            <label class="control-label d-block mb-2"><?php _e('Your form lists'); ?></label>
                            <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="selectpicker" data-width="100%">
                                <option <?php if ($load_list === 'all_lists') { ?> selected="selected" <?php } ?> value="<?php print $config['url']; ?>"><?php _e('All lists'); ?></option>
                                <option <?php if ($load_list === 'default') { ?> selected="selected" <?php } ?> value="<?php print $config['url']; ?>/load_list:0"><?php _e('Default list'); ?>
                                    (<?php
                                    echo mw()->forms_manager->get_entires('count=true&list_id=0');
                                    ?>)
                                </option>
                                <?php $data = get_form_lists('module_name=contact_form'); ?>
                                <?php if (is_array($data)): ?>
                                    <?php foreach ($data as $item): ?>
                                        <?php if (empty($item['title'])) {
                                            continue;
                                        } ?>
                                        <option <?php if ($load_list == $item['id']) { ?> selected="selected" <?php } ?> value="<?php print $config['url']; ?>/load_list:<?php print $item['id']; ?>">
                                            <?php print $item['title']; ?>

                                            (<?php
                                            echo mw()->forms_manager->get_entires('count=true&list_id=' . $item['id']);
                                            ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <?php if ($load_list): ?>
                        <script type="text/javascript">
                            mw.on.hashParam('search', function (pval) {
                                var field = document.getElementById('forms_data_keyword');
                                if (!field.focused) {
                                    field.value = pval;
                                }
                                if (pval != '') {
                                    $('#forms_data_module').attr('keyword', pval);
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
                                    mw.tools.elementEdit(this, false, function () {
                                        var new_title = this
                                        mw.forms_data_manager.rename_form_list('<?php print $load_list ?>', new_title);
                                    });
                                });
                            });
                        </script>

                        <module type="contact_form/manager/list_toolbar" load_list="<?php print $load_list ?>"/>
                        <module type="contact_form/manager/list" load_list="<?php print $load_list ?>" for_module="<?php print $config["the_module"] ?>" id="forms_data_module"/>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade <?php if ($showTab =='settings'):?> show active <?php endif; ?>" id="settings">
                    <module type="settings/list" for_module="contact_form" for_module_id="contact_form_default"/>
                    <module type="contact_form/settings" for_module_id="contact_form_default"/>
                </div>

                <div class="tab-pane fade <?php if ($showTab =='integrations'):?> show active <?php endif; ?>" id="integrations">
                    <module type="admin/mail_providers/show_all"/>
                </div>
            </div>
        </div>
    </div>
