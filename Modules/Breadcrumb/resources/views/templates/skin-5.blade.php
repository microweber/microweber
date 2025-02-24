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



    }
</style>


<ul class="collapsed">
    <li><a href="#">Home</a></li><!--
  --><li><a href="#">First link</a></li><!--
  --><li><a href="#">Second link</a></li><!--
  --><li><a href="#">Another lengthier link</a></li><!--
  --><li><a href="#">Final link in the hierarchy</a></li><!--
  --><li>Current page</li>
</ul>

<div class="mw-big2-breadcrumb-skin-5">
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



