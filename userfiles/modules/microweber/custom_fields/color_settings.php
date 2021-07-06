
<?php include('settings_header.php'); ?>

<script type="text/javascript">

    mw.require("files.js");
    mw.require("uploader.js");
    mw.lib.require('colorpicker');
    mw.require('filepicker.js');


    $(document).ready(function () {

        mw.element('#upload_thumb_btn').on('click', function (){
            var dialog;

            var picker = new mw.filePicker({
                type: 'images',
                label: false,
                autoSelect: false,
                footer: true,
                _frameMaxHeight: true,
                onResult: function (res) {
                    var url = res.src ? res.src : res;
                    if(!url) return;
                    url = url.toString();
                    $("#thumb").attr("src", url);
                    $("#upload_thumb_field").val(url).trigger('change');
                    dialog.remove();
                }
            });
            dialog = mw.top().dialog({
                content: picker.root,
                title: mw.lang('Select image'),
                footer: false
            });
        });


        $(".js-remove-thumb").on('click', function () {
            $("#upload_thumb_field").val('').trigger('change');
            $("#thumb").removeAttr('src')
        })


        $('#value<?php print $rand; ?>').keyup(function() {
            var color = $(this).val();
            $('#value<?php print $rand; ?>').val(color).css('background', color);
            $('#value<?php print $rand; ?>').trigger('change');
        });

/*        $('.js-color-input-method-picker').hide();
        $('.js-color-input-method-image-upload').hide();
        $('.js-color-input-method-color-palette').hide();

        $('.js-color-input-method-type').change(function() {

            var methodType = $(this).val();
            if (methodType == 'picker') {
                $('.js-color-input-method-picker').show();
                $('.js-color-input-method-image-upload').hide();
                $('.js-color-input-method-color-palette').hide();
            }
            if (methodType == 'palette') {
                $('.js-color-input-method-picker').hide();
                $('.js-color-input-method-image-upload').hide();
                $('.js-color-input-method-color-palette').show();
            }
            if (methodType == 'image') {
                $('.js-color-input-method-picker').hide();
                $('.js-color-input-method-image-upload').show();
                $('.js-color-input-method-color-palette').hide();
            }
        });*/

    });

</script>

<style>

    .custom-radio-checked-content{
        display: none;
    }
    :checked ~ .custom-radio-checked-content{
        display: block;
    }

</style>

<div class="custom-field-settings-values">
    <div class="form-group">
      <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Color"); ?></label>
       <small class="text-muted d-block mb-2"><?php _e('This attribute specifies the color');?></small>



        <input type="text" class="form-control" name="value" value="<?php print ($data['value']) ?>"  />


        <template>

            <input type="hidden" name="value" value="<?php print ($data['value']) ?>" id="value<?php print $rand; ?>" />

            <div class="form-group">
                <label class="custom-control custom-radio">
                    <input type="radio" name="customRadio" value="picker" class="custom-control-input">
                    <span class="custom-control-label"><?php _e('Picker'); ?></span>
                    <div class="custom-radio-checked-content">
                        <div class="js-color-input-method-picker">
                            <input type="color" class="form-control" autocomplete="off" value="<?php print ($data['value']) ?>" id="value<?php print $rand; ?>">
                        </div>
                    </div>
                </label>

                <label class="custom-control custom-radio">
                    <input type="radio" name="customRadio" value="palette" class="custom-control-input">
                    <span class="custom-control-label"><?php _e('Palette'); ?></span>
                    <div class="custom-radio-checked-content">
                        <select class="selectpicker" data-live-search="true" style="width:100%;">
                            <?php
                                foreach (mw()->ui->predefined_colors() as $color) {
                                    ?>
                                    <option data-hex="<?php echo $color['hex']; ?>" data-name="<?php echo $color['name']; ?>" data-tokens="<?php echo $color['name']; ?>" style="background: #<?php echo $color['hex']; ?>;">
                                        <?php echo $color['name']; ?>
                                    </option>
                                    <?php
                                }
                            ?>
                        </select>
                    </div>
                </label>

                <label class="custom-control custom-radio">
                    <input type="radio" name="customRadio" value="image" class="custom-control-input">
                    <span class="custom-control-label"><?php _e('Image'); ?></span>
                    <div class="custom-radio-checked-content">
                        <span class="btn btn-light" id="upload_thumb_btn"><span class="mdi mdi-cloud-upload"></span><?php _e('Add image'); ?></span>
                        <input name="upload_thumb" id="upload_thumb_field" class="form-control mw_option_field semi_hidden" type="text" data-mod-name="" value=""/>
                        <div class="mb2">
                            <img id="thumb" src="" alt="" style="max-width: 120px;"/><br/>
                            <span class="btn btn-link text-danger px-0 js-remove-thumb"><?php _e('Remove'); ?></span>
                        </div>
                    </div>
                </label>

            </div>
        </template>



       <input type="hidden" name="options[hex]" value="<?php print ($data['value']) ?>" id="color<?php print $rand; ?>">
    </div>
    <div class="custom-field-settings-values">
        <?php echo $savebtn; ?>
    </div>
</div>

<?php include('placeholder_settings.php'); ?>

<?php include('settings_footer.php'); ?>
