<li class="nav-item @if(isset($class)) {{$class}} @endif">
    <a href="{{$item->url()}}" class="nav-link fs-3 @if($item->active) active @endif">
        @if($item->icon)
            {!! $item->icon !!}
        @endif
        <span>
           <div x-init="setTimeout(function() { $el.classList.remove('placeholder'); $el.classList.remove('placeholder-xs'); }, 0);" class="placeholder placeholder-xs">
                {{$item->text()}}
           </div>
        </span>
    </a>
</li>
