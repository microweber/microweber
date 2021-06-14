
<?php include('settings_header.php'); ?>

<script type="text/javascript">

    mw.require("files.js");
    mw.require("uploader.js");
    mw.lib.require('colorpicker');

    color = mw.colorPicker({
        element: '#value<?php print $rand; ?>',
        position: 'bottom-left',
        onchange: function (color) {
            $('#value<?php print $rand; ?>').val(color).css('background', color);
            $('#value<?php print $rand; ?>').trigger('change');
        }
    });

    $(document).ready(function () {

        var upThumb = mw.upload({
            multiple: false,
            filetypes: 'images',
            element: '#upload_thumb_btn'
        });

        $(upThumb).on("FileUploaded", function (a, b) {
            fileTypes = 'images';
            uploadFieldId = 'upload_thumb_field';
            uploadStatusId = 'upload_thumb_status';
            uploadBtnId = 'upload_thumb_btn';

            $("#thumb").attr("src", b.src);
            $("#upload_thumb_field").val(b.src).trigger('change');

            mw.tools.refresh_image(document.getElementById('thumb'));
            mw.tools.refresh(document.getElementById('chk_autoplay'));
        });

        $(".js-remove-thumb").on('click', function () {
            $("#upload_thumb_field").val('').trigger('change');
            $("#thumb").removeAttr('src')
        })
        $(upThumb).on("progress", function (a, b) {
            $("#upload_thumb_status").find('.progress-bar').width('0%');
            $("#upload_thumb_status").show();
            $("#upload_thumb_status").find('.progress-bar').width(b.percent + '%');
            if (b.percent == 100) { 
                $("#upload_thumb_status").hide();
            }
        });

        $('#value<?php print $rand; ?>').keyup(function() {
            var color = $(this).val();
            $('#value<?php print $rand; ?>').val(color).css('background', color);
            $('#value<?php print $rand; ?>').trigger('change');
        });

        $('.js-color-input-method-picker').hide();
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
        });

    });

</script>

<div class="custom-field-settings-values">
    <div class="form-group">
      <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Color"); ?></label>
       <small class="text-muted d-block mb-2"><?php _e('This attribute specifies the color');?></small>

        <div class="row">
            <div class="col-4">
            <select class="form-control js-color-input-method-type">
                <option value="picker">Picker</option>
                <option value="palette">Palette</option>
                <option value="image">Image</option>
            </select>
            </div>
              <div class="col-8">
                  <div class="js-color-input-method-picker">
                      <input type="text" class="form-control" name="value" autocomplete="off" value="<?php print ($data['value']) ?>" id="value<?php print $rand; ?>">
                  </div>
                  <div class="js-color-input-method-color-palette">
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
            </div>
            <div class="col-12">
                <div class="js-color-input-method-image-upload mt-3">
                    <div style="width: 120px;" class="mb-2">
                        <div class="dropable-zone small-zone square-zone bg-white" id="upload_thumb_btn">
                            <div class="holder">
                                <div class="dropable-zone-img img-media mb-2"></div>
                                <button type="button" class="btn btn-link py-1"><?php _e('Add media'); ?></button>
                                <p><?php _e('or drop'); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="progress mt-2" id="upload_thumb_status" style="display: none">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                    </div>

                    <input name="upload_thumb" id="upload_thumb_field" class="form-control mw_option_field semi_hidden" type="text" data-mod-name="" value=""/>
                    <div class="mb2">
                        <img id="thumb" src="" alt="" style="max-width: 120px;"/><br/>
                        <span class="btn btn-link text-danger px-0 js-remove-thumb"><?php _e('Remove'); ?></span>
                    </div>

                </div>
            </div>
        </div>

       <input type="hidden" name="options[hex]" value="<?php print ($data['value']) ?>" id="color<?php print $rand; ?>">
    </div>
    <div class="custom-field-settings-values">
        <?php echo $savebtn; ?>
    </div>
</div>

<?php include('placeholder_settings.php'); ?>

<?php include('settings_footer.php'); ?>
