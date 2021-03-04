<?php must_have_access(); ?>

<?php
$option_group = $params['id'];

if (isset($params['option_group'])) {
    $option_group = $params['option_group'];
}

$mailProviders = get_modules('type=mail_provider');
?>
<?php if (!empty($mailProviders)): ?>
    <div class="form-group mb-1">
        <label class="control-label"><?php _e("Mail providers"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Choose mail providers to save your contacts information data"); ?></small>
    </div>

    <div>
        <?php foreach ($mailProviders as $mailProvider): ?>
            <div class="form-group mb-1">
                <div class="custom-control custom-checkbox d-inline-block mr-2">
                    <input type="checkbox" parent-reload="true" value="y" name="use_integration_with_<?php echo strtolower($mailProvider['name']); ?>" id="use_integration_with_<?php echo strtolower($mailProvider['name']); ?>" class="mw_option_field custom-control-input" option-group="<?php echo $option_group; ?>" <?php if (get_option('use_integration_with_' . strtolower($mailProvider['name']), $option_group) == 'y'): ?>checked<?php endif; ?> />
                    <label class="custom-control-label" for="use_integration_with_<?php echo strtolower($mailProvider['name']); ?>"><?php echo $mailProvider['name']; ?></label>
                </div>

                <button type="button" class="btn btn-link btn-sm px-0" onclick="mw.tools.open_module_modal('<?php print $mailProvider['module'] ?>/admin', {}, {title: '<?php print $mailProvider['name'] ?>',height:500,overlay: true, skin: 'simple'}); return false;"><?php _e("Settings"); ?></button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>