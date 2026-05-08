@php
/*

type: layout

name: Posts 26

description: Posts 26

*/
@endphp

@once
    <link rel="stylesheet" href="{{ asset('modules/post/css/post-skins.css') }}">
@endonce
<div class="row blog-posts-26 gap-4 justify-content-center">
    @if(empty($data))
       <p class="mw-pictures-clean">No posts added. Please add posts to the module.</p>
   @else
        @foreach ($data as $item)
            <div class="item col-xl-5 col-md-6 px-0" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="col-lg-6">
                    @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                        <div class="image" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                            {!! responsive_thumbnail($item['image'], 800, null, ['alt' => $item['title'], 'class' => 'img-fluid', 'itemprop' => 'url']) !!}
                            <meta itemprop="width" content="400">
                            <meta itemprop="height" content="400">
                        </div>
                    @endif
                </div>

                <div class="col-lg-5 align-self-center">
                    <div class="content ps-4">
                        <a itemprop="url" href="{{ $item['link'] }}">
                            <h4 itemprop="name">{{ $item['title'] }}</h4>
                        </a>

                        <div class="row blog-posts-26-custom-fields">
                            <module type="custom_fields" content_id="{{ $item['id'] }}" template="bootstrap5" default-fields="Duration[type=property,field_size=6,value=5 days],Trip price[type=property,field_size=6,value=100],"/>
                        </div>

                        @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                            <p itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields))
                            <div class="main-button">
                                <a class="btn btn-primary" href="{{ $item['link'] }}" itemprop="url">
                                    <span>{{ $read_more_text }}</span>
                                </a>
                            </div>
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
