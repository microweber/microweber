<script>
    document.addEventListener('livewire:load', function () {
        mw.app.linkPicker.on('selected', function (data) {

            if (!data) {
                return
            }

            if ((data.type) && data.type === 'category') {
            @this.set('settings.url_to_category_id', data.id);
            @this.set('settings.url_to_content_id', '');
            @this.set('settings.url', '');
            } else if ((data.type) && (data.type === 'content')) {
            @this.set('settings.url_to_content_id', data.id);
            @this.set('settings.url_to_category_id', '');
            @this.set('settings.url', '');
            } else {
            @this.set('settings.url', data.url);
            @this.set('settings.url_to_content_id', '');
            @this.set('settings.url_to_category_id', '');
            }

            $('#display-url').val(data.url);
        });
    })
</script>

<div class="mb-3">
    <label class="form-label"><?php _e("Edit url"); ?></label>
    <small class="text-muted d-block mb-3"><?php _e('Link settings for your url.');?></small>


    <button class="btn btn-secondary btn-sm btn-rounded" onclick="mw.app.linkPicker.selectLink('#display-url')"><i class="mdi mdi-link"></i> <?php _e("Edit link"); ?></button>

    <input id="display-url" onclick="mw.app.linkPicker.selectLink('#display-url')" class="form-control-plaintext" type="text" placeholder="Url ..." value="{{ $url }}">

    <input type="hidden" wire:model="settings.url">
    <input type="hidden" wire:model="settings.url_to_content_id">
    <input type="hidden" wire:model="settings.url_to_category_id">
</div>
