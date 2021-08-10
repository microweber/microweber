<?php include 'generated_vars.php'; ?>
<script>
    mw.lib.require('colorpicker');
</script>
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

<div id="settings-holder" data-mwcomponent="accordion" class="mw-ui-box mw-accordion">
    <div class="col-12">
        <h5 style="font-weight: bold;">Template Settings </h5>
    </div>
    <?php //dd($template_settings); ?>

    <div class="bootstrap3ns">
        <?php if ($template_settings): ?>

            <?php foreach ($template_settings as $key => $setting): ?>
                <mw-accordion-item>
                    <div class="mw-ui-box-header mw-accordion-title">
                        <?php if ($setting['type'] == 'title') { ?>
                            <?php echo $setting['label']; ?> <?php if (isset($setting['help'])): ?><span class="tip" data-tip="<?php echo $setting['help']; ?>">(<span class="red">?</span>)</span><?php endif; ?>
                        <?php } ?>
                    </div>

                    <div class="mw-accordion-content mw-ui-box-content">
                    <?php if ($setting['type'] == 'delimiter'): ?>
                        <hr/>
                        <?php elseif ($setting['type'] == 'text'): ?>
                            <div class="form-group ">
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
                        <?php elseif ($setting['type'] == 'dropdown_image'): ?>
                            <style>
                                .image-option .background-option {
                                    background: transparent;
                                    display: inline-block;
                                    width: 100%;
                                    height: 20px;
                                    z-index: 999;
                                    background-repeat: repeat-x;
                                }

                                .image-option span.text {
                                    height: 20px;
                                    display: block;
                                    width: 100%;
                                }

                                .image-option li.active,
                                .image-option a.active {
                                    background: #e2e2e2 !important;
                                }
                            </style>

                            <div class="form-group image-option ">
                                <label for="<?php echo $key; ?>" class="control-label"><?php echo $setting['label']; ?> <?php if (isset($setting['help'])): ?><span class="tip" data-tip="<?php echo $setting['help']; ?>">(<span class="red">?</span>)</span><?php endif; ?></label>
                                <div>
                                    <select name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="mw_option_field form-control" data-option-group="<?php print $option_group; ?>">
                                        <?php if (isset($setting['options'])): ?>
                                            <?php foreach ($setting['options'] as $option_key => $option): ?>
                                                <?php
                                                $image_dir = template_dir() . 'assets' . DS . 'img' . DS . 'settings' . DS . $option_key;
                                                $image_path = template_url() . 'assets/img/settings/' . $option_key;

                                                if (is_file($image_dir . '.jpg')) {
                                                    $image_dir = $image_dir . '.jpg';
                                                    $image_path = $image_path . '.jpg';
                                                } else if (is_file($image_dir . '.png')) {
                                                    $image_dir = $image_dir . '.png';
                                                    $image_path = $image_path . '.png';
                                                }
                                                if (is_file($image_dir)) {
                                                    $optionView = "<img src='" . $image_path . "' />";
                                                } else {
                                                    $optionView = $option;
                                                }
                                                ?>
                                                <option title='<?php echo $option; ?>' data-content="<span class='background-option'><?php echo $optionView; ?></span>"
                                                        value="<?php echo $option_key; ?>" <?php if (isset($option_key) AND isset($$key) AND $option_key == $$key) {
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
                    </div>
                <mw-accordion-item>
            <?php endforeach; ?>
        <?php endif; ?>

        <hr/>
        <div class="form-group text-center">
            <span class="mw-ui-btn mw-ui-btn-medium mw-full-width" onclick="resetToDefault();" style="margin-top: 4px;"><?php _e("Reset Template Settings"); ?></span>
        </div>
    </div>
</div>
<!-- /#settings-holder -->
