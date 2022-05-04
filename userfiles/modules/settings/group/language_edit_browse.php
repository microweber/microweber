<?php

use MicroweberPackages\Translation\Models\TranslationText;

$filter = [];
if (isset($params['search'])) {
    $filter['search'] = $params['search'];
}
if (isset($params['page'])) {
    $filter['page'] = $params['page'];
}

if (isset($params['translation_namespace'])) {
    $filter['translation_namespace'] = $params['translation_namespace'];
} else {
    $filter['translation_namespace'] = 'global';
}

$namespace = $filter['translation_namespace'];
$namespaceMd5 = md5($namespace);

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
$getTranslations = \MicroweberPackages\Translation\Models\TranslationKey::getGroupedTranslations($filter);
?>
<script>

    <?php
    if (empty($getTranslations['results'])):
    ?>
    $('.js-language-edit-browse-<?php echo $namespaceMd5;?>').fadeOut();
    <?php
   else:
    ?>
    $('.js-language-edit-browse-<?php echo $namespaceMd5;?>').fadeIn();
    <?php
    endif;
    ?>

    $('#language-edit-<?php echo $namespaceMd5;?>').collapse('show');
</script>

<script>

    /**
     * jQuery serializeObject
     * @copyright 2014, macek <paulmacek@gmail.com>
     * @link https://github.com/macek/jquery-serialize-object
     * @license BSD
     * @version 2.5.0
     */
    !function(e,i){if("function"==typeof define&&define.amd)define(["exports","jquery"],function(e,r){return i(e,r)});else if("undefined"!=typeof exports){var r=require("jquery");i(exports,r)}else i(e,e.jQuery||e.Zepto||e.ender||e.$)}(this,function(e,i){function r(e,r){function n(e,i,r){return e[i]=r,e}function a(e,i){for(var r,a=e.match(t.key);void 0!==(r=a.pop());)if(t.push.test(r)){var u=s(e.replace(/\[\]$/,""));i=n([],u,i)}else t.fixed.test(r)?i=n([],r,i):t.named.test(r)&&(i=n({},r,i));return i}function s(e){return void 0===h[e]&&(h[e]=0),h[e]++}function u(e){switch(i('[name="'+e.name+'"]',r).attr("type")){case"checkbox":return"on"===e.value?!0:e.value;default:return e.value}}function f(i){if(!t.validate.test(i.name))return this;var r=a(i.name,u(i));return l=e.extend(!0,l,r),this}function d(i){if(!e.isArray(i))throw new Error("formSerializer.addPairs expects an Array");for(var r=0,t=i.length;t>r;r++)this.addPair(i[r]);return this}function o(){return l}function c(){return JSON.stringify(o())}var l={},h={};this.addPair=f,this.addPairs=d,this.serialize=o,this.serializeJSON=c}var t={validate:/^[a-z_][a-z0-9_]*(?:\[(?:\d*|[a-z0-9_]+)\])*$/i,key:/[a-z0-9_]+|(?=\[\])/gi,push:/^$/,fixed:/^\d+$/,named:/^[a-z0-9_]+$/i};return r.patterns=t,r.serializeObject=function(){return new r(i,this).addPairs(this.serializeArray()).serialize()},r.serializeJSON=function(){return new r(i,this).addPairs(this.serializeArray()).serializeJSON()},"undefined"!=typeof i.fn&&(i.fn.serializeObject=r.serializeObject,i.fn.serializeJSON=r.serializeJSON),e.FormSerializer=r,r});

    //$(document).keypress(function(e) {
    //    if (e.which == 13) {
    //        searchLangauges<?php //echo $namespaceMd5;?>//();
    //    }
    //});

    function searchLangauges<?php echo $namespaceMd5;?>()
    {
        var searchText = $('.js-search-lang-text').val();

        $('.js-language-edit-browse-module').attr('page', 1);
        $('.js-language-edit-browse-module').attr('search', searchText);

      //  mw.reload_module('.js-language-edit-browse-<?php echo $namespaceMd5;?>');

        mw.tools.loading('.js-language-edit-browse-module',true)
        mw.reload_module('.js-language-edit-browse-module',function () {
            mw.tools.loading('.js-language-edit-browse-module',false)

        });


        setTimeout(function() {
            $('.js-lang-edit-form-messages').html('');
            if ($('.js-language-edit-browse-module:hidden').size() == $('.js-language-edit-browse-module').size()) {
                $('.js-lang-edit-form-messages').html('<div class="alert alert-warning"><?php _e('No results found');?></div>');
            }
        }, 2000);
    }

    $(document).ready(function () {

        $('.js-import-language-translations').click(function () {
            $('.js-import-language-translations').html('Importing...');
            $.ajax({
                type: "POST",
                url: "<?php echo route('admin.language.import_missing_translations'); ?>",
            }).done(function (resp) {
                mw.notification.success('<?php _e('Translations are imported'); ?>');
                location.reload();
            });
        });

        $('.js-search-lang-text').off('input');
        $('.js-search-lang-text').on('input', function () {
            mw.on.stopWriting(this,function() {
                searchLangauges<?php echo $namespaceMd5;?>();
            });
        });

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
                    mw.notification.success('<?php _e('Settings are saved'); ?>');
                });
            });
        });

    });


