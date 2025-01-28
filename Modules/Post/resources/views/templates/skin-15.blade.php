@php
/*

type: layout

name: Posts 15

description: Posts 15

*/
@endphp

<style>
.blog-posts-3 .date-color {
    color: #FF7A01;
}
</style>

<div class="row blog-posts-3">
    @if(empty($data))
       <p class="mw-pictures-clean">No posts added. Please add posts to the gallery.</p>
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
            <div class="mx-auto mx-md-0 col-12 col-xl-4 mb-5" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="h-100 d-flex flex-column">
                    @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                        <a href="{{ $item['link'] }}" class="d-block px-md-0 px-2" itemprop="url">
                            <div class="img-as-background h-600" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                                <img loading="lazy" src="{{ $item['image'] }}" style="top: unset!important; position: relative !important;" itemprop="url" alt="{{ $item['title'] }}"/>
                                <meta itemprop="width" content="600">
                                <meta itemprop="height" content="600">
                            </div>
                        </a>
                    @endif

                    <div class="pt-4 pb-3">
                        @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                            <small class="mb-3 d-block date-color" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                            <a href="{{ $item['link'] }}" class="px-md-0 px-2" itemprop="url">
                                <h4 class="text-start" itemprop="name">{{ $item['title'] }}</h4>
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
