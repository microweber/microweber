<?php

only_admin_access();

$template_settings = mw()->template->get_config();
$stylesheet_settings = false;
if (isset($template_settings['stylesheet_compiler']) AND isset($template_settings['stylesheet_compiler']['settings'])) {
    $stylesheet_settings = $template_settings['stylesheet_compiler']['settings'];
}


if (!$stylesheet_settings) {
    return;
}

$option_group = 'mw-template-' . mw()->template->folder_name();

if ($stylesheet_settings) {
    foreach ($stylesheet_settings as $key => $setting) {
        $$key = get_option($key, $option_group);
        if ($$key == false) {
            if (isset($setting['default'])) {
                $$key = $setting['default'];
            } else {
                $$key = '';
            }
        }
    }
}

?>
<script>
    mw.lib.require('colorpicker');
</script>

<style>
    #settings-container{
        padding: 0;
    }
    #color-scheme {
        display: none;
    }
    .theme-color-selector {
        width: 100%;
        display: block;
        line-height: 34px;
    }

    .theme-color-selector:after {
        width: 100%;
        display: block;
        clear: both;
        content: '';
    }

    .theme-color-selector button {
        width: 30px;
        height: 30px;
        background: #425cbb;
        margin: 3px;
        outline: none !important;
        border: 1px solid #000000;
        float: left;
        margin-right: 10px;
    }

    .bootstrap3ns .checkbox label, .bootstrap3ns .radio label {
        padding-left: 0;
    }
</style>

<script>
    mw.lib.require('bootstrap3ns');
    mw.lib.require('bootstrap_select');
</script>

<script>
    $(document).ready(function () {
        mw.options.form("#settings-holder-stylesheet-compiler", function () {
            mw.top().notification.success("<?php _ejs("Template settings are saved"); ?>.",3000);
            reloadTemplate();
        });
    });

    function reloadTemplate() {
        $.get(mw.settings.api_url + "template/delete_compiled_css?path=<?php print $template_settings['stylesheet_compiler']['source_file']; ?>&option_group=<?php print $option_group; ?>", function () {
            mw.top().notification.success("<?php _ejs("Reloading styles"); ?>.",7000);
            mw.parent().$("#theme-style").attr('href', '<?php print mw()->template->get_stylesheet($template_settings['stylesheet_compiler']['source_file'], false, false); ?>&t=' + mw.random());
            mw.tools.refresh(parent.$("#theme-style"));
        });
    }

    function deleteCompiledCSS() {
        if (confirm("Are you sure you want to reset stylesheet ?")) {
            $.get(mw.settings.api_url + "template/delete_compiled_css?path=<?php print $template_settings['stylesheet_compiler']['source_file']; ?>&option_group=<?php print $option_group; ?>&delete_options=true", function () {
                reloadTemplate();
                window.location.reload(false);
            });
        }
    }
</script>


