
<x-microweber-ui::button type="button" tooltip="Use this preset"
                                wire:click="$emit('selectPresetForModule', '{{ $itemId }}')">
@lang('Use this preset')
</x-microweber-ui::button>
