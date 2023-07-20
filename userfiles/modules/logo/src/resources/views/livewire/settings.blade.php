<div>

    <div class="card">
        <div x-data="{}" class="card-body" style="padding:5px">

            <div @mw-option-saved.window="function() {
                if ($event.detail.optionKey === 'logoimage') {
                    mw.top().reload_module_everywhere('{{$moduleType}}');
                }
                }">
            </div>

            <div>
                <label class="live-edit-label"><?php _e("Main Logo"); ?></label>
                <small class="text-muted d-block mb-2"><?php _e("This logo image will appear every time"); ?></small>
                <livewire:microweber-option::media-picker optionKey="logoimage" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

        </div>
    </div>

</div>
