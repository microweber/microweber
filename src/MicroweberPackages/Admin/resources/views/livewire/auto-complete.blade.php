<div>
    <div class="input-group">
    <input class="form-control"
           type="search"
           wire:model.debounce.500ms="query"
           wire:keydown.escape="resetProperties"
           wire:keydown.enter="refreshQueryData"
           wire:click="refreshQueryData"
           wire:blur="closeDropdown"

           placeholder="{{$placeholder}}">

        @if($selectedItem)
        <button class="btn btn-sm bg-transparent" wire:click="resetProperties" style="margin-left: -40px; z-index: 100;">
            <i class="fa fa-times text-muted"></i>
        </button>
        @endif
    </div>

    <div wire:loading wire:target="query">
        {{$searchingText}}
    </div>

    @if($showDropdown)
        <ul class="list-group position-absolute" style="z-index: 200;max-height: 300px;overflow-x:hidden; overflow-y: scroll;">
            @if(!empty($data))
                @foreach($data as $item)
                    <li class="list-group-item list-group-item-action cursor-pointer d-flex align-items-center" wire:click="selectItem('{{$item['key']}}')">

                        @if(isset($item['thumbnail']))
                            <div
                                class="bg-image mr-3"
                                style="background-image: url('{{$item['thumbnail']}}'); background-repeat: no-repeat; background-position: center center; width: 30px;height: 30px;">
                            </div>
                        @endif

                      <div>{{ $item['value'] }}</div>
                    </li>
                @endforeach
            @endif
        </ul>
    @endif

</div>
