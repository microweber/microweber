<div>
    <script>
        Livewire.on('updatedSettings', $data => {
            mw.top().reload_module_everywhere('#<?php print $moduleId ?>')
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
