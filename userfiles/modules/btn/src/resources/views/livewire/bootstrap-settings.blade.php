<div>
    <div>
        <div class="mb-3">

            <div class="form-floating">
                <select class="form-select" id="floatingSelectButtonStyle" aria-label="<?php _ejs("Style"); ?>"
                        wire:model="settings.button_style">
                    <option value="btn-default"><?php _e("Default"); ?></option>
                    <option value="btn-primary"><?php _e("Primary"); ?></option>
                    <option value="btn-secondary"><?php _e("Secondary"); ?></option>
                    <option value="btn-success"><?php _e("Success"); ?></option>
                    <option value="btn-danger"><?php _e("Danger"); ?></option>
                    <option value="btn-warning"><?php _e("Warning"); ?></option>
                    <option value="btn-info"><?php _e("Info"); ?></option>
                    <option value="btn-light"><?php _e("Light"); ?></option>
                    <option value="btn-dark"><?php _e("Dark"); ?></option>
                </select>
                <label for="floatingSelectButtonStyle"><?php _e("Style"); ?></label>
            </div>
        </div>

        <div class="mb-3">
            <div class="form-floating">
                <select class="form-select" id="floatingSelectButtonSize" aria-label="<?php _ejs("Size"); ?>"
                        wire:model="settings.button_size">
                    <option value=""><?php _e("Default"); ?></option>
                    <option value="btn-lg"><?php _e("Large"); ?></option>
                    <option value="btn-md"><?php _e("Medium"); ?></option>
                    <option value="btn-sm"><?php _e("Small"); ?></option>
                    <option value="btn-xs"><?php _e("Mini"); ?></option>
                </select>
                <label for="floatingSelectButtonSize"><?php _e("Size"); ?></label>
            </div>


        </div>


    </div>


</div>
