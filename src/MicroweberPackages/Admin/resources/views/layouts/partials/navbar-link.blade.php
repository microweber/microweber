<li class="nav-item">
    <a href="{{$item->url()}}" class="nav-link fs-3 @if($item->active) active @endif">
        @if($item->icon)
            {!! $item->icon !!}
        @endif
        <span>
        {{$item->text()}}
        </span>
    </a>
</li>
