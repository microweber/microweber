<?php
$rand = uniqid();
?>
<div class="col-md-<?php echo $settings['field_size']; ?>">
    <div class="form-group">

        <?php if($settings['show_label']): ?>
        <label class="control-label">
            <?php echo $data["name"]; ?>
            <?php if ($settings['required']): ?>
                <span style="color:red;">*</span>
            <?php endif; ?>
        </label>
        <?php endif; ?>

        <div class="mw-custom-field-form-controls">
            <input type="text" <?php if ($settings['required']): ?> required  <?php endif; ?> data-date-format="<?php echo $settings['date_format'];?>"  data-custom-field-id="<?php echo $data["id"]; ?>" name="<?php print $data["name_key"]; ?>" value="<?php echo $data['value']; ?>" id="date_<?php echo $rand; ?>" placeholder="<?php echo $data["placeholder"]; ?>" class="form-control js-bootstrap3-datepicker" autocomplete="off"/>
        </div>

        <?php if ($data['help']): ?>
            <span class="help-block"><?php echo $data['help']; ?></span>
        <?php endif; ?>
    </div>
</div>

<script>
    mw.lib.require("bootstrap_datepicker");
</script>

<script type="text/javascript">
    $(document).ready(function () {
        if($('#date_<?php echo $rand; ?>') && $('#date_<?php echo $rand; ?>').datepicker){
            $('#date_<?php echo $rand; ?>').datepicker({ dateFormat: '<?php echo $settings['date_format'];?>', language: "<?php echo current_lang_abbr(); ?>"});
        }
    });
</script>
