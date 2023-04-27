<li class="nav-item @if(isset($class)) {{$class}} @endif">
    <a href="{{$item->getUri()}}" class="nav-link fs-3" @if($item) x-init="setTimeout(function() { $el.classList.add('active'); }, 300);" @endif >

        {!! $item->getAttribute('icon') !!}
        <span>
           <div x-init="setTimeout(function() { $el.classList.remove('placeholder'); $el.classList.remove('placeholder-xs'); }, 300);" class="placeholder placeholder-xs">
               {{$item->getName()}}
           </div>
        </span>

    </a>
</li>
