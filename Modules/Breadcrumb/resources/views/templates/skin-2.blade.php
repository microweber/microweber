<?php

/*

  type: layout

  name: skin-2

  description: skin-2

*/

?>
@include('modules.breadcrumb::partials.breadcrumb_params')

<style>
    ul.mw-big2-breadcrumb-skin-2 {

    }
</style>

<ul class="mw-big2-breadcrumb-skin-2">
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


