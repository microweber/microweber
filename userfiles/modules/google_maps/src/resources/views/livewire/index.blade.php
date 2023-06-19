<div>
    <div>

        <div class="form-group">
            <label class="form-label"><?php _e("Enter Your Address"); ?></label>
            <input type="text" class="form-control" wire:model.debounce.100ms="settings.data-address"/>

        </div>
        <div class="form-group">
            <label class="form-label"><?php _e("Zoom Level"); ?></label>
            <input type="range" class="form-control" step="1" min="1" max="18" wire:model.debounce.100ms="settings.data-zoom"/>


        </div>


    </div>


</div>
