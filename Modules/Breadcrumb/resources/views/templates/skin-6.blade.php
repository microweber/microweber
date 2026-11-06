<?php

/*

  type: layout

  name: skin-6

  description: skin-6

*/

?>
@include('modules.breadcrumb::partials.breadcrumb_params')

@if (mw_asset_once('breadcrumb-skin-6'))
    {{-- Skin CSS moved to Modules/Breadcrumb/resources/assets/css/skin-6.css (served from
         public/modules/breadcrumb/css/skin-6.css) and emitted once per page via mw_asset_once
         instead of being re-inlined for every instance of this skin. --}}
    <link rel="stylesheet" href="{{ asset('modules/breadcrumb/css/skin-6.css') }}">
@endif



<div class="mw-big2-breadcrumb-skin-6">
    <div class="breadcrumb">
        <a href="{{ $homepage['url'] }}">
            <span class="breadcrumb__inner">
                <span class="breadcrumb__title">  {{ $homepage['title'] }} </span>
            </span>
        </a>

        @if(!empty($data))
            @foreach($data as $item)
                @if($loop->last)
                    <a class="active">
                        <span class="breadcrumb__inner">
                            <span class="breadcrumb__title"> {{ $item['title'] }} </span>
                        </span>
                    </a>
                @else
                   <a href="{{ $item['url'] }}">
                       <span class="breadcrumb__inner">
                            <span class="breadcrumb__title"> {{ $item['title'] }} </span>
                       </span>
                   </a>
                @endif
            @endforeach
        @endif
    </div>
</div>



