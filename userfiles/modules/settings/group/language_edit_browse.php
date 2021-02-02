<script>
    $('.collapse').collapse('show');
</script>

<script>
    $(document).ready(function () {

        $('.js-search-lang-text').on('input', function () {
            $('.js-language-edit-browse').attr('search', $(this).val());
            mw.reload_module('.js-language-edit-browse');
        });

        $('.mw_lang_item_textarea_edit').on('input', function () {
            mw.on.stopWriting(this,function(){
                var saveTranslations = $('.lang-edit-form').serialize();
                $.ajax({
                    type: "POST",
                    url: "<?php echo route('admin.language.save'); ?>",
                    data: saveTranslations
                }).done(function (resp) {
                    mw.notification.success('<?php _e('Settings are saved'); ?>');
                });
            });
        });

    });
</script>

<div class="card bg-light style-1 mb-3">
    <div class="card-body py-2">
        <div class="row">
            <div class="col-12">
                <div class="form-group mb-0">
                    <label class="control-label mb-0"><?php _e('Language file'); ?>:
                        <button type="button" class="btn btn-link px-0 js-lang-file-position" type="button" data-toggle="collapse" data-target="#global-file">
                            <?php _e('Global'); ?> <i class="mdi mdi-menu-down mdi-rotate-270"></i>
                        </button>
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
                <a href="javascript:;" onClick="" class="btn btn-outline-primary btn-sm">Export to Excel</a>
                <a href="javascript:;" onClick="" class="btn btn-outline-primary btn-sm">Import Excel file</a>
            </div>
        </div>

        <hr class="thin my-2"/>

        <table width="100%" class="table js-table-lang">
            <thead>
            <tr>
                <th scope="col" style="vertical-align: middle; width: 30%; max-width: 200px; overflow: hidden;"><?php _e('Key'); ?></th>
                <th scope="col"><?php _e('Value'); ?></th>
            </tr>
            </thead>
            <tbody>

            <?php
            $filter = [];
            if (isset($params['search'])) {
                $filter['search'] = $params['search'];
            }
            $supportedLanguages = get_supported_languages(true);
            $getTranslations = \MicroweberPackages\Translation\Models\Translation::getGroupedTranslations($filter);
            foreach ($getTranslations as $translationKey=>$translationByLocales):
                $translationKeyMd5 = md5($translationKey);
                ?>
                <tr style="border-bottom: 1px solid #cfcfcf">
                    <td style="vertical-align: middle; width: 30%; max-width: 200px; overflow: hidden;">
                        <div class="lang-key-holder">
                            <small><?php echo $translationKey;?></small>
                        </div>
                    </td>
                    <td style="vertical-align: middle;">
                        <?php
                        foreach ($supportedLanguages as $supportedLanguage):
                            ?>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1">
                                                                 <span class="flag-icon flag-icon-<?php echo $supportedLanguage['icon']; ?> m-r-10"></span>
                                                                </span>
                                </div>
                                <input type="hidden" name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][group]" value="*">
                                <input type="hidden" name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][namespace]" value="*">
                                <textarea name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][key]" style="display:none;"><?php echo $translationKey;?></textarea>
                                <textarea name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][text]" class="mw_lang_item_textarea_edit form-control form-control-sm" aria-label="" aria-describedby="basic-addon1" wrap="soft" rows="2"><?php if(isset($translationByLocales[$supportedLanguage['locale']])): echo $translationByLocales[$supportedLanguage['locale']]; else: echo $translationKey; endif; ?></textarea>
                            </div>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>


        </div>
    </div>
</div>