@php
/*

type: layout

name: Posts 20

description: Posts 20

*/
@endphp

<style>
    .blog-posts-20 .post-20::after {
        content: "";
        width: 60px;
        position: absolute;
        bottom: -3px;
        left: 50%;
        transform: translateX(-50%);
        border-bottom: 4px solid rgb(32, 32, 32);
        margin: 0px auto;
        transition: width 0.5s ease 0s;
    }

    .blog-posts-20 .post-20:hover::after {
        width: 100%;
    }

    .blog-posts-20 .img-as-background img {
        position: relative !important;
        object-fit: cover;
    }
</style>

<div class="row blog-posts-20">
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
            <div class="position-relative mx-auto mx-md-0 col-sm-10 col-md-6 mb-5" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="h-100 d-flex flex-column post-20">
                    @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                        <a href="{{ $item['link'] }}" class="d-block" itemprop="url">
                            <div class="img-as-background h-350" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                                <img loading="lazy" src="{{ $item['image'] }}" itemprop="url" alt="{{ $item['title'] }}"/>
                                <meta itemprop="width" content="350">
                                <meta itemprop="height" content="350">
                            </div>
                        </a>
                    @endif

                    <div class="pt-4 pb-3">
                        @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                            <small class="mb-2 d-block" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                            <a href="{{ $item['link'] }}" class="" itemprop="url">
                                <h6 class="text-start font-weight-bold" itemprop="name">{{ $item['title'] }}</h6>
                            </a>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                            <p class="" itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
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
