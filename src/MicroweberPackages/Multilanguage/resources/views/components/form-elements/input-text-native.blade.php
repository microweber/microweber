<x-microweber-ui::label for="{{$randId}}" :value="$labelText" />
<x-microweber-ui::input id="{{$randId}}" class="block w-full" wire:model.lazy="state.{{$fieldName}}" />
