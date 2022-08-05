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
?>

<script type="text/javascript">

    function importTranslation(namespaceMD5) {
        mw.dialog({
            content: '<div id="mw_admin_import_language_modal_content"></div>',
            title: 'Import Language File',
            height: "auto",
            id: 'mw_admin_import_language_modal'
        });
        var params = {};
        params.namespaceMD5 = namespaceMD5;
        mw.load_module('settings/group/language_import', '#mw_admin_import_language_modal_content', null, params);
    }

    function exportTranslation(namespace) {

        mw.dialog({
            content: '<div id="mw_admin_export_language_modal_content"></div>',
            title: 'Export Language File',
            height: "auto",
            id: 'mw_admin_export_language_modal'
        });
        var params = {};
        params.namespace = namespace;
        mw.load_module('settings/group/language_export', '#mw_admin_export_language_modal_content', null, params);
    }

    function send_lang_form_to_microweber() {

        if (!mw.$(".send-your-lang a").hasClass("disabled")) {

            mw.tools.disable(document.querySelector(".send-your-lang a"), "<?php _e('Sending...'); ?>");

            $.ajax({
                type: "POST",
                url: "<?php echo route('admin.language.send_to_us'); ?>",
                success: function (data) {
                    mw.notification.msg('<?php _e('Thank you for your sharing.'); ?>', 1000, false);
                    mw.tools.enable(document.querySelector(".send-your-lang a"));
                }
            });
        }

        return false;
    }
</script>

<script>
    $('body').on('click', '.js-lang-file-position', function () {
        $(this).find('.mdi').toggleClass('mdi-rotate-270');
    });

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
                <?php if (mw()->ui->enable_service_links and mw()->ui->disable_powered_by_link == false): ?>

                <small class="text-muted"><?php _e('Help us improve'); ?></small>
                <a href="https://microweber.org/go/translation_help"  target="_blank" class="btn btn-outline-primary btn-sm mt-2"><?php _e('Help with translation'); ?></a>
                <?php endif; ?>



                <!--
                                <small class=""><?php /*_e('Help us improve Microweber'); */?></small>
                                <a href="javascript:;" onclick="send_lang_form_to_microweber()" class="btn btn-outline-primary btn-sm mt-2"><?php /*_e('Send us your translation'); */?></a>
                                -->
            </div>
            <div class="col-md-9">
                <form id="language-form" class="lang-edit-form">
                    <div class="js-lang-edit-form-messages"></div>
                    <?php
                    $getNamespaces = \MicroweberPackages\Translation\Models\TranslationKey::getNamespaces();
                    ?>
                    <?php if ($getNamespaces): ?>

                    <?php foreach ($getNamespaces as $translation):?>
                    <module type="settings/group/language_edit_browse"
                            class="js-language-edit-browse-module js-language-edit-browse-<?php echo md5($translation['translation_namespace']);?>"
                            translation_namespace="<?php echo $translation['translation_namespace']; ?>"
                            translation_namespace_md5="<?php echo md5($translation['translation_namespace']);?>" search="" page="1" />
                    <?php endforeach; ?>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>
