<?php
only_admin_access();
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.js-option-field-change-is-active').change(function (e) {
            is_checked = $(this).is(':checked');
            supported_language_id = $(this).attr('supported_language_id');

            $.post("<?php print site_url('api/multilanguage/supported_locale/set_active'); ?>", {is_active: is_checked, id: supported_language_id}, function (msg) {
                if (is_checked === true) {
                    mw.notification.success('<?php _ejs('Language is enabled!'); ?>');
                } else {
                    mw.notification.error('<?php _ejs('Language is disabled!'); ?>');
                }
                mw.reload_module_everywhere('multilanguage');
            });
        });
    });
</script>


<div class="table-responsive">
    <table class="table card-table table-vcenter">
        <thead>
            <tr>
<!--                <th style="min-width: 25px;"></th>-->
                <th style="min-width: 50px;"><?php echo _e('Active'); ?></th>
                <th><?php echo _e('Language'); ?></th>
                <th><?php echo _e('Display as'); ?></th>
                <th class="text-center"><?php echo _e('Reorder'); ?></th>
                <th class="text-center"><?php echo _e('Actions'); ?></th>
            </tr>
        </thead>
        <tbody class="js-tbody-supported-locales">
            <?php
            $defaultLang = mw()->lang_helper->default_lang();
            $supportedLanguages = get_supported_languages();
            if (!empty($supportedLanguages)):
                $isl = 1;
                foreach ($supportedLanguages as $language):
                    ?>
                    <tr class="js-lang-tr-<?php echo $language['locale']; ?> show-on-hover-root">
<!--                        <td class="text-center px-0" style="vertical-align: middle;"><span class="mdi mdi-cursor-move mdi-20px show-on-hover text-opacity-5"></span></td>-->

                        <td class="text-center px-0" style="vertical-align: middle; width: 25px;">
                            <div class="custom-control custom-switch">
                                <input class="mw_option_field js-option-field-change-is-active form-check-input" type="checkbox" supported_language_id="<?php echo $language['id']; ?>" id="<?php echo $language['id']; ?>" autocomplete="off" value="y" name="is_active" <?php if ($language['is_active'] == 'y'): ?>checked<?php endif;
                                ?> data-value-checked="y" data-value-unchecked="n">
                                <label class="custom-control-label" for="<?php echo $language['id']; ?>"></label>
                            </div>
                        </td>

                        <td style="vertical-align: middle;">
                            <div data-bs-toggle="tooltip" title="<?php echo $language['language']; ?>">


                                <i class="flag-icon flag-icon-<?php echo get_flag_icon($language['locale']); ?> mr-2"></i>
                                <?php echo  \MicroweberPackages\Translation\LanguageHelper::getDisplayLanguage($language['locale']); ?> [<?php echo $language['locale']; ?>]
                                <?php if (strtolower($defaultLang) == strtolower($language['locale'])): ?>
                                    <small class="text-muted">(<?php _e('Default'); ?>)</small>
                                <?php endif; ?>
                            </div>
                        </td>

                        <td style="vertical-align: middle;">
                            <?php if ($language['display_icon']) : ?>
                                <img src="<?php echo $language['display_icon']; ?>" style="max-width:22px;max-height: 22px;" data-bs-toggle="tooltip" title="Display Icon"/>
                            <?php endif; ?>
                            <?php if ($language['display_locale']) : ?>
                                <span data-bs-toggle="tooltip" title="Display Locale">[<?php echo $language['display_locale']; ?>]</span>
                            <?php endif; ?>
                            <?php if ($language['display_name']): ?>
                                <span data-bs-toggle="tooltip" title="Display Icon" class="text-muted"><?php echo $language['display_name']; ?></span>
                            <?php endif; ?>

                        </td>

                        <td style="vertical-align: middle;">
                            <div class="show-on-hover d-flex justify-content-center">
                                <input class="js-supported-language-order-numbers js-supported-language-order-number-<?php echo $language['id']; ?>" name="<?php echo $language['id']; ?>" data-initial-value="<?php echo $isl; ?>" value="<?php echo $isl; ?>" type="number" style="display:none;font-size:22px;border: 0px;width: 35px;" min="1">
                                <a href="javascript:;" onclick="updateOrderNumber(<?php echo $language['id']; ?>, 'down')"><span class="js-update-order-number text-muted mx-1"> <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" width="20"><path d="M444 864V426L243 627l-51-51 288-288 288 288-51 51-201-201v438h-72Z"/></svg></span></a>
                                <a href="javascript:;" onclick="updateOrderNumber(<?php echo $language['id']; ?>, 'up')"><span class="js-update-order-number text-muted"><svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" width="20"><path d="M480 864 192 576l51-51 201 201V288h72v438l201-201 51 51-288 288Z"/></svg></span></a>
                            </div>
                        </td>

                        <td class="text-right" style="vertical-align: middle;">
                            <div class="show-on-hover d-flex justify-content-end">
                                <a href="javascript:;" onClick="editSuportedLanguage('<?php echo $language['id']; ?>')" class="btn btn-outline-primary btn-sm p-1 mx-1"><?php echo _e('Edit'); ?></a>
                                <?php if ($defaultLang !== $language['locale']): ?>
                                    <a href="javascript:;" onClick="deleteSuportedLanguage('<?php echo $language['id']; ?>')" class="btn btn-outline-danger btn-sm p-1"><?php echo _e('Delete'); ?></a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php $isl++;   endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5"><?php _e('No supported languages found.'); ?></td>
                </tr>
<?php endif; ?>
        </tbody>
    </table>
</div>
