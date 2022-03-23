<?php
$rand = uniqid();
?>
<div class="col-sm-<?php echo $settings['field_size_mobile']; ?> col-md-<?php echo $settings['field_size_tablet']; ?> col-lg-<?php echo $settings['field_size_desktop']; ?>">
    <div class="mw-text-start mb-3">

        <?php if($settings['show_label']): ?>
        <label class="control-label my-3">
            <?php echo $data["name"]; ?>
            <?php if ($settings['required']): ?>
                <span style="color:red;">*</span>
            <?php endif; ?>
        </label>
        <?php endif; ?>

        <input type="text" <?php if ($settings['required']): ?> required  <?php endif; ?>  data-date-format="<?php echo $settings['date_format'];?>"   data-custom-field-id="<?php echo $data["id"]; ?>" name="<?php print $data["name_key"]; ?>" value="<?php echo $data['value']; ?>" id="date_<?php echo $rand; ?>" placeholder="<?php echo $data["placeholder"]; ?>"
               class="form-control js-bootstrap5-datepicker" autocomplete="off"/>
        <div class="valid-feedback"><?php _e('Success! You\'ve done it.'); ?></div>
        <div class="invalid-feedback"><?php _e('Error! The value is not valid.'); ?></div>

        <?php if ($data['help']): ?>
            <small class="form-text text-muted"><?php echo $data['help']; ?></small>
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
