<?php

/*

  type: layout

  name: skin-5

  description: skin-5

*/

?>
@include('modules.breadcrumb::partials.breadcrumb_params')

<style>
   .mw-big2-breadcrumb-skin-5 {

       ul {
           list-style-type: none;
           margin: 0;
           padding: 2em;
           color: #333;
       }

       li {
           display: inline-block;
           position: relative;
           padding-right: 2em;
           margin: 0;
       }
       li:after {
           content: '>';
           position: absolute;
           display: inline-block;
           right: 0;
           width: 2.2em;
           text-align: center;
       }
       li:last-child {
           font-weight: bold;
       }
       li:last-child:after {
           content: '';
       }

       a {
           text-decoration: none;
           display: inline-block;
           color: #333;
           white-space: nowrap;
       }
       a:hover {
           text-decoration: underline;
       }


       /* Collapsed breadcrumbs */

       .collapsed li {
           overflow: hidden;
       }
       .collapsed li:after {
           background: rgb(245, 245, 245);
           background: linear-gradient(90deg, rgba(255, 255, 255, 0.84) 0%, rgb(255, 255, 255) 35%);
           padding-left: 1em;
       }

       .collapsed a {
           max-width: 2em;
           transition: max-width 300ms ease-in-out;
       }
       .collapsed a:hover,
       .collapsed a:focus,
       .collapsed li:hover a {
           max-width: 1000px;
       }
       .collapsed li:hover:after {
           padding-left: 0em;
           background: transparent;
       }

    }
</style>


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



