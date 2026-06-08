<?php
/*
type: layout
name: sidebar
description: sidebar
*/
?>

@php
$tn = $tn_size;
if(!isset($tn[0]) || ($tn[0]) == 150){
     $tn[0] = 70;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}
@endphp

<style>
    .module-posts-template-sidebar li{
        list-style: none
    }

    .module-posts-template-sidebar-image-column{
        width: 80px;
    }

    .module-posts-template-sidebar-content-column h5{
        margin-top: 0;
        margin-bottom: 0;
    }
</style>

<div class="module-posts-template-sidebar">
    @if (!empty($data))
        <ul>
            @if(empty($data))
                {{-- AI-104 / TICKET-AI-104 (cycle-101 2026-05-09): wrap empty-content placeholder in is_admin() so it stays visible to editors but doesn't leak onto anonymous public pages. --}}
                {{-- task-2026-06-07-pmprod: empty-state logic + markup centralised in
                     \Modules\Content\Services\ContentModuleEmptyState + the shared partial
                     below — replaces the copy-pasted ~50-line inline-PHP block (posts/pages/
                     products/content parity). --}}
                @include('modules.content::partials.module-empty-state', ['params' => $params])
            @else
                @foreach ($data as $item)
                <li>
                    <div class="mw-ui-row-nodrop">
                        <div class="mw-ui-col module-posts-template-sidebar-image-column">
                            <a href="{{ $item['link'] }}">
                                @if(!isset($show_fields) || $show_fields == false || in_array('thumbnail', $show_fields))
                                    {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                                    {!! responsive_thumbnail($item['image'], $tn[0], $tn[1], [
                                        'class' => 'img-fluid',
                                    ]) !!}
                                @endif
                            </a>
                        </div>
                        <div class="mw-ui-col module-posts-template-sidebar-content-column">
                            <div class="mw-ui-col-container">
                                @if(!isset($show_fields) || $show_fields == false || in_array('title', $show_fields))
                                    <h5><a class="link media-heading" href="{{ $item['link'] }}">{{ $item['title'] }}</a></h5>
                                @endif
                                @if(!isset($show_fields) || $show_fields == false || in_array('created_at', $show_fields))
                                    <small class="date">{{ $item['created_at'] }}</small>
                                @endif

                                @if(!isset($show_fields) || $show_fields == false || in_array('description', $show_fields))
                                    <p>{{ $item['description'] }}</p>
                                @endif

                                @if(!isset($show_fields) || $show_fields == false || in_array('read_more', $show_fields))
                                    <a href="{{ $item['link'] }}" class="btn btn-default btn-mini">{{ $read_more_text ?? __('Continue Reading') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
            @endif
        </ul>
    @endif
</div>

@if (isset($pages_count) && $pages_count > 1 && isset($paging_param))
    {!! paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") !!}
@endif
