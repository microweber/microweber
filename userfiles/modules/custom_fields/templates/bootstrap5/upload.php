<?php $up = 'up' . uniqid() . rand() . rand() . crc32($data['id']); ?>
<?php $rand = uniqid(); ?>

<div class="col-md-<?php echo $settings['field_size']; ?>">
    <div class="form-group">

        <?php if ($settings['show_label']): ?>
            <label class="control-label"><?php echo $data["name"]; ?>
                <?php if ($settings['required']): ?>
                    <span style="color:red;">*</span>
                <?php endif; ?>
            </label>
        <?php endif; ?>

        <div class="relative mw-custom-field-upload" id="upload_<?php echo($rand); ?>">
            <div class="row">
                <div class="col">

                    <div class="custom-file custom-file-<?php echo($rand); ?>">
                        <input type="file" name="<?php echo $data["name_key"]; ?>" class="custom-file-input form-control custom-file-input-<?php echo($rand); ?>" id="customFile<?php echo($rand); ?>">
                        <label class="custom-file-label custom-file-label-<?php echo($rand); ?>" for="customFile<?php echo($rand); ?>"><i class="mdi mdi-upload"></i> <?php _e("Browse"); ?></label>
                    </div>
                    <div class="valid-feedback"><?php _e('Success! You\'ve done it.'); ?></div>
                    <div class="invalid-feedback"><?php _e('Error! The value is not valid.'); ?></div>
                 </div>
            </div>
        </div>
    </div>

 </div>


<script>
    $(document).ready(function (){
        $('#customFile<?php echo($rand); ?>').change(function(e) {
            var fileName = e.target.files[0].name;
            // change name of actual input that was uploaded
            $(e.target).next().html(fileName);
        });
    });
</script>
