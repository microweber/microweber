





@if($moduleIdFromPreset == $item['module_id'])


    <span class="badge bg-yellow-lt d-block">You are using this preset</span>
    <x-microweber-ui::button type="button" class="mt-2 btn btn-sm btn-warning"
                             wire:click="$emit('onRemoveSelectedPresetForModule', '{{ $moduleId }}')">
        @lang('Clear preset')
    </x-microweber-ui::button>

    @elseif($moduleId == $item['module_id'])
    <span class="badge bg-green-lt d-block">This is the current module</span>


    @else

    <x-microweber-ui::button type="button" class="mt-2 btn btn-sm btn-primary" tooltip="Use this preset"
                             wire:click="$emit('onSelectPresetForModule', '{{ $itemId }}')">

        @lang('Use this preset')
    </x-microweber-ui::button>

@endif


