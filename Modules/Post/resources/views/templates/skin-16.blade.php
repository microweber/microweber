@php
/*

type: layout

name: Posts 16

description: Posts 16

*/
@endphp

@once
    <link rel="stylesheet" href="{{ asset('modules/post/css/post-skins.css') }}">
@endonce
<div class="row merry-blog-posts blog-posts-3">
    @if(empty($data))
       <p class="mw-pictures-clean">No posts added. Please add posts to the module.</p>
   @else
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
            <div class="mx-auto mx-md-0 col-sm-10 col-md-6 col-xl-3 mb-5" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="h-100 d-flex flex-column">
                    @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                        <div class="img-as-background h-350" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                            <div class="merry-on-hover-button">
                                <a href="{{ $item['link'] }}" itemprop="url">
                                    <i class="mw-micon-Google-Play"></i>
                                </a>
                            </div>
                            {!! responsive_thumbnail($item['image'], 800, null, ['alt' => $item['title'], 'class' => 'img-fluid', 'style' => 'position: relative !important;', 'itemprop' => 'url']) !!}
                            <meta itemprop="width" content="350">
                            <meta itemprop="height" content="350">
                        </div>
                    @endif

                    <div class="pt-4 pb-3">
                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                            <a href="{{ $item['link'] }}" class="" itemprop="url">
                                <h6 class="text-start" itemprop="name">{{ $item['title'] }}</h6>
                            </a>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                            <small class="d-block" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
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
