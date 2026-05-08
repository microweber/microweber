@php
/*

type: layout

name: Posts 22

description: Posts 22

*/
@endphp

@once
    <link rel="stylesheet" href="{{ asset('modules/post/css/post-skins.css') }}">
@endonce
<div class="blog-posts-22">
    @if(empty($data))
       <p class="mw-pictures-clean">No posts added. Please add posts to the module.</p>
   @else
        <div class="mw-post-22-element mw-post-22-card-shadow-yes mw-post-22-widget">
            <div class="row mw-post-22-posts mw-post-22-widget-container">
                @foreach ($data as $item)
                    <div class="col-md-4 col-sm-10 col-12 mx-auto mb-4" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                        <div class="mw-post-22-post-card">
                            @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                                <a class="mw-post-22-post-thumbnail-link" itemprop="url" href="{{ $item['link'] }}">
                                    <div class="mw-post-22-post-thumbnail" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                                        {!! responsive_thumbnail($item['image'], 800, null, ['alt' => $item['title'], 'class' => 'attachment-full size-full img-fluid', 'itemprop' => 'url']) !!}
                                        <meta itemprop="width" content="400">
                                        <meta itemprop="height" content="400">
                                    </div>
                                </a>
                            @endif

                            <div class="mw-post-22-post-badge">
                                @php
                                    $categories = content_categories($item['id']);
                                @endphp
                                @if($categories)
                                    @foreach($categories as $category)
                                        {{ $category['title'] }}
                                    @endforeach
                                @endif
                            </div>

                            <div class="mw-post-22-post-text">
                                @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                                    <h3 class="mw-post-22-post-title" itemprop="name">
                                        <a itemprop="url" href="{{ $item['link'] }}">{{ $item['title'] }}</a>
                                    </h3>
                                @endif

                                @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                                    <div class="mw-post-22-post-excerpt">
                                        <p itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                                    </div>
                                @endif

                                @if (!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields))
                                    <a itemprop="url" href="{{ $item['link'] }}" class="mw-post-22-post-read-more" aria-label="Read more about {{ $item['title'] }}">View Partner »</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