<div id="settings-holder-stylesheet-compiler">


    <div class="bootstrap3ns">
        <?php if ($stylesheet_settings): ?>
            <div data-mw-component="accordion" class="mw-ui-box">
            <?php
                $count = -1;
                $total = count($stylesheet_settings);
                foreach ($stylesheet_settings as $key => $setting):
                $count++;
            ?>
                <?php if ($setting['type'] == 'title') { ?>
                    <?php if ($count > 0 ){ ?>
                        </div>
                        </mw-accordion-item>
                    <?php } ?>
                    <mw-accordion-item>
                        <div class="mw-accordion-title mw-ui-box-header"><?php echo $setting['label']; ?> <?php if (isset($setting['help'])): ?><span class="tip" data-tip="<?php echo $setting['help']; ?>">(<span class="red">?</span>)</span><?php endif; ?></div>
                        <div class="mw-accordion-content mw-ui-box-content">
                <?php } ?>


                <?php if ($setting['type'] == 'delimiter'): ?>

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
                <?php elseif ($setting['type'] == 'dropdown_background_image_x'): ?>
                    <style>
                        .background-x-option .background-option {
                            background: transparent;
                            display: inline-block;
                            width: 100%;
                            height: 20px;
                            z-index: 999;
                            background-repeat: repeat-x;
                        }

                        .background-x-option span.text {
                            height: 20px;
                            display: block;
                            width: 100%;
                        }

                        .background-x-option li.active,
                        .background-x-option a.active {
                            background: #e2e2e2 !important;
                        }
                    </style>

                    <div class="form-group background-x-option">
                        <label for="<?php echo $key; ?>" class="control-label"><?php echo $setting['label']; ?> <?php if (isset($setting['help'])): ?><span class="tip" data-tip="<?php echo $setting['help']; ?>">(<span class="red">?</span>)</span><?php endif; ?></label>
                        <div>
                            <select name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="mw_option_field form-control selectpicker" data-option-group="<?php print $option_group; ?>">
                                <?php if (isset($setting['options'])): ?>
                                    <?php foreach ($setting['options'] as $option_key => $option): ?>
                                        <?php
                                        $image_path = str_replace("'", '', $option_key);
                                        $image_path = str_replace("../", '', $image_path);
                                        ?>
                                        <option title='<?php echo $option; ?>' data-content="<span class='background-option' style='background-image: url(<?php echo template_url(); ?>assets/<?php echo $image_path; ?>);'></span>"
                                                value="<?php echo $option_key; ?>" <?php if (isset($option_key) AND isset($$key) AND $option_key == $$key) {
                                            echo 'selected';
                                        } ?>><?php echo $option; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                <?php elseif ($setting['type'] == 'color'): ?>
                    <div class="form-group" style="margin-bottom:5px;">
                        <div class="theme-color-selector">
                            <button style="background: <?php echo $$key ?>;" id="<?php echo $key; ?>"></button>
                            <input class="mw-ui-field mw_option_field hidden" name="<?php echo $key; ?>" value="<?php echo $$key ?>" data-option-group="<?php echo $option_group; ?>" placeholder="Default color: <?php echo $setting['default']; ?>">
                            <?php echo $setting['label']; ?> <?php if (isset($setting['help'])): ?><span class="tip" data-tip="<?php echo $setting['help']; ?>">(<span class="red">?</span>)</span><?php endif; ?>
                        </div>

                        <script>
                            $(window).load(function () {
                                mw.colorPicker({
                                    element: '#<?php echo $key; ?>',
                                    value: $('input[name="<?php echo $key; ?>"]').val(),
                                    position: 'bottom-left',
                                    onchange: function (color) {
                                        $('#<?php echo $key; ?>').css('background', color);
                                        $('input[name="<?php echo $key; ?>"]').val(color);
                                        $('input[name="<?php echo $key; ?>"]').trigger('change');
                                    }
                                });
                            });
                        </script>
                    </div>
                <?php elseif ($setting['type'] == 'text'): ?>
                    <div class="form-group my-2">
                        <label class="control-label mw-ui-label"><?php echo $setting['label']; ?> <?php if (isset($setting['help'])): ?><span class="tip" data-tip="<?php echo $setting['help']; ?>">(<span class="red">?</span>)</span><?php endif; ?></label>
                        <input class="form-control mw-ui-field mw_option_field" name="<?php echo $key; ?>" value="<?php echo $$key ?>" data-option-group="<?php echo $option_group; ?>" placeholder="Default: <?php echo $setting['default']; ?>">
                    </div>
                <?php elseif ($setting['type'] == 'font_selector'): ?>
                    <?php
                    $enabled_custom_fonts = \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts();

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

                    <button type="button" class="btn btn-primary btn-sm btn-block m-b-20" onclick="window.mw.parent().drag.module_settings('#font_family_selector_main','admin');">Load more fonts</button>
                <?php endif; ?>
                <?php if ($total === $count) { ?>
                    </div>
                    </mw-accordion-item>
                <?php } ?>

            <?php endforeach; ?>
            </div>
        <?php endif; ?>



    </div>
    </div>

<div class="form-group text-center">
    <span class="btn btn-outline-secondary btn-sm justify-content-center d-block mt-2" onclick="deleteCompiledCSS();"><?php _e("Reset Stylesheet Settings"); ?></span>
</div>
</div>

