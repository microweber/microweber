<?php must_have_access(); ?>

<script type="text/javascript">
    mw.require('forms.js', true);
</script>



<?php
/*
 * $lang = get_option('language', 'website');
if (!$lang) {
    $lang = 'en';
}
set_current_lang($lang);
*/

$lang = mw()->lang_helper->current_lang();

if(isset($params['edit-lang']) and $params['edit-lang']){
    $lang = $params['edit-lang'];
    set_current_lang($lang);
    $lang = mw()->lang_helper->current_lang();
}
?>

<script type="text/javascript">
    function import_language_by_namespace(namespace, language) {
        mw.dialog({
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
            url: "<?php echo route('admin.backup.language.export'); ?>",
            data: "namespace=" + namespace + "&language=" + language,
            success: function (data) {
                window.location = data;
            }
        });
    }

    function send_lang_form_to_microweber() {
        if (!mw.$(".send-your-lang a").hasClass("disabled")) {
            mw.tools.disable(document.querySelector(".send-your-lang a"), "<?php _e('Sending...'); ?>");
            $.each($('.lang-edit-form'), function () {
                mw.form.post($(this), '<?php print api_link('send_lang_form_to_microweber'); ?>',
                    function (msg) {
                        mw.notification.msg(this, 1000, false);
                        mw.tools.enable(document.querySelector(".send-your-lang a"));
                    });
            });
        }

        return false;
    }

    function save_lang_form($form_id) {
        var formArray = $('#' + $form_id).serializeArray();

        mw.tools.loading( $('#' + $form_id),true)

        $.ajax({
            type: "POST",
            url: "<?php print api_link('save_language_file_content'); ?>",
            data: {lines: JSON.stringify(formArray)},
            dataType: "json",
            success: function (msg) {
                mw.tools.loading( $('#' + $form_id),false)
                if(msg.success){
                                mw.notification.success(msg.success,2000,'lang');
                } else {
                    mw.notification.msg(msg,2000,false);

                }
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

        $('.lang-edit-form textarea').on('focusin', function () {
            $(this).parent().parent().find('.lang-key-holder').addClass('border');
        })

        $('.lang-edit-form textarea').on('focusout', function () {
            $(this).parent().parent().find('.lang-key-holder').removeClass('border');
        })
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
        border: 1px solid transparent;
    }

    .lang-key-holder.border {
        border: 1px solid #4592ff !important;
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
    <div class="card-body pt-3 pb-0 px-0">
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
                            <input type="text" class="form-control js-search-lang-text"
                                   placeholder="<?php _e('Enter a word or phrase'); ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="thin mx-4"/>

<div class="card bg-none style-1 mb-0 card-settings">
    <div class="card-body pt-0 px-0">
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
                <form id="language-form" class="lang-edit-form">
                    <module type="settings/group/language_edit_browse" class="js-language-edit-browse" search="" page="1" />
                </form>
            </div>
        </div>
    </div>
</div>
