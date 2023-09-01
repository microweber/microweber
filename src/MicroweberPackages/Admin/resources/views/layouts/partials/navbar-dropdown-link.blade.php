@php
$dropdownActive = false;
foreach($item->getChildren() as $subItem) {

    if (!empty($subItem->getExtra('routes'))) {
        if (in_array(Route::currentRouteName(), $subItem->getExtra('routes'))) {
            $dropdownActive = true;
        }
    }

    if ($subItem->getAttribute('route') == Route::currentRouteName()) {
        $dropdownActive = true;
    }
    if (request()->getUri() == $subItem->getUri()) {
        $dropdownActive = true;
    }
}
@endphp

<li class="nav-item dropdown">
    <a href="@if (!empty($subItem->getAttribute('route'))) {{route($subItem->getAttribute('route'))}} @else {{ $subItem->getUri() }} @endif" class="nav-link fs-3 dropdown-toggle" @if($dropdownActive) x-init="setTimeout(function() { $el.classList.add('active'); }, 300);" @endif data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="true">
        {!! $item->getAttribute('icon') !!}
        <div x-init="setTimeout(function() { $el.classList.remove('placeholder'); $el.classList.remove('placeholder-xs'); }, 300);" class="placeholder placeholder-xs">
            <span class="badge-holder">
                {{_e($item->getName())}}
            </span>
        </div>
    </a>
    <div class="dropdown-menu" @if($dropdownActive) x-init="setTimeout(function() { $el.classList.add('show'); }, 300);" @endif data-bs-popper="static">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                @foreach($item->getChildren() as $subItem)
                    <div class="dropdown-menu-column-item">
                    @php
                    $subItemIsActive = false;
                    if ($subItem->getAttribute('route') == Route::currentRouteName()) {
                        $subItemIsActive = true;
                    }
                    if (!empty($subItem->getExtra('routes'))) {
                        if (in_array(Route::currentRouteName(), $subItem->getExtra('routes'))) {
                            $subItemIsActive = true;
                        }
                    }
                    @endphp

                    <a href="@if (!empty($subItem->getAttribute('route'))) {{route($subItem->getAttribute('route'))}} @else {{ $subItem->getUri() }} @endif" class="dropdown-item  @if($subItemIsActive) active @endif">

                        @if($subItem->hasChildren())
                            <span class="add-new add-new-hamburger dropdown-menu-column-item--tree-open">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>

                           </span>
                        @endif

                        <span>
                            {{_e($subItem->getName())}}
                       </span>

                        @if($subItem->hasChildren())
                            <span data-href="" class="add-new add-new-dots" data-bs-toggle="tooltip" title="">
                             <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M240 656q-33 0-56.5-23.5T160 576q0-33 23.5-56.5T240 496q33 0 56.5 23.5T320 576q0 33-23.5 56.5T240 656Zm240 0q-33 0-56.5-23.5T400 576q0-33 23.5-56.5T480 496q33 0 56.5 23.5T560 576q0 33-23.5 56.5T480 656Zm240 0q-33 0-56.5-23.5T640 576q0-33 23.5-56.5T720 496q33 0 56.5 23.5T800 576q0 33-23.5 56.5T720 656Z"/></svg>
                            </span>
                        @endif
                    </a>

                    @if($subItem->hasChildren())
                        <div class="mw-admin-sidebar-navigation-menu">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <nav class="nav flex-column">
                                        @foreach($subItem->getChildren() as $subItemChildren)
                                            <a class="mw-admin-action-links btn btn-link text-decoration-none fs-5" aria-current="page"
                                               href="@if (!empty($subItemChildren->getAttribute('route'))) {{route($subItemChildren->getAttribute('route'))}} @else {{ $subItemChildren->getUri() }} @endif">
                                                {{_e($subItemChildren->getName())}}
                                            </a>
                                        @endforeach
                                        <a class="mw-admin-action-links btn btn-link text-decoration-none fs-5 dropdown-menu-column-item--tree-open">Site structure</a>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    @endif

                    </div>
                @endforeach
            </div>
        </div>
    </div>
</li>
