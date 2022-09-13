<th scope="col">
    {{$name}}
    <span style="font-size: 15px;cursor: pointer">
        @if(isset($filters['orderBy']) && !empty($filters['orderBy']))
            @if ($filters['orderBy'] == $key . ',desc')
                <i class="fa fa-arrow-down" wire:click="orderBy('{{$key}},asc')"></i>
            @elseif ($filters['orderBy'] == $key . ',asc')
                <i class="fa fa-arrow-up" wire:click="orderBy('{{$key}},desc')"></i>
            @else
            <i class="fa fa-sort" wire:click="orderBy('{{$key}},desc')"></i>
            @endif
        @endif
    </span>
</th>
