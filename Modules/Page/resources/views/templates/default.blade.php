@php

/*

type: layout

name: Default

description: Default - 3 Columns

*/

@endphp

<style>
    #pages-{{ $params['id'] }} .thumbnail-image-holder {
        position: relative;
        overflow: hidden;
        height: 300px;
    }

    #pages-{{ $params['id'] }} .thumbnail-image-holder .thumbnail {
        object-fit: cover;
        width: 100%;
        height: 100%;
        min-width: calc(100% + 2px);
        min-height: 100%;
    }

    #pages-{{ $params['id'] }} .mw-pages-title a {
        color: var(--mw-heading-color);
        text-decoration: none;
    }

    #pages-{{ $params['id'] }} .page-bottom-holder {
        padding: 25px;
        padding-top: 20px;
    }

    #pages-{{ $params['id'] }} .module-pages-template-columns-3 .page-holder {
        background: var(--background);
        box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        margin: 20px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
        text-align: left;
    }

    #pages-{{ $params['id'] }} .page-list-image {
        width: 240px;
    }
</style>

@php
    $columns = get_option('columns', $params['id']);
    if (! $columns) {
        $columns = $params['data-columns'] ?? 'col-md-6 col-lg-4';
    }
    $columns_xl = get_option('columns-xl', $params['id']);
    $columns_md = get_option('columns-md', $params['id']);
    if ($columns_xl) { $columns .= ' ' . $columns_xl; }
    if ($columns_md) { $columns .= ' ' . $columns_md; }

    $count = 0;
@endphp

<div class="row module-pages-template-columns-3" id="pages-{{ $params['id'] }}">
    <div class="col-lg-12 mx-auto">
        <div class="row">
            @if (empty($data))

                @if (is_admin())
                    <div class="mw-canvas-empty-state" data-mw-content-type="page">
                        <h3 class="mw-canvas-empty-state__title">{{ __('No pages yet') }}</h3>
                        <p class="mw-canvas-empty-state__body">{{ __('Add your first page to fill this module.') }}</p>
                        <a class="mw-canvas-empty-state__cta" href="{{ route('filament.admin.resources.pages.create') }}" aria-label="{{ __('+ Add page') }}">{{ __('+ Add page') }}</a>
                    </div>
                @endif
            @else
                @foreach ($data as $key => $item)
                    @php $count++; @endphp

                    <div class="{{ $columns }}" data-aos="fade-up" data-aos-delay="{{ min($key, 8) * 100 }}" itemscope itemtype="https://schema.org/WebPage">
                        <div class="page-holder">
                            <a href="{{ $item['link'] }}" itemprop="url">
                                <div class="thumbnail-holder">
                                    @if (! isset($show_fields) || $show_fields == false || in_array('thumbnail', $show_fields))
                                        <div class="thumbnail-image-holder">
                                            {!! responsive_thumbnail($item['image'], 535, 285, [
                                                'class' => 'thumbnail img-fluid',
                                            ]) !!}
                                        </div>
                                    @endif
                                </div>
                            </a>
                            <div class="page-bottom-holder">
                                @if (! isset($show_fields) || $show_fields == false || in_array('title', $show_fields))

                                    <h3 itemprop="name" class="mw-pages-title m-0"><a href="{{ $item['link'] }}">{{ $item['title'] }}</a></h3>
                                @endif

                                @if (! isset($show_fields) || $show_fields == false || in_array('created_at', $show_fields))
                                    <small class="text-muted" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                                @endif

                                @if (! isset($show_fields) || $show_fields == false || in_array('description', $show_fields))
                                    <p itemprop="description" class="mt-3">{{ \Illuminate\Support\Str::limit(strip_tags((string) ($item['description'] ?? '')), 150) }}</p>
                                @endif

                                @if (! isset($show_fields) || $show_fields == false || in_array('read_more', $show_fields))
                                    <a href="{{ $item['link'] }}" class="button-8 m-t-20">
                                        <span>{{ $read_more_text ?? __('Read more') }}</span>
                                    </a>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

@if (isset($pages_count) && $pages_count > 1 && isset($paging_param))
    @php $current_page = $current_page ?? 1; @endphp
    {!! paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") !!}
@endif
