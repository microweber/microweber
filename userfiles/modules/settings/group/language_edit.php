<?php only_admin_access(); ?>

<script type="text/javascript">
    mw.require('forms.js', true);
</script>

<script type="text/javascript">
    function import_language_by_namespace(namespace, language) {
        mw.modal({
            content: '<div id="mw_admin_import_language_modal_content"></div>',
            title: 'Import Language File',
            height: 200,
            id: 'mw_admin_import_language_modal'
        });
        var params = {};
        params.namespace = namespace;
        params.language = language;
        mw.load_module('settings/group/language_import', '#mw_admin_import_language_modal_content', null, params);
    }

    function export_language_by_namespace(namespace, language) {
        $.ajax({
            type: "POST",
            url: mw.settings.api_url + "Microweber/Utils/Language/export",
            data: "namespace=" + namespace + "&language=" + language,
            success: function (data) {
                window.location = data;
            }
        });
    }

    function send_lang_form_to_microweber() {
        if (!mw.$(".send-your-lang a").hasClass("disabled")) {
            mw.tools.disable(mwd.querySelector(".send-your-lang a"), "<?php _e('Sending...'); ?>");
            $.each($('.lang-edit-form'), function () {
                mw.form.post($(this), '<?php print api_link('send_lang_form_to_microweber'); ?>',
                    function (msg) {
                        mw.notification.msg(this, 1000, true);
                        mw.tools.enable(mwd.querySelector(".send-your-lang a"));
                    });
            });
        }

        return false;
    }

    function save_lang_form($form_id) {
        var formArray = $('#' + $form_id).serializeArray();

        $.ajax({
            type: "POST",
            url: "<?php print api_link('save_language_file_content'); ?>",
            data: {lines: JSON.stringify(formArray)},
            dataType: "json",
            success: function (msg) {
                mw.notification.msg(msg);
            }
        });

        return false;
    }
</script>

<script>
    $('body').on('click', '.js-lang-file-position', function () {
        $(this).find('.mdi').toggleClass('mdi-rotate-270');
    })

    $(document).ready(function () {
        $('.lang-edit-form textarea').keypress(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
            }
        });
    });
</script>

<style>
    .lang-key-holder {
        background: #f3f3f3;
        max-width: 100%;
        width: 100%;
        padding: 3px 7px;
        min-height: 45px;
        display: flex;
        align-items: center;
    }

    .lang-edit-form textarea {
        min-height: 45px;
    }

    .lang-edit-form table,
    .lang-edit-form table th,
    .lang-edit-form table td,
    .lang-edit-form table tr {
        border: 0;
    }
</style>

<div class="card bg-none style-1 mb-0 card-settings">
    <div class="card-body pt-3 pb-0">
        <div class="row">
            <div class="col-md-3">
                <h5 class="font-weight-bold"><?php _e("Search"); ?></h5>
                <small class="text-muted"><?php _e('Search for words or phrases.'); ?></small>
            </div>
            <div class="col-md-9">
                <div class="row mt-3">
                    <div class="col">
                        <div class="input-group prepend-transparent">
                            <div class="input-group-prepend bg-white">
                                <span class="input-group-text"><i class="mdi mdi-magnify mdi-18px"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Enter a word or phrase"/>
                        </div>
                    </div>

                    <div class="col-auto pl-0">
                        <button type="submit" class="btn btn-outline-primary">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="thin mx-4"/>

