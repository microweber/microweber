<?php only_admin_access(); ?>

<?php
$parallax = get_option('parallax', $params['id']);
$infoImage = get_option('info-image', $params['id']);
$height = get_option('height', $params['id']);
$alpha = get_option('alpha', $params['id']);
?>

<script>
    mw.lib.require('font_awesome5');
</script>

<script>
    $(document).ready(function () {
        var parallax = mw.uploader({
            filetypes: "images,videos",
            multiple: false,
            element: "#parallax"
        });
        $(parallax).bind('FileUploaded', function (a, b) {
            mw.tools.preload(b.src);
            mw.$("#parallaxval").val(b.src).trigger('change');
        });

        var textImage = mw.uploader({
            filetypes: "images,videos",
            multiple: false,
            element: "#info-image"
        });
        $(textImage).bind('FileUploaded', function (a, b) {
            mw.tools.preload(b.src);
            mw.$("#infoimageval").val(b.src).trigger('change');
        });
    });
</script>

<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-parallax-settings">
                <div class="mw-ui-field-holder">
                    <div class="mw-ui-label"><?php _e('Upload Parallax image'); ?></div>
                    <span class="mw-ui-btn mw-ui-btn-info w100" id="parallax"><span class="fas fa-upload"></span> &nbsp; <?php _e('Choose image'); ?></span>
                    <input type="hidden" class="mw_option_field" name="parallax" id="parallaxval" value="<?php print $parallax; ?>"/>
                </div>

                <div class="mw-ui-field-holder">
                    <div class="mw-ui-label"><?php _e('Upload Info image'); ?></div>
                    <span class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline w100" id="info-image"><span class="fas fa-upload"></span> &nbsp; <?php _e('Choose Info image'); ?></span>
                    <input type="hidden" class="mw_option_field" name="info-image" id="infoimageval" value="<?php print $infoImage; ?>"/>
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label" for="height"><?php _e('Full width parallax height'); ?></label>
                    <input name="height" data-refresh="parallax" class="mw-ui-field mw_option_field mw-full-width" type="number" value="<?php print get_option('height', $params['id']) ?>" id="height">
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label" for="alpha"><?php _e('Alpha: 0-10'); ?></label>
                    <div class="range-slider">
                        <input name="alpha" data-refresh="parallax" class="mw-ui-field-range mw_option_field mw-full-width" max="1" min="0" step=".01" type="range" id="alpha" value="<?php print get_option('alpha', $params['id']) ?>"/>
                    </div>
                </div>
            </div>
            <!-- Settings Content - End -->
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>