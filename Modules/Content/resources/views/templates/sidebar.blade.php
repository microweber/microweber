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
                @if(is_admin())
                    @php
                        // AI-780a (task-2026-05-17-4c289e) — companion-template
                        // rollout of the AI-780 type-aware empty state.
                        // Mechanical-copy slice per designer dispatch.
                        // Original AI-780 references at task-2026-05-17-6d65de.
                        $mwAi780Type = $params['content_type'] ?? null;
                        // task-2026-05-17-fe8f9e / AI-801 -- Stage-1 fix:
                        // infer from $params['type'] when content_type
                        // is missing at runtime. See default.blade.php
                        // for the full lineage docblock.
                        if (! $mwAi780Type) {
                            $mwAi780Type = match ($params['type'] ?? null) {
                                'posts'    => 'post',
                                'pages'    => 'page',
                                'products' => 'product',
                                default    => null,
                            };
                        }
                        if ($mwAi780Type === 'post') {
                            $mwAi780Title = __('No posts yet');
                            $mwAi780Body = __('Add your first post to fill this module.');
                            $mwAi780CtaLabel = __('+ Add post');
                            $mwAi780CtaHref = e(admin_url('content/create?content_type=post'));
                        } elseif ($mwAi780Type === 'page') {
                            $mwAi780Title = __('No pages yet');
                            $mwAi780Body = __('Add your first page to fill this module.');
                            $mwAi780CtaLabel = __('+ Add page');
                            $mwAi780CtaHref = e(admin_url('content/create?content_type=page'));
                        } else {
                            $mwAi780Title = __('No content yet');
                            $mwAi780Body = __('Add your first item to fill this module.');
                            $mwAi780CtaLabel = __('+ Add content');
                            $mwAi780CtaHref = e(admin_url('content/create'));
                        }
                    @endphp
                    <div class="mw-canvas-empty-state" data-mw-ai780-content-type="{{ e($mwAi780Type ?? 'unknown') }}">
                        <h3 class="mw-canvas-empty-state__title">{{ $mwAi780Title }}</h3>
                        <p class="mw-canvas-empty-state__body">{{ $mwAi780Body }}</p>
                        <a class="mw-canvas-empty-state__cta" href="{{ $mwAi780CtaHref }}" aria-label="{{ $mwAi780CtaLabel }}">{{ $mwAi780CtaLabel }}</a>
                    </div>
                @endif
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
