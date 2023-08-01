<div>

    @if($customField->type == 'checkbox' || $customField->type == 'dropdown' || $customField->type == 'radio')

        <div>
            <x-microweber-ui::label value="Values" />

            @foreach($inputs as $key => $value)
                <div class="row mt-3">
                    <div class="col-md-8">
                        <x-microweber-ui::input class="mt-1 block w-full" wire:model="inputs.{{ $key }}" />
                        @error('inputs.'.$key) <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-2 d-flex justify-content-center align-items-center">
                        <button class="btn btn-outline-success btn-sm mx-2" wire:click.prevent="add()">Add</button>
                    </div>
                    <div class="col-md-2 d-flex justify-content-center align-items-center">
                        <button class="btn btn-outline-danger btn-sm" wire:click.prevent="remove({{$key}})">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        <div class="mt-3">
            <x-microweber-ui::label for="value" value="Value" />
            <x-microweber-ui::input id="value" class="mt-1 block w-full" wire:model="state.value" />
        </div>
    @endif
</div>
