<?php

/*

  type: layout

  name: skin-1

  description: skin-1

*/

?>
@include('modules.breadcrumb::partials.breadcrumb_params')

<style>
    ul {
        display: flex;
    }

    li {
        margin-left: -2px;
        &:first-child {
            a {
                border-radius: 8px 0 0 8px;
            }
        }
        &:last-child {
            a {
                border-radius: 0 8px 8px 0;
            }
        }
    }

    a {
        background-color: #fff;
        color: #6b7280;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        font-size: 1.25rem;
        font-weight: 600;
        border: 2px solid #d1d5db;
        height: 3em;
        padding-left: 1.5em;
        padding-right: 1.5em;
        border-radius: 0;
        transform: skew(-30deg);
        position: relative;
        transition: color 0.15s ease, border-color 0.15s ease;

        &:hover,
        &:focus,
        &.active {
            outline: 0;
            color: #6366f1;
            border-color: #6366f1;
            z-index: 1;
        }

        & > * {
            transform: skew(30deg);
        }

        span {
            display: flex;
            align-items: center;
            justify-content: center;
            svg {
                margin-right: 0.375em;
                width: 1.5em;
                height: 1.5em;
            }
        }
    }
</style>

<nav aria-label="breadcrumb">
    <ul class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ $homepage['url'] }}"><span>{{ $homepage['title'] }}</span></a>

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
    </ul>
</nav>

