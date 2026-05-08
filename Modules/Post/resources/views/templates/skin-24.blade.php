@php
/*

type: layout

name: Posts 24

description: Posts 24

*/
@endphp

@once
    <link rel="stylesheet" href="{{ asset('modules/post/css/post-skins.css') }}">
@endonce
<div class="row py-4 blog-posts-24">
    @if(empty($data))
       <p class="mw-pictures-clean">No posts added. Please add posts to the module.</p>
   @else
        @foreach ($data as $key => $item)
            @if ($key == 0)
                <div class="col-lg-6" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                    <div class="post-holder zoom-on-hover mb-4">
                        @php
                            $categories = content_categories($item['id']);
                        @endphp

                        <div class="d-flex flex-column">
                            <a href="{{ $item['link'] }}" class="d-block position-relative" itemprop="url">
                                <div class="position-absolute">
                                    @if($categories)
                                        @foreach($categories as $category)
                                            <div class="news-category">{{ $category['title'] }}</div>
                                        @endforeach
                                    @endif
                                </div>

                                @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                                    <div class="img-as-background" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                                        <img loading="lazy" class="featured-image img-fluid" src="{{ $item['image'] }}" itemprop="url" alt="{{ $item['title'] }}"/>
                                        <meta itemprop="width" content="750">
                                        <meta itemprop="height" content="750">
                                    </div>
                                @endif
                            </a>

                            <div>
                                <small class="mt-3 d-block" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                                @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                                    <a href="{{ $item['link'] }}" class="mb-2">
                                        <h3 itemprop="name">{{ $item['title'] }}</h3>
                                    </a>
                                @endif

                                @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                                    <p itemprop="description" class="">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        <div class="col-lg-6">
            @foreach ($data as $key => $item)
                @if ($key == 1 or $key == 2 or $key == 3 or $key == 4)
                    <div class="post-holder zoom-on-hover d-flex flex-column flex-lg-row mb-3">
                        @php
                            $categories = content_categories($item['id']);
                        @endphp
                        <div class="w-100">
                            <a href="{{ $item['link'] }}" class="position-relative" itemprop="url">
                                <div class="position-absolute">
                                    @if($categories)
                                        @foreach($categories as $category)
                                            <div class="news-category">{{ $category['title'] }}</div>
                                        @endforeach
                                    @endif
                                </div>
                                @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                                    <div class="img-as-background" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                                        <img loading="lazy" class="thumbnail-image img-fluid" src="{{ $item['image'] }}" itemprop="url" alt="{{ $item['title'] }}"/>
                                        <meta itemprop="width" content="300">
                                        <meta itemprop="height" content="300">
                                    </div>
                                @endif
                            </a>
                        </div>

                        <div class="w-100 px-4">
                            @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                                <a href="{{ $item['link'] }}">
                                    <h5 class="mw-posts-24-title font-weight-bold mb-3" itemprop="name">{{ $item['title'] }}</h5>
                                </a>
                            @endif

                            @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                                <small class="my-2 d-block" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                            @endif

                            @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                                <p class="" itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
