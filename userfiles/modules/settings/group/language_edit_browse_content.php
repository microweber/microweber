<?php

$filter = [];

if (isset($params['namespace'])) {
    $filter['translation_namespace'] = $params['namespace'];
}
if (isset($params['search'])) {
    $filter['search'] = trim($params['search']);
}
if (isset($params['page'])) {
    $filter['page'] = $params['page'];
}

$getTranslations = \MicroweberPackages\Translation\Models\TranslationKey::getGroupedTranslations($filter);

\Config::set('microweber.disable_model_cache', true);
$supportedLanguages = [];
if (function_exists('get_supported_languages')) {
    $supportedLanguages = get_supported_languages(true);
}

if(empty($supportedLanguages)){
    $currentLanguageAbr = mw()->lang_helper->default_lang();
    $supportedLanguages[] = [
        'icon'=>get_flag_icon($currentLanguageAbr),
        'locale'=>$currentLanguageAbr,
        'language'=>$currentLanguageAbr
    ];
}

$namespace = $filter['translation_namespace'];
$namespaceMd5 = md5($namespace);
?>

<?php
foreach ($getTranslations['results'] as $translationKey=>$translationByLocales):
    $translationKeyMd5 = md5($translationKey . $namespaceMd5);
    ?>

    <div class="card mb-4 col-xxl-8 col-xl-10 col-12 mx-auto">
        <div class="card-body">
            <small class="d-block mb-1 text-primary"><?php _e("Translation key") ?></small>

            <p class="mb-0">
                <b><?php echo $translationKey;?></b>
            </p>

            <?php
            foreach ($supportedLanguages as $supportedLanguage):
                ?>


                <div class="d-flex align-items-center">

                    <div class="py-3">
                        <span class="flag-icon flag-icon-<?php echo $supportedLanguage['icon']; ?> m-r-10"></span>
                        <small  class="form-text text-muted"><?php echo $supportedLanguage['language'];?></small>
                    </div>
                </div>

                <div class="input-group">

                    <input type="hidden" name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][translation_group]" value="*">
                    <input type="hidden" name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][translation_namespace]" value="<?php echo $namespace;?>">


                    <textarea name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][translation_key]" style="display:none;"><?php echo $translationKey;?></textarea>
                    <textarea oninput="$(this).parent().addClass('js-translate-changed-fields');" name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][translation_text]" class="mw_lang_item_textarea_edit form-control" aria-label="" aria-describedby="basic-addon1" wrap="soft" rows="2"><?php
                        if(isset($translationByLocales[$supportedLanguage['locale']])) {
                            echo $translationByLocales[$supportedLanguage['locale']];
                        } else {
                            if (strpos($supportedLanguage['locale'], 'en') !== false) {
                                echo $translationKey;
                            } else {
                                echo '';
                            }
                        }
                        ?></textarea>
                </div>

            <?php endforeach; ?>

        </div>
    </div>

<?php endforeach; ?>


<div class="js-language-pagination-<?php echo $namespaceMd5;?>">
    <?php
    echo $getTranslations['pagination'];
    ?>
</div>



<script>
    $(document).ready(function () {

        $('.mw_lang_item_textarea_edit').on('input', function () {
            mw.on.stopWriting(this,function(){

                var  saveTranslations = JSON.stringify($('.js-translate-changed-fields').find('input,textarea,select').serializeObject());
                saveTranslations = btoa(encodeURIComponent(saveTranslations).replace(/%([0-9A-F]{2})/g,
                    function toSolidBytes(match, p1) {
                        return String.fromCharCode('0x' + p1);
                    }));

                $.ajax({
                    type: "POST",
                    url: "<?php echo route('admin.language.save'); ?>",
                    data: {translations:saveTranslations}
                }).done(function (resp) {
                    mw.notification.success('<?php _ejs('Settings are saved'); ?>');
                });
            });
        });
    });

    // Laravel Pagination
    $(document).on('click', '.js-language-pagination-<?php echo $namespaceMd5;?> .pagination a', function(event){
        event.preventDefault();

        var page = $(this).attr('href').split('page=')[1];

        $('#js-language-edit-browse-content-<?php echo $namespaceMd5;?>').attr('page', page);
        mw.reload_module('#js-language-edit-browse-content-<?php echo $namespaceMd5;?>');

    });
</script>
