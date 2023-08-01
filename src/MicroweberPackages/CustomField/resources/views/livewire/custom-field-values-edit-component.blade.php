<div>

    @if($customField->type == 'checkbox')

        <div>
            <x-microweber-ui::label value="Values" />

            {{-- <div class="mt-3">
              <x-microweber-ui::input class="mt-1 block w-full" wire:model="customFieldValues.{{ $customFieldValue->id }}" />
          </div>--}}





            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Enter Name" wire:model="name.0">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-success btn-sm mx-2" wire:click.prevent="add({{$i}})">Add</button>
                </div>
            </div>


            @foreach($inputs as $key => $value)
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <input type="email" class="form-control" wire:model="email.{{ $value }}" placeholder="Enter Email">
                            @error('email.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-2">
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
