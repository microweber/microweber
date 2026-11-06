<?php

/*

  type: layout

  name: skin-1

  description: skin-1

*/

?>
@include('modules.breadcrumb::partials.breadcrumb_params')

@if (mw_asset_once('breadcrumb-skin-1'))
    {{-- Skin CSS moved to Modules/Breadcrumb/resources/assets/css/skin-1.css (served from
         public/modules/breadcrumb/css/skin-1.css) and emitted once per page via mw_asset_once
         instead of being re-inlined for every instance of this skin. --}}
    <link rel="stylesheet" href="{{ asset('modules/breadcrumb/css/skin-1.css') }}">
@endif

<ul class="mw-big2-breadcrumb-skin-1">
    <li>
        <a href="{{ $homepage['url'] }}">
            <span>{{ $homepage['title'] }}</span>
        </a>
    </li>
    @if(!empty($data))
        @foreach($data as $item)
            @if($loop->last)
                <li aria-current="page">
                    <a class="active">
                        <span>{{ $item['title'] }} </span>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ $item['url'] }}">
                        <span>{{ $item['title'] }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    @endif
</ul>


