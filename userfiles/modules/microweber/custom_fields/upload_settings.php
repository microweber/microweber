<?php include('settings_header.php'); ?>
<div class="custom-field-settings-name">
    <div class="mw-custom-fields-upload-filetypes">
        <label class="control-label"><small><?php _e("Allowable Format for upload"); ?></small></label>
        <div class="mw-ui-field-holder">
            <label class="mw-ui-check">
                <input type="checkbox"  name="options[file_types]" <?php if(isset($settings['options']) and isset($settings['options']['file_types']) and in_array('images',$settings['options']['file_types'])) : ?> checked <?php endif; ?> value="images"  />
                <span></span>
                <span><?php _e("Images Files"); ?></span>
            </label>
        </div>

        <div class="mw-ui-field-holder">
            <label class="mw-ui-check">
                <input type="checkbox"  name="options[file_types]" <?php if(isset($settings['options']) and isset($settings['options']['file_types']) and in_array('documents',$settings['options']['file_types'])) : ?> checked <?php endif; ?>  value="documents" />
                <span></span>
                <span><?php _e("Document Files"); ?></span>
            </label>
        </div>

        <div class="mw-ui-field-holder">
            <label class="mw-ui-check">
                <input type="checkbox"  name="options[file_types]" <?php if(isset($settings['options']) and isset($settings['options']['file_types']) and in_array('archives',$settings['options']['file_types'])) : ?> checked <?php endif; ?>  value="archives" />
                <span></span>
                <span><?php _e("Archive Files"); ?></span>
            </label>
        </div>

        <div class="mw-ui-field-holder">
            <label class="control-label"><?php _e("Custom File Types"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e('Specifies the custom file type');?></small>
            <input type="text" class="form-control js-custom-field-types" value="<?php if(isset($settings['options']) and isset($settings['options']['file_types']) and is_array($settings['options']['file_types'])) : ?><?php

                $array2 = array("images", "documents", "archives");
                $oresult = array_diff( $settings['options']['file_types'], $array2 );
                $xresult = [];
                foreach ($oresult as $restype) {
                    $restype = trim($restype);
                    if (empty($restype)) {
                        continue;
                    }
                    $xresult[] = $restype;
                }
                $xresult = array_unique($xresult);
                echo implode(',', $xresult);
                ?><?php endif; ?>" placeholder='psd,html,css' />
        </div>
        <div class="js-custom-field-types-append"></div>
        <script>
            $('.js-custom-field-types').change(function() {
                $('.js-custom-field-types-append').html('');
                var customFieldTypesText = $(this).val();
                var customFieldTypes = customFieldTypesText.split(',');
                $.each(customFieldTypes, function(index, customFieldType) {
                    if (customFieldType == '' || customFieldType == ' ') {
                        return;
                    }
                    $('.js-custom-field-types-append').append('<input type="hidden" name="options[file_types]" value="'+customFieldType+'" />');
                });
            });
        </script>
    </div>
</div>

<div class="custom-field-settings-values">
    <?php echo $savebtn; ?>
</div>

<?php include('placeholder_settings.php'); ?>
<?php include('settings_footer.php'); ?>