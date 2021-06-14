
<?php include('settings_header.php'); ?>

<script type="text/javascript">

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

   /*     $('.predefined-colors .color').click(function () {
           $('#value<?php print $rand; ?>').val($(this).data('name'));
           $('#value<?php print $rand; ?>').css('background', '#' + $(this).data('hex'));
            $('#value<?php print $rand; ?>').trigger('change');

            $('#color<?php print $rand; ?>').val($(this).data('hex'));
            $('#color<?php print $rand; ?>').trigger('change');
        });*/

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
                  <div class="js-color-input-method-image-upload">
                    
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
