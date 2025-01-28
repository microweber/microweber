@php
/*

type: layout

name: Posts 3

description: Posts 3

*/
@endphp

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
            <div class="mx-auto mx-md-0 col-sm-10 col-md-6 col-lg-4 mb-5" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="h-100 d-flex flex-column">
                    @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                        <a href="{{ $item['link'] }}" class="d-block" itemprop="url">
                            <div class="img-as-background h-350">
                                <img loading="lazy" src="{{ $item['image'] }}" style="position: relative !important;" itemprop="image"/>
                            </div>
                        </a>
                    @endif

                    <div class="pt-4 pb-3">
                        @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                            <small class="mb-2 d-block" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                            <a href="{{ $item['link'] }}" class="" itemprop="url">
                                <h4 class="text-start text-left" itemprop="name">{{ $item['title'] }}</h4>
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
