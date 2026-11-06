<?php

/*

  type: layout

  name: skin-5

  description: skin-5

*/

?>
@include('modules.breadcrumb::partials.breadcrumb_params')

@if (mw_asset_once('breadcrumb-skin-5'))
    {{-- Skin CSS moved to Modules/Breadcrumb/resources/assets/css/skin-5.css (served from
         public/modules/breadcrumb/css/skin-5.css) and emitted once per page via mw_asset_once
         instead of being re-inlined for every instance of this skin. --}}
    <link rel="stylesheet" href="{{ asset('modules/breadcrumb/css/skin-5.css') }}">
@endif


<div class="mw-big2-breadcrumb-skin-5">
    <ul class="collapsed">
        <li>
            <a href="{{ $homepage['url'] }}">
                {{ $homepage['title'] }}
            </a>
        </li>

        @if(!empty($data))
            @foreach($data as $item)
                @if($loop->last)
                    <li>
                        <a class="active">
                            {{ $item['title'] }}
                        </a>
                    </li>
                    <li>Current page</li>
                @else
                   <li>
                       <a href="{{ $item['url'] }}">
                           {{ $item['title'] }}
                       </a>
                   </li>
                @endif
            @endforeach
        @endif
    </ul>
</div>



