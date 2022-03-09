<style>
    .multilanguage-display-icon-custom {
        max-width: 18px;
        max-height: 18px;
        margin-right: 5px;
        margin-top: 3px;
    }
</style>

<script>
function changeAdminModuleLanguage(language) {
    $.post(mw.settings.api_url + "multilanguage/change_language", {locale: language, is_admin: 1})
        .done(function (data) {
            mw.notification.success('Reloading..');
            mw.reload_module_everywhere('<?php echo $params['reload_module']; ?>');
        });
    return false;
}
</script>

<?php if (!empty($supported_languages)): ?>
    <?php $current_language_flag = $current_language['locale'];
    if ($current_language_flag == 'en') {
        $current_language_flag = 'gb';
    }
    ?>
    <li class="mx-1 language-selector">
        <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon" data-toggle="dropdown" style="padding: 10px 0px;">
            <i class="flag-icon flag-icon-<?php echo get_flag_icon($current_language['locale']); ?>" style="font-size: 30px"></i>
        </button>
        <div class="dropdown-menu dropdown-languages">
            <?php foreach ($supported_languages as $language): ?>
                <button type="button" onclick='changeAdminModuleLanguage("<?php print $language['locale'] ?>");' class="dropdown-item <?php if ($current_language['locale'] == get_short_abr($language['locale'])): ?>active<?php endif; ?>">
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
                        <span class="text-uppercase"><?php echo \Symfony\Component\Intl\Languages::getName($language['locale']); ?></span>
                    <?php endif; ?>
                    <!--- end of display name -->
                </button>
            <?php endforeach; ?>
        </div>
    </li>
<?php endif; ?>
