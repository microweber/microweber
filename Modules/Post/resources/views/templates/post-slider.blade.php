@php
/*

type: layout

name: Posts Slider 1

description: Posts Slider 1

*/
@endphp

@include('modules.post::partials.slick_options')

<div class="slick-arrows-1">
    <div class="row py-4 blog-posts-1 slickslider slick-dots-relative">
        @if(empty($data))
       <p class="mw-pictures-clean">No posts added. Please add posts to the module.</p>
   @else
            @foreach ($data as $item)
            {{-- audit-test 2026-05-07 Post Lists audit finding #1 (SECURITY HIGH):
                 stored-XSS — category title was string-concatenated into
                 $itemCats and printed via {!! !!} (raw output), so any
                 HTML/<script> in a category title executed verbatim on
                 every page that rendered the slider. Rewrote as a Blade
                 @foreach with {{ }} so each title goes through htmlspecialchars. --}}
            @php $categories = content_categories($item['id']); @endphp

            <div class="mx-3 col-sm-10 col-md-6 col-lg-4 mb-5" itemscope itemtype="http://schema.org/Article">
                <div class="overflow-hidden h-100 d-flex flex-column">
                    @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                        <a itemprop="url" href="{{ $item['link'] }}">
                            <div class="img-as-background h-350">
{!! responsive_thumbnail($item['image'], 800, null, ['alt' => $item['title'] ?? '', 'class' => 'img-fluid', 'style' => 'position: relative !important;', 'itemprop' => 'image']) !!}
                            </div>
                        </a>
                    @endif

                    <div class="pt-3 pb-5 mt-md-auto mt-5">
                        @if ($categories)
                            @foreach ($categories as $category)
                                <small class="text-dark font-weight-bold d-block mb-2" itemprop="description">{{ $category['title'] }}</small>
                            @endforeach
                        @endif
                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                            <h4 itemprop="name" class="mb-2">{{ $item['title'] }}</h4>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                            <p itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                        @endif

                        <div class="m-t-auto">
                            @if (!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields))
                                <a href="{{ $item['link'] }}" class="" itemprop="url">
                                    <span itemprop="name">
                                        {{ $read_more_text ?? 'Read more' }}
                                    </span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
