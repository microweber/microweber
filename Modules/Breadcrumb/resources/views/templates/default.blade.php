<?php

/*

  type: layout

  name: Default

  description: Default template

*/

?>

@include('modules.breadcrumb::partials.breadcrumb_params')


<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ $homepage['url'] }}">{{ $homepage['title'] }}</a>
        </li>
        @if(!empty($data))
            @foreach($data as $item)
                @if($loop->last)
                    <li class="breadcrumb-item active" aria-current="page">{{ $item['title'] }}</li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                    </li>
                @endif
            @endforeach
        @endif
    </ol>
</nav>
