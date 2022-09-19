<div class="">
    <input class="form-control"

       type="search"

       wire:model.debounce.500ms="query"
       wire:keydown.escape="resetProperties"
       wire:keydown.enter="refreshQueryData"
       wire:click="refreshQueryData"
       wire:blur="closeDropdown"

       placeholder="Type to search customers...">

    <div wire:loading wire:target="query">
        Searching...
    </div>

    @if($showDropdown)
    <ul class="list-group position-absolute" style="z-index: 200;max-height: 300px;overflow-x:hidden; overflow-y: scroll;">
        @if(!empty($data))
            @foreach($data as $row)
                <li class="list-group-item list-group-item-action cursor-pointer" wire:click="selectCreatedById({{$row['created_by']}})">
                    {{ $row['first_name'] }} {{ $row['last_name'] }} (#{{ $row['created_by'] }})
                </li>
            @endforeach
        @endif
    </ul>
    @endif

</div>
