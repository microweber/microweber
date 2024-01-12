<x-microweber-ui::label for="{{$randId}}" :value="$labelText" />
<x-microweber-ui::textarea id="{{$randId}}" class="block w-full" wire:model.lazy="state.{{$fieldName}}" />
