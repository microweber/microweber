<div>

    <div class="card">
        <div x-data="{}" class="card-body" style="padding:5px">

            <div class="form-group">
                <label class="form-label font-weight-bold"><?php _e("Main Logo"); ?></label>
                <small class="text-muted d-block mb-2"><?php _e("This logo image will appear every time"); ?></small>
            </div>

            <div>
                <label class="live-edit-label"><?php _e("Main Logo"); ?></label>
                <livewire:microweber-option::media-picker label="<?php _e("Add Logo"); ?>" optionKey="logoimage" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

        </div>
    </div>

</div>
