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



