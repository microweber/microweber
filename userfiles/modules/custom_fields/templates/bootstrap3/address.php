<?php
// var_dump($data);var_dump($settings);die();
?>
<div class="col-md-<?php echo $settings['field_size']; ?>">
    <div class="mw-ui-field-holder">

        <?php if($settings['show_label']): ?>
        <label class="control-label">
            <?php echo $data['name']; ?>
            <?php if ($settings['required']): ?>
                <span style="color: red;">*</span>
            <?php endif; ?>
        </label>
        <?php endif; ?>



        <?php foreach ($data['values'] as $key => $value): ?>
            <div class="control-group">

                <?php if($settings['show_label']): ?>
                <label class="control-label"><?php _e($value); ?>
                    <?php if ($settings['required']): ?>
                        <span style="color:red;">*</span>
                    <?php endif; ?>
                </label>
                <?php endif; ?>

                <?php if ($key == 'country')  : ?>
                    <?php if ($data['countries']) { ?>

                        <select name="<?php echo $data['name'] ?>[country]" class="form-control">
                            <option value=""><?php _e('Choose country') ?></option>
                            <?php foreach ($data['countries'] as $country): ?>
                                <option value="<?php echo $country ?>"><?php echo $country ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php } else { ?>
                        <input type="text" class="form-control"  <?php if(!$settings['show_label']): ?>placeholder="<?php _e($value); ?>"<?php endif; ?> name="<?php echo $data['name'] ?>[<?php echo($key); ?>]" <?php if ($settings['required']) { ?> required <?php } ?> data-custom-field-id="<?php echo $data["id"]; ?>"/>
                    <?php } ?>

                <?php else: ?>
                    <input type="text" class="form-control" <?php if(!$settings['show_label']): ?>placeholder="<?php _e($value); ?>"<?php endif; ?> name="<?php echo $data['name'] ?>[<?php echo($key); ?>]" <?php if ($settings['required']) { ?> required <?php } ?>
                           data-custom-field-id="<?php echo $data["id"]; ?>"/>
                <?php endif; ?>

            </div>

        <?php endforeach; ?>


        <?php if ($data['help']): ?>
            <span class="help-block"><?php echo $data['help']; ?></span>
        <?php endif; ?>
    </div>
</div>