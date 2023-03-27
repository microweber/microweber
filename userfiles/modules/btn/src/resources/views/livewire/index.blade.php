<div>
    <script>

        Livewire.on('settingsChanged', $data => {
            mw.top().trigger('live_edit.modules.settings.btn.changed', $data);
         })
    </script>
    <div>
        <input type="text" wire:model.debounce.100ms="settings.text"/>

        <select wire:model.debounce.100ms="settings.align">
            <option value="none">none</option>
            <option value="left">left</option>
            <option value="right">right</option>
            <option value="center">center</option>
        </select>

    </div>


</div>
