<style>
    .multilanguage-display-icon-custom {
        max-width: 18px;
        max-height: 18px;
        margin-right: 5px;
        margin-top: 3px;
    }
</style>

<?php if (!empty($supported_languages)): ?>
    <?php

    $current_language_flag = get_flag_icon($current_language['locale']);


    ?>
    <li class="mx-1 language-selector">
        <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon" data-toggle="dropdown" style="padding: 10px 0px;">
            <i class="flag-icon flag-icon-<?php print $current_language['icon']; ?>" style="font-size: 30px"></i>
        </button>
        <div class="dropdown-menu dropdown-languages">
            <?php foreach ($supported_languages as $language): ?>
                <button onclick='mw.admin.language("<?php print $language['locale'] ?>");' class="dropdown-item <?php if ($current_language['locale'] == get_short_abr($language['locale'])): ?>active<?php endif; ?>">
                    <!-- custom display icon -->
                    <?php if (!empty($language['display_icon'])): ?>
                        <img src="<?php echo $language['display_icon']; ?>" class="multilanguage-display-icon-custom d-inline"/>
                    <?php else: ?>
                        <i class="flag-icon flag-icon-<?php echo get_flag_icon($language['locale']); ?>"></i>
                    <?php endif; ?>
                    <!--- end of display icon -->

                    <!-- custom display name -->
                    <?php if (!empty($language['display_name'])): ?>
                        <span class="text-uppercase"><?php echo $language['display_name']; ?></span>
                    <?php else: ?>
                        <span class="text-uppercase">
                            <?php echo  \MicroweberPackages\Translation\LanguageHelper::getDisplayLanguage($language['locale']); ?>

                           </span>
                    <?php endif; ?>
                    <!--- end of display name -->
                </button>
            <?php endforeach; ?>

            <?php if (isset($params['show_settings_link']) && $params['show_settings_link'] == true): ?>
                <a class="dropdown-item" href="<?php echo admin_url() ?>view:modules/load_module:multilanguage" onclick="window.location.href = '<?php echo admin_url() ?>view:modules/load_module:multilanguage';">
                    <?php _e('Settings'); ?>
                </a>
            <?php endif; ?>
        </div>
    </li>
<?php endif; ?>
