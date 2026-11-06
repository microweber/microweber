<?php

/*

  type: layout

  name: skin-3

  description: skin-3

*/

?>
@include('modules.breadcrumb::partials.breadcrumb_params')

@if (mw_asset_once('breadcrumb-skin-3'))
    {{-- Skin CSS moved to Modules/Breadcrumb/resources/assets/css/skin-3.css (served from
         public/modules/breadcrumb/css/skin-3.css) and emitted once per page via mw_asset_once
         instead of being re-inlined for every instance of this skin. --}}
    <link rel="stylesheet" href="{{ asset('modules/breadcrumb/css/skin-3.css') }}">
@endif


<ul class="mw-big2-breadcrumb-skin-3 breadcrumbs">
    <li>
        <a class="breadcrumbs__item" href="{{ $homepage['url'] }}">
           {{ $homepage['title'] }}
        </a>
    </li>

    @if(!empty($data))
        @foreach($data as $item)
            @if($loop->last)
                <li>
                    <a class="breadcrumbs__item active">
                        {{ $item['title'] }}
                    </a>
                </li>
            @else
                <li>
                    <a class="breadcrumbs__item" href="{{ $item['url'] }}">
                        {{ $item['title'] }}
                    </a>
                </li>
            @endif
        @endforeach
    @endif
</ul>



