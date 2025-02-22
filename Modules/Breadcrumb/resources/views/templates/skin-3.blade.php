<?php

/*

  type: layout

  name: skin-3

  description: skin-3

*/

?>
@include('modules.breadcrumb::partials.breadcrumb_params')

<style>
    ul.mw-big2-breadcrumb-skin-3 {
        .breadcrumbs__item {
            background: #fff;
            color: #333;
            outline: none;
            padding: 0.75em 0.75em 0.75em 1.25em;
            position: relative;
            text-decoration: none;
            transition: background 0.2s linear;
        }

        .breadcrumbs__item:hover:after,
        .breadcrumbs__item:hover, .breadcrumbs__item.active {
            background: var(--mw-primary-color);;
        }

        .breadcrumbs__item:focus:after,
        .breadcrumbs__item:focus,
        .breadcrumbs__item.active:focus {
            background: var(--mw-primary-color);
            color: #fff;
        }

        .breadcrumbs__item:before {
            background: var(--mw-primary-color);
            bottom: 0;
            clip-path: polygon(50% 50%, -50% -50%, 0 100%);
            content: "";
            left: 100%;
            position: absolute;
            top: 0;
            transition: background 0.2s linear;
            width: 1em;
            z-index: 1;
        }


        .breadcrumbs__item:last-child {
            border-right: none;
        }

        .breadcrumbs__item.active {
            background: var(--mw-primary-color);;
        }
    }
</style>


<ul class="mw-big2-breadcrumb-skin-3 breadcrumbs">
    <a class="breadcrumbs__item" href="{{ $homepage['url'] }}">
       {{ $homepage['title'] }}
    </a>

    @if(!empty($data))
        @foreach($data as $item)
            @if($loop->last)
                <a class="breadcrumbs__item active">
                    {{ $item['title'] }}
                </a>
            @else
                <a class="breadcrumbs__item" href="{{ $item['url'] }}">
                    {{ $item['title'] }}
                </a>
            @endif
        @endforeach
    @endif
</ul>



