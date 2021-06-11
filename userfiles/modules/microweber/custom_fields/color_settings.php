
<?php include('settings_header.php'); ?>

<script type="text/javascript">

   mw.lib.require('colorpicker');

    color = mw.colorPicker({
        element: '#value<?php print $rand; ?>',
        position: 'bottom-left',
        onchange: function (color) {
            $('#color<?php print $rand; ?>').val(color).css('background', color);
            $('#color<?php print $rand; ?>').trigger('change');
        }
    });

    $(document).ready(function () {

        $('.predefined-colors .color').click(function () {
           $('#value<?php print $rand; ?>').val($(this).data('name'));
           $('#value<?php print $rand; ?>').css('background', '#' + $(this).data('hex'));
            $('#value<?php print $rand; ?>').trigger('change');

            $('#color<?php print $rand; ?>').val($(this).data('hex'));
            $('#color<?php print $rand; ?>').trigger('change');
        });

    });

</script>

<style>
    .predefined-colors {
        display: block;
        width: 100%;
        clear: both;
    }
    .predefined-colors .color {
        height: 50px;
        width: 50px;
        font-size:9px;
        padding: 2px;
        /*float:left;*/
        margin-right: 2px;
        opacity: 0.9;
        cursor: pointer;
        border: 2px solid #fff;
    }
    .predefined-colors .color:hover {
        opacity: 1;
        border: 2px solid #00000052;
    }
</style>

<div class="custom-field-settings-values">

    <div class="form-group">
      <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Color"); ?></label>
       <small class="text-muted d-block mb-2"><?php _e('This attribute specifies the color');?></small>
       <input type="text" class="form-control" name="value" autocomplete="off" value="<?php print ($data['value']) ?>" id="value<?php print $rand; ?>">
       <input type="hidden" name="options[hex]" value="<?php print ($data['value']) ?>" id="color<?php print $rand; ?>">
    </div>

    <div class="predefined-colors row d-flex">
        <?php
        foreach (mw()->ui->predefined_colors() as $color) {
        ?>
        <div class="color" style="background: #<?php echo $color['hex']; ?>" data-hex="<?php echo $color['hex']; ?>" data-name="<?php echo $color['name']; ?>">
           <!-- <?php echo $color['name']; ?> -->
        </div>
        <?php
        }
        ?>
    </div>

    <div class="custom-field-settings-values">
        <?php echo $savebtn; ?>
    </div>
</div>

<?php include('placeholder_settings.php'); ?>

<?php include('settings_footer.php'); ?>
