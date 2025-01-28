@php
/*

type: layout

name: Posts pro-blog

description: Posts pro-blog

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

        img {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }
    }
    .skin-18--read-more-link:after{
        content: "\e658";
        color: inherit;
        font-family: 'icomoon-solid';
        vertical-align: middle;
        margin-inline-start: 9px;
    }
</style>

<div class="row blog-posts-pro-blog">
    @if(empty($data))
       <p class="mw-pictures-clean">No posts added. Please add posts to the gallery.</p>
   @else
        @php
            $item = reset($data);
            $categories = content_categories($item['id']);
        @endphp
        <div class="d-flex align-items-center justify-content-between">
            <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-3">Our Latest Blog</h2>

            <div class="mw-post-22-post-badge">
                @if($categories)
                    @foreach($categories as $category)
                        <a class="btn btn-secondary {{ $category['id'] == category_id() ? 'active' : '' }}"
                           href="{{ category_link($category['id']) }}">{{ $category['title'] }}</a>
                    @endforeach
                @endif
            </div>
        </div>

        @foreach($data as $item)
            @php
                $categories = content_categories($item['id']);
                $itemCats = '';
                if ($categories) {
                    foreach ($categories as $category) {
                        $itemCats .= '<small class="text-dark font-weight-bold d-inline-block mb-2" itemprop="name">' . $category['title'] . '</small> ';
                    }
                }
            @endphp
            <div class="mx-auto mx-md-0 col-12 mb-5" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="h-100 d-flex flex-wrap align-items-center">
                    <div class="col-lg-8 col-12 pt-4 pb-3 order-lg-1 order-2">
                        <small style="color: #2b2b2b;" class="mb-4 d-block" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                            <a href="{{ $item['link'] }}" class="" itemprop="url">
                                <h4 class="text-start text-left" itemprop="name">{{ $item['title'] }}</h4>
                            </a>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                            <p style="color: #2b2b2b;" class="" itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                        @endif

                        <div class="d-flex">
                            <a href="{{ $item['link'] }}" class="d-flex align-items-center action-blog-arrow skin-18--read-more-link" itemprop="url">
                                {!! $read_more_text !!}
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-12 justify-content-end ms-auto order-lg-2 order-1">
                        @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                            <a href="{{ $item['link'] }}" class="d-block" itemprop="url">
                                <div class="img-as-background">
                                    <img loading="lazy" src="{{ $item['image'] }}" style="position: relative !important;" itemprop="image"/>
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
