





@if($moduleIdFromPreset == $item['module_id'])

    @lang('You are using this preset')

    <x-microweber-ui::button type="button" class="btn btn-warning"
                             wire:click="$emit('onRemoveSelectedPresetForModule', '{{ $moduleId }}')">
        @lang('Clear preset')
    </x-microweber-ui::button>

    @elseif($moduleId == $item['module_id'])

        This is the current module

    @else

    <x-microweber-ui::button type="button" tooltip="Use this preset"
                             wire:click="$emit('onSelectPresetForModule', '{{ $itemId }}')">

        @lang('Use this preset')
    </x-microweber-ui::button>

@endif


