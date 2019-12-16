<?php
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
        if ($$key === false AND $$key !== null) {
            if (isset($setting['default'])) {
                $$key = $setting['default'];
            } else {
                $$key = '';
            }
        } elseif ($$key == null) {
            $$key = '';
        }
    }
}

?>


<style>
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
        border: 1px solid transparent;
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

<script>mw.lib.require('bootstrap3ns');</script>

<script>
    $(document).ready(function () {
        mw.options.form("#settings-holder", function () {
            reloadTemplate();
        });
    });

    function reloadTemplate() {
        parent.mw.notification.success("<?php _ejs("Template settings are saved"); ?>.");
        parent.$("#theme-style").attr('href', '<?php print mw()->template->get_stylesheet($template_settings['stylesheet_compiler']['source_file'], false, false); ?>&t=' + mw.random());
        mw.tools.refresh(parent.$("#theme-style"));
    }

    function deleteCompiledCSS() {
        $.get(mw.settings.api_url + "template/delete_compiled_css?path=<?php print $template_settings['stylesheet_compiler']['source_file']; ?>&option_group=<?php print $option_group; ?>", function () {
            // Delete
            reloadTemplate();
            window.parent.mw.drag.save();
            window.parent.location.reload(false);
        });
    }
</script>


<div id="settings-holder">
    <div class="col-12">
        <h4 style="font-weight: bold;"><?php _e("Stylesheet Settings"); ?></h4>
    </div>

    <div class="bootstrap3ns">
        <?php if ($stylesheet_settings): ?>
            <?php foreach ($stylesheet_settings as $key => $setting): ?>
                <?php if ($setting['type'] == 'title'): ?>
                    <h5><?php echo $setting['label']; ?> <?php if (isset($setting['help'])): ?><span class="tip" data-tip="<?php echo $setting['help']; ?>">(<span class="red">?</span>)</span><?php endif; ?></h5>
                <?php elseif ($setting['type'] == 'delimiter'): ?>
                    <hr/>
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
                    <div class="form-group">
                        <label class="control-label mw-ui-label"><?php echo $setting['label']; ?> <?php if (isset($setting['help'])): ?><span class="tip" data-tip="<?php echo $setting['help']; ?>">(<span class="red">?</span>)</span><?php endif; ?></label>
                        <input class="form-control mw-ui-field mw_option_field" name="<?php echo $key; ?>" value="<?php echo $$key ?>" data-option-group="<?php echo $option_group; ?>" placeholder="Default: <?php echo $setting['default']; ?>">
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <hr/>
        <div class="form-group text-center">
            <span class="mw-ui-btn mw-ui-btn-medium mw-full-width" onclick="deleteCompiledCSS();" style="margin-top: 4px;"><?php _e("Reset Stylesheet Settings"); ?></span>
        </div>
        <hr/>
    </div>
</div>
