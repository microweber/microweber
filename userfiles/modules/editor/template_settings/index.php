<?php include 'generated_vars.php'; ?>

<script>mw.lib.require('bootstrap3ns');</script>
<script>
    function resetToDefault() {
        mw.tools.confirm_reset_module_by_id('<?php echo $option_group; ?>')
        window.parent.location.reload(false);
    }

</script>

<style>
    .bootstrap3ns .checkbox label, .bootstrap3ns .radio label {
        padding-left: 0;
    }
</style>


<div id="settings-holder">
    <div class="col-12">
        <h4 style="font-weight: bold;">Template Settings</h4>
    </div>
    <?php //d($template_settings); ?>
    <div class="bootstrap3ns">
        <?php if ($template_settings): ?>
            <?php foreach ($template_settings as $key => $setting): ?>
                <?php if ($setting['type'] == 'title'): ?>
                    <h5><?php echo $setting['label']; ?> <?php if (isset($setting['help'])): ?><span class="tip" data-tip="<?php echo $setting['help']; ?>">(<span class="red">?</span>)</span><?php endif; ?></h5>
                <?php elseif ($setting['type'] == 'delimiter'): ?>
                    <hr/>
                <?php elseif ($setting['type'] == 'text'): ?>
                    <div class="form-group">
                        <label class="control-label mw-ui-label"><?php echo $setting['label']; ?> <?php if (isset($setting['help'])): ?><span class="tip" data-tip="<?php echo $setting['help']; ?>"><span class="red">?</span></span><?php endif; ?></label>
                        <input class="form-control mw-ui-field mw_option_field" name="<?php echo $key; ?>" value="<?php echo $$key ?>" data-option-group="<?php echo $option_group; ?>" placeholder="Default: <?php echo $setting['default']; ?>">
                    </div>
                <?php elseif ($setting['type'] == 'dropdown'): ?>
                    <div class="form-group">
                        <label for="<?php echo $key; ?>" class="control-label"><?php echo $setting['label']; ?> <?php if (isset($setting['help'])): ?><span class="tip" data-tip="<?php echo $setting['help']; ?>">(<span class="red">?</span>)</span><?php endif; ?></label>
                        <div>
                            <select name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="mw_option_field form-control" data-option-group="<?php print $option_group; ?>">
                                <?php if (isset($setting['options'])): ?>
                                    <?php foreach ($setting['options'] as $option_key => $option): ?>
                                        <option value="<?php echo $option_key; ?>" <?php if (isset($option_key) AND isset($$key) AND $option_key == $$key) {
                                            echo 'selected';
                                        } ?>><?php echo $option; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                <?php elseif ($setting['type'] == 'font_selector'): ?>
                    <?php
                    $enabled_custom_fonts = get_option("enabled_custom_fonts", "template");

                    if (is_string($enabled_custom_fonts)) {
                        $enabled_custom_fonts_array = explode(',', $enabled_custom_fonts);
                        if (is_array($enabled_custom_fonts_array)) {
                            foreach ($enabled_custom_fonts_array as $font1) {
                                $setting['options'][$font1] = $font1;

                            }

                        }
                    }
                    ?>
                    <div class="form-group">
                        <label for="<?php echo $key; ?>" class="control-label"><?php echo $setting['label']; ?> <?php if (isset($setting['help'])): ?><span class="tip" data-tip="<?php echo $setting['help']; ?>">(<span class="red">?</span>)</span><?php endif; ?></label>
                        <div>
                            <select name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="mw_option_field form-control" data-option-group="<?php print $option_group; ?>">
                                <?php if (isset($setting['options'])): ?>
                                    <?php foreach ($setting['options'] as $option_key => $option): ?>
                                        <option value="<?php echo $option_key; ?>" <?php if (isset($option_key) AND isset($$key) AND $option_key == $$key) {
                                            echo 'selected';
                                        } ?>><?php echo $option; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <hr/>
        <div class="form-group text-center">
            <span class="mw-ui-btn mw-ui-btn-medium mw-full-width" onclick="resetToDefault();" style="margin-top: 4px;"><?php _e("Reset Template Settings"); ?></span>
        </div>
    </div>
</div>
<!-- /#settings-holder -->