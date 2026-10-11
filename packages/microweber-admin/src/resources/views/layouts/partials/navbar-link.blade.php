@php
$isCurrent = false;
if (request()->getUri() == $item->getUri()) {
    $isCurrent = true;
}

if ($item->getAttribute('route') == Route::currentRouteName()) {
    $isCurrent = true;
}
@endphp


<li class="nav-item @if(isset($class)) {{$class}} @endif">
    <a href="{{$item->getUri()}}" @if($item->getAttribute('target') ) target="{!! $item->getAttribute('target') !!}" @endif" class="nav-link fs-3" @if($isCurrent) x-init="setTimeout(function() { $el.classList.add('active'); }, 300);" @endif >

        {!! $item->getAttribute('icon') !!}
        <span>
           <div x-init="setTimeout(function() { $el.classList.remove('placeholder'); $el.classList.remove('placeholder-xs'); }, 300);" class="placeholder placeholder-xs">
               {{_e($item->getName())}}
           </div>
        </span>

    </a>
</li>