<div class="card bg-none style-1 mb-0 card-settings">
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-md-3">
                <h5 class="font-weight-bold"><?php _e("Translation"); ?></h5>
                <small class="text-muted"><?php _e('You can translate the selected language from this fields.'); ?></small>
                <br/>
                <br/>
                <small class=""><?php _e('Help us improve Microweber'); ?></small>
                <a href="javascript:;" onclick="send_lang_form_to_microweber()" class="btn btn-outline-primary btn-sm mt-2"><?php _e('Send us your translation'); ?></a>
            </div>

            <div class="col-md-9">
                <?php
                /*
                 * $lang = get_option('language', 'website');
                if (!$lang) {
                    $lang = 'en';
                }
                set_current_lang($lang);
                */
                $lang = mw()->lang_helper->current_lang();

                $cont = mw()->lang_helper->get_language_file_content();
                $namespaces = mw()->lang_helper->get_all_language_file_namespaces();
                ?>
                <?php if (!empty($cont)): ?>
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body py-2">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="control-label mb-0"><?php _e('Language file'); ?>:
                                            <button type="button" class="btn btn-link px-0 js-lang-file-position" type="button" data-toggle="collapse" data-target="#global-file"><?php _e('Global'); ?> <i class="mdi mdi-menu-down mdi-rotate-270"></i></button>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="collapse" id="global-file">
                                <hr class="thin my-2"/>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <label class="control-label m-0">Global <?php _e('Language file'); ?></label>
                                    </div>

                                    <div>
                                        <a href="javascript:;" onClick="export_language_by_namespace('global', '<?php print $lang ?>');" class="btn btn-outline-primary btn-sm">Export to Excel</a>
                                        <a href="javascript:;" onClick="import_language_by_namespace('global', '<?php print $lang ?>');" class="btn btn-outline-primary btn-sm">Import Excel file</a>
                                    </div>
                                </div>

                                <hr class="thin my-2"/>

                                <form id="language-form-<?php print $params['id'] ?>" class="lang-edit-form">
                                    <input name="___lang" value="<?php print $lang ?>" type="hidden">

                                    <table width="100%" class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col" style="vertical-align: middle; width: 30%; max-width: 200px; overflow: hidden;"><?php _e('Key'); ?></th>
                                            <th scope="col"><?php _e('Value'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($cont as $k => $item): ?>
                                            <tr>
                                                <td style="vertical-align: middle; width: 30%; max-width: 200px; overflow: hidden;">
                                                    <div class="lang-key-holder">
                                                        <small><?php print $k ?></small>
                                                    </div>
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <textarea name="<?php print $k ?>" class="mw_lang_item_textarea_edit form-control form-control-sm" onchange="save_lang_form('language-form-<?php print $params['id'] ?>')" wrap="soft" rows="2"><?php print $item ?></textarea>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($namespaces)): ?>
                    <?php foreach ($namespaces as $iter => $ns): ?>

                        <?php
                        $cont = mw()->lang_helper->get_language_file_content($ns);
                        ?>

                        <?php if (!empty($cont)): ?>
                            <div class="card bg-light style-1 mb-3">
                                <div class="card-body py-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group mb-0">
                                                <label class="control-label mb-0"><?php _e('Language file'); ?>:
                                                    <button type="button" class="btn btn-link px-0 js-lang-file-position" type="button" data-toggle="collapse" data-target="#file-<?php print $params['id'] . $iter ?>"><?php print $ns ?> <i class="mdi mdi-menu-down mdi-rotate-270"></i></button>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="collapse" id="file-<?php print $params['id'] . $iter ?>">
                                        <hr class="thin my-2"/>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <label class="control-label m-0"><?php print $ns ?><?php _e('Language file'); ?></label>
                                            </div>

                                            <div>
                                                <a href="javascript:;" onClick="export_language_by_namespace('<?php print $ns; ?>', '<?php print $lang ?>');" class="btn btn-outline-primary btn-sm">Export Excel</a>
                                                <a href="javascript:;" onClick="import_language_by_namespace('<?php print $ns; ?>', '<?php print $lang ?>');" class="btn btn-outline-primary btn-sm">Import Excel</a>
                                            </div>
                                        </div>

                                        <hr class="thin my-2"/>

                                        <form id="language-form-<?php print $params['id'] . $iter ?>" class="lang-edit-form">
                                            <input name="___namespace" value="<?php print $ns ?>" type="hidden">
                                            <input name="___lang" value="<?php print $lang ?>" type="hidden">
                                            <table width="100%" class="table">
                                                <thead>
                                                <tr>
                                                    <th scope="col" style="vertical-align: middle; width: 30%; max-width: 200px; overflow: hidden;"><?php _e('Key'); ?></th>
                                                    <th scope="col"><?php _e('Value'); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($cont as $k => $item): ?>
                                                    <tr>
                                                        <td style="vertical-align: middle; width: 30%; max-width: 200px; overflow: hidden;">
                                                            <div class="lang-key-holder">
                                                                <small><?php print $k ?></small>
                                                            </div>
                                                        </td>
                                                        <td style="vertical-align: middle;">
                                                            <textarea name="<?php print $k ?>" class="mw_lang_item_textarea_edit form-control form-control-sm" onchange="save_lang_form('language-form-<?php print $params['id'] . $iter ?>')" wrap="soft" rows="2"><?php print $item ?></textarea>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>