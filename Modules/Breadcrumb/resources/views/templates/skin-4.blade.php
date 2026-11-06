<?php

/*

  type: layout

  name: skin-4

  description: skin-4

*/

?>
@include('modules.breadcrumb::partials.breadcrumb_params')

@if (mw_asset_once('breadcrumb-skin-4'))
    {{-- Skin CSS moved to Modules/Breadcrumb/resources/assets/css/skin-4.css (served from
         public/modules/breadcrumb/css/skin-4.css) and emitted once per page via mw_asset_once
         instead of being re-inlined for every instance of this skin. --}}
    <link rel="stylesheet" href="{{ asset('modules/breadcrumb/css/skin-4.css') }}">
@endif


<div class="mw-big2-breadcrumb-skin-4">
    <ul>
        <li>
            <a href="{{ $homepage['url'] }}">
                <i class="fa fa-home" aria-hidden="true"></i>
            </a>
        </li>

        @if(!empty($data))
            @foreach($data as $item)
                @if($loop->last)
                    <li>
                        <a class=" active">
                            {{ $item['title'] }}
                        </a>
                    </li>
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



