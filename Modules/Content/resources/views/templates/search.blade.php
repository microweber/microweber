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
            @if(empty($data))
                {{-- AI-104 / TICKET-AI-104 (cycle-101 2026-05-09): wrap empty-content placeholder in is_admin() so it stays visible to editors but doesn't leak onto anonymous public pages. --}}
                @if(is_admin())
                    @php
                        // AI-780a (task-2026-05-17-4c289e) — companion-template
                        // rollout of the AI-780 type-aware empty state.
                        // Mechanical-copy slice per designer dispatch.
                        // Original AI-780 references at task-2026-05-17-6d65de.
                        $mwEmptyType = $params['content_type'] ?? null;
                        // task-2026-05-17-fe8f9e / AI-801 -- Stage-1 fix:
                        // infer from $params['type'] when content_type
                        // is missing at runtime. See default.blade.php
                        // for the full lineage docblock.
                        if (! $mwEmptyType) {
                            $mwEmptyType = match ($params['type'] ?? null) {
                                'posts'    => 'post',
                                'pages'    => 'page',
                                'products' => 'product',
                                default    => null,
                            };
                        }
                        if ($mwEmptyType === 'post') {
                            $mwEmptyTitle = __('No posts yet');
                            $mwEmptyBody = __('Add your first post to fill this module.');
                            $mwEmptyCtaLabel = __('+ Add post');
                            $mwEmptyCtaHref = route('filament.admin.resources.posts.create');
                        } elseif ($mwEmptyType === 'page') {
                            $mwEmptyTitle = __('No pages yet');
                            $mwEmptyBody = __('Add your first page to fill this module.');
                            $mwEmptyCtaLabel = __('+ Add page');
                            $mwEmptyCtaHref = route('filament.admin.resources.pages.create');
                        } else {
                            $mwEmptyTitle = __('No content yet');
                            $mwEmptyBody = __('Add your first item to fill this module.');
                            $mwEmptyCtaLabel = __('+ Add content');
                            $mwEmptyCtaHref = route('filament.admin.resources.contents.create');
                        }
                    @endphp
                    <div class="mw-canvas-empty-state" data-mw-content-type="{{ e($mwEmptyType ?? 'unknown') }}">
                        <h3 class="mw-canvas-empty-state__title">{{ $mwEmptyTitle }}</h3>
                        <p class="mw-canvas-empty-state__body">{{ $mwEmptyBody }}</p>
                        <a class="mw-canvas-empty-state__cta" href="{{ $mwEmptyCtaHref }}" aria-label="{{ $mwEmptyCtaLabel }}">{{ $mwEmptyCtaLabel }}</a>
                    </div>
                @endif
            @else
                @foreach ($data as $item)
                <li>
                    <div class="row">
                        @if(!isset($show_fields) || $show_fields == false || in_array('thumbnail', $show_fields))
                            <div class="col-auto module-posts-template-search-image-holder">
                                <a href="{{ $item['link'] }}" class="module-posts-template-search-image">
                                    {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                                    {!! responsive_thumbnail($item['image'], $tn[0], $tn[1], [
                                        'class' => 'img-fluid',
                                    ]) !!}
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
        @endif
        </ul>
    @endif

    @if (isset($pages_count) && $pages_count > 1 && isset($paging_param))
        {!! paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") !!}
    @endif
</div>
