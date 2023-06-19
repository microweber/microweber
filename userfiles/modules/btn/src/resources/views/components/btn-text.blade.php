<div class="mb-3">
    <label class="form-label"><?php _e("Text"); ?></label>

    <input type="text" class="form-control mb-2" wire:model.debounce.100ms="settings.text"
           placeholder="Text..">


    <small class="form-hint">
        <?php _e('Change your button text.'); ?>
    </small>

</div>
