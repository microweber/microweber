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

        .container {
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 100%;
            min-width: 480px;
            padding: 0 40px;
        }

        .breadcrumb-skin-2 {
            display: flex;
            border-radius: 10px;
            margin: auto;
            text-align: center;
            top: 50%;
            width: 100%;
            height: 40px;
            transform: translateY(-50%);
            z-index: 1;
            justify-content: center;
        }


        .breadcrumb__item--skin-2 {
            height: 100%;
            color: #FFF;
            border-radius: 7px;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            position: relative;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            transform: skew(-21deg);
            box-shadow: 0 2px 5px rgba(0,0,0,0.26);
            margin: 5px;
            padding: 0 40px;
            cursor: pointer;
        }

        .breadcrumb__item--skin-2 a {
            text-decoration: none;
        }

        .breadcrumb__item--skin-2:hover, .breadcrumb__item--skin-2.active {
            background: var(--mw-primary-color);
            color: #FFF;
        }


        .breadcrumb__inner {
            display: flex;
            flex-direction: column;
            margin: auto;
            z-index: 2;
            transform: skew(21deg);
        }

        .breadcrumb__title {
            font-size: 16px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }


        @media all and (max-width: 1000px) {
            .breadcrumb {
                height: 35px;
            }

            .breadcrumb__title{
                font-size: 11px;
            }
            .breadcrumb__item--skin-2 {
                padding: 0 30px;
            }
        }

        @media all and (max-width: 710px) {
            .breadcrumb {
                height: 30px;
            }
            .breadcrumb__item--skin-2 {
                padding: 0 20px;
            }

        }
    }
</style>


<div class="container">
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


