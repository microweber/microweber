@php
/*

type: layout

name: Posts 17

description: Posts 17

*/
@endphp

<style>
    .blog-16-merry-card {
        padding: 10px;
        border: 2px solid #181E4E;
    }

    .merry-blog-posts-2 .img-as-background:hover {
        transform: scale(1.05);
        transition: 1.3s;
    }
</style>

<div class="row blog-posts-3 merry-blog-posts-2 justify-content-center">
    @if(empty($data))
       <p class="mw-pictures-clean">No posts added. Please add posts to the gallery.</p>
   @else
        <div class="col-12 d-flex flex-wrap justify-content-center">
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

                <div class="col-sm-10 col-md-6 col-xl-4 px-4 mb-5" itemscope itemtype="http://schema.org/BlogPosting">
                    <div class="h-100 blog-16-merry-card">
                        @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                            <a href="{{ $item['link'] }}" class="d-block" itemprop="url">
                                <div class="img-as-background h-350" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                                    <img loading="lazy" src="{{ $item['image'] }}" style="position: relative !important;" itemprop="url" alt="{{ $item['title'] }}"/>
                                    <meta itemprop="width" content="350">
                                    <meta itemprop="height" content="350">
                                </div>
                            </a>
                        @endif

                        <div class="pt-4 pb-3 px-4">
                            @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                                <a href="{{ $item['link'] }}" class="" itemprop="url">
                                    <h6 class="text-start" itemprop="name">{{ $item['title'] }}</h6>
                                </a>
                            @endif

                            @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                                <p class="" itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
