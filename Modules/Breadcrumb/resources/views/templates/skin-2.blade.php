<?php

/*

  type: layout

  name: skin-2

  description: skin-2

*/

?>
@include('modules.breadcrumb::partials.breadcrumb_params')

@if (mw_asset_once('breadcrumb-skin-2'))
    {{-- Skin CSS moved to Modules/Breadcrumb/resources/assets/css/skin-2.css (served from
         public/modules/breadcrumb/css/skin-2.css) and emitted once per page via mw_asset_once
         instead of being re-inlined for every instance of this skin. --}}
    <link rel="stylesheet" href="{{ asset('modules/breadcrumb/css/skin-2.css') }}">
@endif


<div class="mw-breadcrumb-skin-2-wrap">
    <ul class="mw-big2-breadcrumb-skin-2 breadcrumb-skin-2">
        <li class="breadcrumb__item--skin-2 breadcrumb__item--skin-2-firstChild">
            <a class="breadcrumb__inner" href="{{ $homepage['url'] }}">
                <span class="breadcrumb__title">{{ $homepage['title'] }}</span>
            </a>
        </li>
        @if(!empty($data))
            @foreach($data as $item)
                @if($loop->last)
                    <li class="breadcrumb__item--skin-2 active" aria-current="page">
                        <a class="breadcrumb__inner">
                            <span class="breadcrumb__title">{{ $item['title'] }} </span>
                        </a>
                    </li>
                @else
                    <li class="breadcrumb__item--skin-2">
                        <a class="breadcrumb__inner" href="{{ $item['url'] }}">
                            <span class="breadcrumb__title">{{ $item['title'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        @endif
    </ul>
</div>


