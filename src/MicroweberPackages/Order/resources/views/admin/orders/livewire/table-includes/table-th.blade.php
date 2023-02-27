<th scope="col">
    <div>
        {{$name}}
        <span style="font-size: 15px;cursor: pointer">
            @if(isset($filters['orderBy']) && !empty($filters['orderBy']))
                @if ($filters['orderBy'] == $key . ',desc')
                    <i class="fa fa-arrow-down" wire:click="$emit('setFilterToOrders','orderBy', '{{$key}},asc')"></i>
                @elseif ($filters['orderBy'] == $key . ',asc')
                    <i class="fa fa-arrow-up" wire:click="$emit('setFilterToOrders','orderBy', '{{$key}},desc')"></i>
                @else
                <i class="fa fa-sort" wire:click="$emit('setFilterToOrders','orderBy', '{{$key}},desc')"></i>
                @endif
            @else
                <i class="fa fa-sort" wire:click="$emit('setFilterToOrders','orderBy', '{{$key}},desc')"></i>
            @endif
        </span>
    </div>
</th>