</script>
<style scoped>
    .lang_textarea_key {
        display: inline-block;
        border: none;
        overflow-y: auto;
        resize: both;
    }
</style>

<?php
if (TranslationText::where('translation_locale', mw()->lang_helper->current_lang())->count() == 0):
?>
<div class="alert alert-warning mb-3">
<?php _e('Translations not found in database. Do you wish to import translations? '); ?>
    <br /><br />
    <button type="button" class="js-import-language-translations btn btn-outline-primary btn-sm"><?php _e('Import'); ?></button>
</div>
<?php
endif;
?>

<div class="card bg-light style-1 mb-3">
    <div class="card-body py-2">
        <div class="row">
            <div class="col-12">
                <div class="form-group mb-0">
                    <label class="control-label mb-0"><?php _e('Language file'); ?>:
                        <button type="button" class="btn btn-link js-lang-file-position" type="button" data-toggle="collapse" data-target="#language-edit-<?php echo $namespaceMd5;?>">
                            <?php
                            if ($namespace == '*') {
                                echo 'Global';
                            } else {
                                echo $namespace;
                            }
                            ?>
                            <i class="mdi mdi-menu-down mdi-rotate-270"></i>
                        </button>
                    </label>
                </div>
            </div>
        </div>
        <div class="collapse" id="language-edit-<?php echo $namespaceMd5;?>">
        <hr class="thin my-2"/>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <label class="control-label m-0"><?php _e('Translate the fields to different languages'); ?></label>
            </div>

           <div>
                <button type="button" onClick="exportTranslation('<?php echo $namespace;?>')" class="btn btn-outline-primary btn-sm"><?php _e('Export File'); ?></button>
                <button type="button" onClick="importTranslation('<?php echo $namespaceMd5;?>')" class="btn btn-outline-primary btn-sm"><?php _e('Import File'); ?></button>
            </div>
        </div>

        <div class="js-language-pagination-<?php echo $namespaceMd5;?> text-center mt-5">
        <?php
        echo $getTranslations['pagination'];
        ?>
        </div>

        <table width="100%" class="table js-table-lang">
            <thead>
            <tr>
                <th scope="col" style="vertical-align: middle; width: 30%; max-width: 200px; overflow: hidden;"><?php _e('Key'); ?></th>
                <th scope="col"><?php _e('Value'); ?></th>
            </tr>
            </thead>
            <tbody>

            <?php
            foreach ($getTranslations['results'] as $translationKey=>$translationByLocales):
                $translationKeyMd5 = md5($translationKey . $namespaceMd5);
                ?>
                <tr style="border-bottom: 1px solid #cfcfcf">
                    <td style="vertical-align: middle; width: 30%; max-width: 200px; overflow: hidden;">
                        <div class="lang-key-holder">
                            <textarea  readonly disabled="disabled"  class="lang_textarea_key form-control form-control-sm"><?php echo $translationKey;?></textarea>


                        </div>
                    </td>
                    <td style="vertical-align: middle;">
                        <?php
                        foreach ($supportedLanguages as $supportedLanguage):
                            ?>

                            <div class="form-group">
                                 <small  class="form-text text-muted"><?php echo $supportedLanguage['language'];?></small>


                                <div class="input-group mb-3">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text" >
                                         <span class="flag-icon flag-icon-<?php echo $supportedLanguage['icon']; ?> m-r-10"></span>
                                        </span>
                                    </div>
                                    <input type="hidden" name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][translation_group]" value="*">
                                    <input type="hidden" name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][translation_namespace]" value="<?php echo $namespace;?>">


                                    <textarea name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][translation_key]" style="display:none;"><?php echo $translationKey;?></textarea>
                                    <textarea oninput="$(this).parent().addClass('js-translate-changed-fields');" name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][translation_text]" class="mw_lang_item_textarea_edit form-control form-control-sm" aria-label="" aria-describedby="basic-addon1" wrap="soft" rows="2"><?php
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

                            </div>




                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

            <div class="js-language-pagination-<?php echo $namespaceMd5;?>">
                <?php
                echo $getTranslations['pagination'];
                ?>
            </div>
        </div>


        <script>
            // Laravel Pagination
            $(document).on('click', '.js-language-pagination-<?php echo $namespaceMd5;?> .pagination a', function(event){
                event.preventDefault();

                var page = $(this).attr('href').split('page=')[1];

                $('.js-language-edit-browse-<?php echo $namespaceMd5;?>').attr('page', page);
                mw.reload_module('.js-language-edit-browse-<?php echo $namespaceMd5;?>');

            });
        </script>


    </div>
</div>

