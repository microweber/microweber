
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

    });

</script>

<div class="custom-field-settings-values">
    <div class="form-group">
      <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Color"); ?></label>
       <small class="text-muted d-block mb-2"><?php _e('This attribute specifies the color');?></small>

       <input type="text" class="form-control" name="value" autocomplete="off" value="<?php print ($data['value']) ?>" id="value<?php print $rand; ?>">
<!--
        <select class="selectpicker" data-live-search="true" style="width:100%;">
            <?php
/*            foreach (mw()->ui->predefined_colors() as $color) {
                */?> 
                 <option data-hex="<?php /*echo $color['hex']; */?>" data-name="<?php /*echo $color['name']; */?>" data-tokens="<?php /*echo $color['name']; */?>" style="background: #<?php /*echo $color['hex']; */?>;">
                     <?php /*echo $color['name']; */?>
                 </option>
                <?php
/*            }
            */?>
        </select>
-->
       <input type="hidden" name="options[hex]" value="<?php print ($data['value']) ?>" id="color<?php print $rand; ?>">
    </div>
    <div class="custom-field-settings-values">
        <?php echo $savebtn; ?>
    </div>
</div>

<?php include('placeholder_settings.php'); ?>

<?php include('settings_footer.php'); ?>
