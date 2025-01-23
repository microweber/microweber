@php
/*

type: layout

name: Posts 18

description: Posts 18

*/
@endphp

<style>
    .action-blog-arrow {
        font-weight: bold;
        border-bottom: 1px solid transparent;
        transition: all 0.7s;
    }

    .action-blog-arrow:hover {
        text-decoration: none!important;
        color: #FFA028!important;
        border-color: #FFA028;
        transition: all 0.7s;
    }

    .action-blog-arrow i:hover {
        background-color: unset!important;
    }

    .blog-posts-18 .img-as-background {
        height: 250px;
        width: 300px;
    }

    .blog-posts-18 .img-as-background img {
        object-fit: cover;
        height: 100%;
        width: 100%;
    }

    .skin-18--read-more-link:after {
        content: "\e658";
        color: inherit;
        font-family: 'icomoon-solid';
        vertical-align: middle;
        margin-inline-start: 9px;
    }

    .blog-posts-18 .date-text,
    .blog-posts-18 .description-text {
        color: #2b2b2b;
    }
</style>

<div class="row blog-posts-18">
    @if (!empty($data))
        @foreach ($data as $item)
            @php
                $categories = content_categories($item['id']);
                $itemCats = '';
            @endphp
            @if($categories)
                @foreach($categories as $category)
                    @php
                        $itemCats .= '<small class="text-dark font-weight-bold d-inline-block mb-2" itemprop="category">' . $category['title'] . '</small> ';
                    @endphp
                @endforeach
            @endif
            <div class="mx-auto mx-md-0 col-12 mb-5" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="h-100 d-flex flex-wrap align-items-center">
                    <div class="col-lg-8 col-12 pt-4 pb-3 order-lg-1 order-2">
                        <small class="mb-4 d-block date-text" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                            <a href="{{ $item['link'] }}" class="" itemprop="url">
                                <h4 class="text-start" itemprop="name">{{ $item['title'] }}</h4>
                            </a>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                            <p class="description-text" itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                        @endif

                        <div class="d-flex">
                            <a href="{{ $item['link'] }}" class="d-flex align-items-center action-blog-arrow skin-18--read-more-link" itemprop="url">
                                {{ $read_more_text }}
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-12 justify-content-end ms-auto order-lg-2 order-1">
                        @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                            <a href="{{ $item['link'] }}" class="d-block" itemprop="url">
                                <div class="img-as-background" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                                    <img loading="lazy" src="{{ $item['image'] }}" style="position: relative !important;" itemprop="url" alt="{{ $item['title'] }}"/>
                                    <meta itemprop="width" content="300">
                                    <meta itemprop="height" content="250">
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
