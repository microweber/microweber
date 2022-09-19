<div class="">
    <input class="form-control"

           wire:model.debounce.500ms="query"
           wire:keydown.escape="resetProperties"
           wire:keydown.tab="resetProperties"
           wire:keydown.enter="showContact"
           wire:click="refreshQueryData"

           placeholder="Type to search customers...">

    <div wire:loading wire:target="query">
        Searching...
    </div>

    <ul class="list-group position-absolute" style="z-index: 200;">
        @if(!empty($data))
            @foreach($data as $row)
                <li class="list-group-item list-group-item-action cursor-pointer" wire:click="selectId({{$row['id']}})">
                    {{ $row['first_name'] }} {{ $row['last_name'] }} (#{{ $row['id'] }})
                </li>
            @endforeach
        @endif
    </ul>

</div>
