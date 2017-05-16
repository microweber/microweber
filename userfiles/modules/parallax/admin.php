<?php only_admin_access(); ?>

<?php
$parallax = get_option('parallax', $params['id']);
$infoImage = get_option('info-image', $params['id']);
$height = get_option('height', $params['id']);
$alpha = get_option('alpha', $params['id']);
?>

<script type="text/javascript">
    $(document).ready(function () {
        mw.lib.require('bootstrap3ns');
    });
</script>

<div class="module-live-edit-settings">
    <div class="bootstrap3ns">

        <div class="row">
            <div class="col-xs-12">
                <label class="mw-ui-label"><?php _e('Upload image'); ?></label>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="mw-ui-field-holder">
                            <span class="mw-ui-btn" id="parallax"><span class="mw-icon-upload"></span><?php _e('Choose parallax image'); ?></span>
                        </div>
                    </div>
                    <input type="hidden" class="mw_option_field" name="parallax" id="parallaxval" value="<?php print $parallax; ?>"/>

                    <div class="col-xs-6">
                        <div class="mw-ui-field-holder">
                            <span class="mw-ui-btn" id="info-image"><span class="mw-icon-upload"></span><?php _e('Choose Info image'); ?></span>
                        </div>
                    </div>
                    <input type="hidden" class="mw_option_field" name="info-image" id="infoimageval" value="<?php print $infoImage; ?>"/>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="height"><?php _e('Full width parallax height'); ?></label>
                            <input name="height" data-refresh="parallax" class="form-control mw_option_field" type="number" value="<?php print get_option('height', $params['id']) ?>" id="height">
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="alpha"><?php _e('Alpha: 0-1'); ?></label>
                            <input name="alpha" data-refresh="parallax" class="form-control mw_option_field" type="range" value="<?php print get_option('alpha', $params['id']) ?>" id="alpha" min="0" max="1" step=".01"/>
                        </div>
                    </div>
                </div>

                <!--                <div class="form-group">-->
                <!--                    <label class="control-label" for="text">--><?php //_e('Text'); ?><!--</label>-->
                <!--                    <textarea name="text" data-refresh="parallax" class="form-control mw_option_field" id="text" rows="10" style="height: 100px;">-->
                <?php //print get_option('text', $params['id']) ?><!--</textarea>-->
                <!--                </div>-->
                <!---->

                <!---->
                <!--                <div class="form-group">-->
                <!--                    <label class="control-label" for="button-url">--><?php //_e('Button URL'); ?><!--</label>-->
                <!--                    <input name="button-url" data-refresh="parallax" class="form-control mw_option_field" type="text" value="-->
                <?php //print get_option('button-url', $params['id']) ?><!--" id="button-url">-->
                <!--                </div>-->

                <div class="row">
                    <div class="col-xs-12">
                        <module type="admin/modules/templates"/>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

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