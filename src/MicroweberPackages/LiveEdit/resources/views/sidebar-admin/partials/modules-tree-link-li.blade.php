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
   ccccccccccc
</li>
