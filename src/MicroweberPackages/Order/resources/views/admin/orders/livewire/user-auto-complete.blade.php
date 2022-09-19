<div class="">
    <input class="form-control"

       wire:model.debounce.500ms="query"
       wire:keydown.escape="resetProperties"
       wire:keydown.enter="refreshQueryData"
       wire:click="refreshQueryData"

       placeholder="Type to search registered users...">

    <div wire:loading wire:target="query">
        Searching...
    </div>

    <ul class="list-group position-absolute" style="z-index: 200;max-height: 300px;overflow-x:hidden; overflow-y: scroll;">
        @if(!empty($data))
            @foreach($data as $row)
                <li class="list-group-item list-group-item-action cursor-pointer" wire:click="selectCreatedById({{$row->id}})">
                    {{ $row->displayName() }} (#{{ $row->id }})
                </li>
            @endforeach
        @endif
    </ul>

</div>
