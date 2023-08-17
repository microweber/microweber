@if($moduleId == $item['module_id'])
    <x-microweber-ui::button type="button" disabled tooltip="Use this preset">
        @lang('You are using this preset')
    </x-microweber-ui::button>

@else
    <x-microweber-ui::button type="button" tooltip="Use this preset"
                             wire:click="$emit('selectPresetForModule', '{{ $itemId }}')">
        @lang('Use this preset')
    </x-microweber-ui::button>

@endif




