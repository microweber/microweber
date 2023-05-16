<?php
$languages = \MicroweberPackages\Translation\LanguageHelper::getLanguagesWithDefaultLocale();
?>

<script type="text/javascript">
    $(document).ready(function () {
        mw.dropdown();
        add_language_key = false;
        add_language_value = false;

        $('.js-add-language').on('click', function () {

            $('.js-add-language').html('<?php _ejs('Importing the language..'); ?>');

            if (typeof(mw.notification) != 'undefined') {
                mw.notification.success('Adding language...',10000);
            }

            if (add_language_key == false || add_language_value == false) {
                mw.notification.error('<?php _ejs('Please, select language.'); ?>');
                return;
            }

            $.post(mw.settings.api_url + "multilanguage/add_language", {locale: add_language_key, language: add_language_value}).done(function (data) {

                $('.js-add-language').html('<?php _ejs('Add'); ?>');

                if (typeof(mw.notification) != 'undefined') {
                    mw.notification.success('Language added...',10000);
                }

                mw.trigger('mw.multilanguage.admin.language_added');

                mw.reload_module_everywhere('multilanguage');
                mw.reload_module_everywhere('multilanguage/language_settings', function () {
                });
            });
        });

        $('#add_language_ul').on('change', function () {
            var selectedOption = $(this).find('option:selected');
            var key = selectedOption.data('key');
            var value = selectedOption.data('value');
            add_language_key = key;
            add_language_value = value;
        });

    });

</script>

<div>
    <div class="form-group">
        <label class="form-label d-block"><?php _e('Add new language'); ?></label>

        <div class="d-flex align-items-center">
            <?php if ($languages) : ?>

                <script>
                    $(document).ready(function() {
                        var settings = {};
                        new TomSelect('.js-dropdown-text-language',settings);
                    });
                </script>

                <select autocomplete="off" class="js-dropdown-text-language  form-select" id="add_language_ul" data-size="5" data-live-search="true">
                    <option>
                        <?php _e('Select language'); ?>
                    </option>

                    <?php foreach ($languages as $languageName => $languageDetails): ?>
                        <option value="<?php echo $languageDetails['locale'] ?>" data-key="<?php echo $languageDetails['locale'] ?>" data-value="<?php echo $languageName ?>" style="color:#000;">
                            <span class="flag-icon flag-icon-fr m-r-10"></span> <?php echo $languageName; ?> [<?php echo $languageDetails['locale'] ?>]
                        </option>


                        <?php if(isset($languageDetails['locales']) and !empty($languageDetails['locales']) and count($languageDetails['locales']) > 1 ): ?>

                            <?php

                            if(is_array($languageDetails['locales'])){
                                foreach ($languageDetails['locales'] as $languageName2 => $locale2){
                                    ?>
                                    <option value="<?php echo $languageName2 ?>" data-key="<?php echo $languageName2 ?>" data-value="<?php echo $locale2 ?>"  style="color:#000;">
                                        <span class="flag-icon flag-icon-fr m-r-10"></span> <?php echo $languageDetails['text']; ?> <?php echo $locale2; ?>  [<?php echo $languageName2; ?>]
                                    </option>
                                    <?php
                                }
                            }


                            ?>
                        <?php endif; ?>



                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

           <div class="ms-2">
               <button class="btn btn-primary js-add-language "><?php _e('Add'); ?></button>
           </div>
        </div>
    </div>
</div>
