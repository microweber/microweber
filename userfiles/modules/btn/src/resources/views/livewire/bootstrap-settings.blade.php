<div>
    <div>

        <div class="mb-3">
            <label><?php _ejs("Style"); ?></label>
            <div>
                <select class="form-select" wire:model="settings.button_style">
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
            </div>
        </div>

        <div class="mb-3">
            <label><?php _ejs("Size"); ?></label>
            <div>
                <select class="form-select" wire:model="settings.button_size">
                    <option value=""><?php _e("Default"); ?></option>
                    <option value="btn-lg"><?php _e("Large"); ?></option>
                    <option value="btn-md"><?php _e("Medium"); ?></option>
                    <option value="btn-sm"><?php _e("Small"); ?></option>
                    <option value="btn-xs"><?php _e("Mini"); ?></option>
                </select>
            </div>


        </div>


    </div>


</div>
