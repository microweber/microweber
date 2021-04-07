<script>
    function exportTheSelectedLanguage(namespace) {

        var locale = $('.js-export-selected-locale-val').val();
        var format = $('.js-export-selected-file-format-val').val();

        $('.js-export-selected-locale-btn').attr('disabled','disabled');

        $.ajax({
            type: "POST",
            url: "<?php echo route('admin.language.export'); ?>",
            data: "namespace=" + namespace + "&locale=" + locale + "&format=" + format,
            success: function (data) {
                if (data.files[0].download) {
                    window.location = data.files[0].download;
                } else {
                    mw.notification.error("<?php _e("Can't export the language pack."); ?>");
                }
                $('.js-export-selected-locale-btn').removeAttr('disabled');
            }
        });
    }
</script>
<?php
$supportedLanguages = [];
if (function_exists('get_supported_languages')) {
    $supportedLanguages = get_supported_languages(true);
}

if(empty($supportedLanguages)){
    $currentLanguageAbr = mw()->lang_helper->default_lang();

    $supportedLanguages[] = [
        'icon'=>$currentLanguageAbr,
        'locale'=>$currentLanguageAbr
    ];
}
?>
<div class="my-2">
    <label class="control-label"><?php _e('Select the language to export:');?></label>
    <small class="text-muted d-block mb-3"><?php _e('If you want to export a .xlsx translated file you can export it from here.');?></small>
    <select class="form-control js-export-selected-locale-val">
        <?php
        foreach ($supportedLanguages as $supportedLanguage):
            ?>
            <option value="<?php echo $supportedLanguage['locale'];?>"><?php echo strtoupper($supportedLanguage['locale']); ?></option>
        <?php
        endforeach;
        ?>
    </select>
    <br />
    <label class="control-label"><?php _e('Export File Format:'); ?></label>
    <small class="text-muted d-block mb-3"><?php _e('Select the format file to export.');?></small>
    <select class="form-control js-export-selected-file-format-val">
        <option value="xlsx">.xlsx</option>
        <option value="json">.json</option>
    </select>
    <br />
    <button type="button" onclick="exportTheSelectedLanguage('<?php echo $params['namespace'];?>');" class="btn btn-success js-export-selected-locale-btn"><i class="mdi mdi-download"></i> <?php _e('Export & Download');?> </button>
</div>
