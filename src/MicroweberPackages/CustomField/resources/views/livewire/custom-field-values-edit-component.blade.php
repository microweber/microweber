<div>

    @if($customField->type == 'checkbox')

    @else
        <div class="mt-3">
            <x-microweber-ui::label for="value" value="Value" />
            <x-microweber-ui::input id="value" class="mt-1 block w-full" wire:model="state.value" />
        </div>
    @endif
</div>
