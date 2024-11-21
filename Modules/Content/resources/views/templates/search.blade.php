<?php
/*
type: layout
name: Search
description: Search
visible: no
*/
?>

<style>
    .module-posts-template-search{
        min-width: 100%;
        background: white;
        border-radius: 3px;
        border: 1px solid #E3E3E3;
    }

    .module-posts-template-search li{
        list-style: none;
        padding: 12px;
    }

    .module-posts-template-search li,
    .module-posts-template-search p{
        font-size: 12px;
    }

    .module-posts-template-search .pagination-holder{
        padding-left: 12px;
        padding-right: 12px;
    }

    .module-posts-template-search-image{
        margin-right: 12px;
    }

    .module-posts-template-search-image-holder{
        width: 82px;
    }

    .module-posts-template-search-body > h5{
        margin-top: 0;
    }
</style>

@php
$tn = $tn_size;
if(!isset($tn[0]) || ($tn[0]) == 150){
     $tn[0] = 70;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}
@endphp

<div class="module-posts-template-search">
    @if (!empty($data))
        <ul>
            @foreach ($data as $item)
                <li>
                    <div class="row">
                        @if(!isset($show_fields) || $show_fields == false || in_array('thumbnail', $show_fields))
                            <div class="col-auto module-posts-template-search-image-holder">
                                <a href="{{ $item['link'] }}" class="module-posts-template-search-image">
                                    <img src="{{ thumbnail($item['image'], $tn[0], $tn[1]) }}" alt="" width="50" height="50"/>
                                </a>
                            </div>
                        @endif
                        <div class="col">
                            <div class="module-posts-template-search-body">
                                @if(!isset($show_fields) || $show_fields == false || in_array('title', $show_fields))
                                    <a class="link media-heading text-decoration-none" style="font-size: 14px;" href="{{ $item['link'] }}">{{ $item['title'] }}</a>
                                @endif
                            </div>
                        </div>

                        <div class="col-auto">
                            @if ($show_fields == false || in_array('price', $show_fields))
                                <div class="price">
                                    @if (isset($item['prices']) && is_array($item['prices']))
                                        @php
                                            $vals2 = array_values($item['prices']);
                                            $val1 = array_shift($vals2);
                                        @endphp
                                        <span>{{ currency_format($val1) }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    @if (isset($pages_count) && $pages_count > 1 && isset($paging_param))
        {!! paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") !!}
    @endif
</div>
