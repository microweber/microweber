<?php include('settings_header.php'); ?>

<div class="mw-custom-field-group ">
    <label class="form-label font-weight-bold"><?php _e('Allow multiple choices');?></label>

    <label class="form-check">
        <input class="form-check-input me-2" type="checkbox" data-option-group="custom_fields" name="options[multiple]" value="1" <?php if ($settings["multiple"]): ?> checked="checked" <?php endif; ?>/>

        <span class="form-check-label"><?php _e("Multiple Choices"); ?></span>
    </label>
</div>
<div class="custom-field-settings-values">
    <label class="form-label font-weight-bold"><?php _e("Values"); ?></label>
    <div class="mw-custom-field-group" style="padding-top: 0;" id="fields<?php print $rand; ?>">
        <?php if (is_array($data['values']) and !empty($data['values'])) : ?>
            <?php foreach ($data['values'] as $v): ?>

            <?php if (is_array($v)) {
                $v = implode(',', $v);
            } ?>
            <div class="mw-custom-field-form-controls d-flex align-items-center flex-wrap gap-1">

                <div class="col-7 d-flex align-items-center">

                    <div class="cursor-move-holder me-2 custom-fields-handle-field">
                  <span href="javascript:;" class="btn btn-link text-blue-lt tblr-body-color">
                      <svg class="mdi-cursor-move" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"/></svg>
                  </span>
                    </div>
                    <input type="text" class="form-control col-5 <?php if(empty($add_remove_controls)):?>mw-full-width<?php endif; ?>" name="value[]" value="<?php print $v; ?>">
                </div>
                <div class="col-4 text-end ms-md-0 ms-3 mt-md-0 mt-2">

                    <?php print $add_remove_controls; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="mw-custom-field-form-controls d-flex align-items-center flex-wrap gap-1">
                <div class="col-7 d-flex align-items-center">

                    <i class="mdi mdi-cursor-move custom-fields-handle-field align-self-center me-2"></i>
                    <input type="text" name="value[]" class="form-control col-5 <?php if(empty($add_remove_controls)):?>mw-full-width<?php endif; ?>" value=""/>
                </div>
                <div class="col-4 text-end ms-md-0 ms-3 mt-md-0 mt-2">

                    <?php print $add_remove_controls; ?>
                </div>
            </div>
        <?php endif; ?>
        <script type="text/javascript">
            mw.custom_fields.sort("fields<?php print $rand; ?>");
        </script>
    </div>
    <?php print $savebtn; ?>
</div>

<?php include('placeholder_settings.php'); ?>

<?php include('settings_footer.php'); ?>
