@php
$dropdownActive = false;
foreach($item->items() as $subItem) {
    if($subItem->active) {
        $dropdownActive = true;
    }
}
@endphp

<li class="nav-item dropdown">
    <a href="" class="nav-link fs-3 dropdown-toggle @if($dropdownActive) show @endif" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="true">
        @if($item->prepend->icon)
            {!! $item->prepend->icon !!}
        @endif
        <div x-init="setTimeout(function() { $el.classList.remove('placeholder'); $el.classList.remove('placeholder-xs'); }, 0);" class="placeholder placeholder-xs">
            <span class="badge-holder">
                {{$item->prepend->text()}}
            </span>
        </div>
    </a>
    <div class="dropdown-menu @if($dropdownActive) show @endif" data-bs-popper="static">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                @foreach($item->items() as $subItem)
                    <a href="{{$subItem->url()}}" class="dropdown-item justify-content-between @if($subItem->active) active @endif">
                       <span>
                            {{$subItem->text()}}
                       </span>
                        <span data-href="{{$subItem->url()}}" class="add-new" data-bs-toggle="tooltip" title="">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M240 656q-33 0-56.5-23.5T160 576q0-33 23.5-56.5T240 496q33 0 56.5 23.5T320 576q0 33-23.5 56.5T240 656Zm240 0q-33 0-56.5-23.5T400 576q0-33 23.5-56.5T480 496q33 0 56.5 23.5T560 576q0 33-23.5 56.5T480 656Zm240 0q-33 0-56.5-23.5T640 576q0-33 23.5-56.5T720 496q33 0 56.5 23.5T800 576q0 33-23.5 56.5T720 656Z"/></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</li>
