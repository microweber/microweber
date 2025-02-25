<?php

/*

  type: layout

  name: skin-6

  description: skin-6

*/

?>
@include('modules.breadcrumb::partials.breadcrumb_params')

<style>
   .mw-big2-breadcrumb-skin-6 {
       &.container {
           display: flex;
           flex-direction: column;
           height: 100%;
           width: 100%;
           min-width: 480px;
           padding: 0 40px;
       }

       .breadcrumb {
           display: flex;
           border-radius: 6px;
           overflow: hidden;
           margin: auto;
           text-align: center;
           top: 50%;
           width: 100%;
           height: calc(38px * 1.5);
           transform: translateY(-50%);
           box-shadow: 0 1px 1px rgba(0,0,0,1),
           0 4px 14px rgba(0,0,0,0.7);
           z-index: 1;
           background-color: #ddd;
           font-size: 14px;
       }

       .breadcrumb a {
           position: relative;
           display: flex;
           flex-grow: 1;
           text-decoration: none;
           margin: auto;
           height: 100%;
           padding-left: 38px;
           padding-right: 0;
           color: #666;
       }

       .breadcrumb a:first-child {
           padding-left: calc(38px / 2.5);
       }

       .breadcrumb a:last-child {
           padding-right: calc(38px / 2.5);
       }

       .breadcrumb a:after {
           content: "";
           position: absolute;
           display: inline-block;
           width: calc(38px * 1.5);
           height: calc(38px * 1.5);
           top: 0;
           right: calc(38px / 1.35 * -1);
           background-color: #ddd;
           border-top-right-radius: 5px;
           transform: scale(0.707) rotate(45deg);
           box-shadow: 1px -1px rgba(0,0,0,0.25);
           z-index: 1;
       }

       .breadcrumb a:last-child:after {
           content: none;
       }

       .breadcrumb__inner {
           display: flex;
           flex-direction: column;
           margin: auto;
           z-index: 2;
       }

       .breadcrumb__title {
           font-weight: bold;
       }

       .breadcrumb a.active, .breadcrumb a:hover{
           background: var(--mw-primary-color);
           color: white;
       }

       .breadcrumb a.active:after, .breadcrumb a:hover:after {
           background: var(--mw-primary-color);
           color: white;
       }


       @media all and (max-width: 1000px) {
           .breadcrumb {
               font-size: 12px;
           }
       }

       @media all and (max-width: 710px) {
           .breadcrumb__desc {
               display: none;
           }

           .breadcrumb {
               height: 38px;
           }

           .breadcrumb a {
               padding-left: calc(38px / 1.5);
           }

           .breadcrumb a:after {
               content: "";
               width: calc(38px * 1);
               height: calc(38px * 1);
               right: calc(38px / 2 * -1);
               transform: scale(0.707) rotate(45deg);
           }
       }

    }
</style>



<div class="container mw-big2-breadcrumb-skin-6">
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



