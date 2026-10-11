<div>
    <x-microweber-ui::checkbox

        :options="$checkboxOptions"
        wire:model.live.debounce.100ms="selectedCheckboxes"

    />
</div>
