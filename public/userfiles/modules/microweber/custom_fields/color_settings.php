
<?php include('radio_settings.php');

return;
?>
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
                    $('[name="value"]').val(url).trigger('change');
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
            $('[name="value"]').val('').trigger('change');
            $("#thumb").removeAttr('src')
        })


        $('#value<?php print $rand; ?>').keyup(function() {
            var color = $(this).val();
            $('#value<?php print $rand; ?>').val(color).css('background', color);
            $('#value<?php print $rand; ?>').trigger('change');
        });

        mw.element('.js-color-input-method-picker input[type="color"]').on('input', function (){
           mw.element('#value<?php print $rand; ?>').val(this.value)
           this.parentNode.previousElementSibling.value = this.value

        })


    });

</script>

<style>

    .custom-radio-checked-content{
        display: none;
    }
    :checked ~ .custom-radio-checked-content{
        display: block;
    }

    .palette-picker{
        max-height: 50vh;
        overflow: auto;
        padding: 12px;
        background: #fff;
        border: 1px solid #cfcfcf;
        border-radius: 5px;
    }

    .palette-picker > span span{
        display: inline-block;
        border-radius: 20px;
        box-shadow: inset 0 0 10px rgba(0,0,0,.2);
        width: 20px;
        height: 20px;
        margin: 0 10px 0 0;
    }
    .palette-picker > span{
        cursor: pointer;
        display: inline-flex;
        margin: 5px 0;
        align-items: center;
        transition: .3s;
        width: 47%;

    }
    .input-group-append > [type="color"] {
        height: 100%;
        background: #fff;
        border: 1px solid #cfcfcf;
    }

    .js-color-input-method-picker .img-circle-holder{
        border-color: #0a53be;
    }
    .js-color-input-method-picker{
        width: 150px;
    }

    .image-color-col{
        padding: 10px 20px 10px 0;
    }
    .image-color-row{
        display: flex;
    }

    .img-circle-holder{
        border: 1px solid #cfcfcf;
    }

</style>




<div class="custom-field-settings-values">
    <div class="mw-custom-field-group">
        <label class="form-label">Color name</label>
        <small class="text-muted d-block mb-2">Used for filtering purposes</small>

        <div id="mw-custom-fields-text-holder">
            <input type="text" class="form-control" name="placeholder" value="" placeholder="e.g.: Cinnamon Satin">
        </div>
    </div>

    <div class="form-group">
      <label class="form-label" for="value<?php print $rand; ?>"><?php _e("Color"); ?></label>
       <small class="text-muted d-block mb-2"><?php _e('This attribute specifies the color');?></small>
            <input type="hidden" name="value" value="<?php print ($data['value']) ?>" id="value<?php print $rand; ?>" />





            <div>
                <div class="form-group">
                    <label class="custom-control custom-radio my-2">
                        <input type="radio" name="customRadio" value="picker" class="form-check-input">
                        <span class="custom-control-label"><?php _e('Picker'); ?></span>
                        <div class="custom-radio-checked-content">
                            <div class="js-color-input-method-picker">
                                <div class="input-group">
                                    <input type="text" class="form-control color-value-text" autocomplete="off" value="<?php print ($data['value']) ?>">
                                    <div class="input-group-append">
                                        <input type="color" autocomplete="off" value="<?php print ($data['value']) ?>" id="value<?php print $rand; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="custom-control custom-radio my-2">
                        <input type="radio" name="customRadio" value="palette" class="form-check-input">
                        <span class="custom-control-label"><?php _e('Palette'); ?></span>
                        <div class="custom-radio-checked-content">
                            <div class="palette-picker">
                                <?php foreach (mw()->ui->predefined_colors() as $color) { ?>
                                    <span data-hex="<?php echo $color['hex']; ?>">
                                        <span  style="background: #<?php echo $color['hex']; ?>;"></span>
                                        <?php echo $color['name']; ?>
                                    </span>
                                <?php } ?>
                            </div>

                        </div>
                    </label>

                    <label class="custom-control custom-radio my-2">
                        <input type="radio" name="customRadio" value="image" class="form-check-input">
                        <span class="custom-control-label"><?php _e('Image'); ?></span>
                        <div class="custom-radio-checked-content">
                            <input name="upload_thumb" id="upload_thumb_field" class="form-control mw_option_field semi_hidden" type="text" data-mod-name="" value=""/>
                            <div class="image-color-row">
                                <div class="image-color-col">
                                    <div class="img-circle-holder border-radius-10">
                                        <img id="thumb" src="<?php print str_contains($data['value'], '.') ? $data['value'] : pixum(120,120);   ?>" alt="" style="max-width: 120px;"/>
                                    </div>
                                    <span class="btn btn-link text-danger px-0 js-remove-thumb"><?php _e('Remove'); ?></span>
                                </div>
                                <div class="image-color-col">
                                    <span class="btn btn-outline-secondary" id="upload_thumb_btn"><span class="mdi mdi-cloud-upload"></span>  <?php _e('Add image'); ?></span>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
       <input type="hidden" name="options[hex]" value="<?php print ($data['value']) ?>" id="color<?php print $rand; ?>">
    </div>
    <div class="custom-field-settings-values"><?php echo $savebtn; ?></div>
</div>

<?php include('placeholder_settings.php'); ?>

<?php include('settings_footer.php'); ?>
