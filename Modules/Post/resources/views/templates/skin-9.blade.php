@php
/*

type: layout

name: Posts 9

description: Posts 9

*/
@endphp

<div class="row py-4 blog-posts-9">
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
                        $itemCats .= '<small class="text-dark bg-body px-2 py-1 font-weight-bold d-inline-block mb-2 me-2" itemprop="category">' . $category['title'] . '</small> ';
                    @endphp
                @endforeach
            @endif

            <div class="mx-auto col-sm-10 mx-md-0 col-md-6 col-lg-4 mb-5" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="overflow-hidden h-100 d-flex flex-column bg-body hover-">
                    @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                        <a href="{{ $item['link'] }}" class="d-block position-relative" itemprop="url">
                            <div class="img-as-background square-75" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                                <img loading="lazy" style="object-fit: cover;" src="{{ $item['image'] }}" alt="{{ $item['title'] }}" itemprop="url"/>
                                <meta itemprop="width" content="450">
                                <meta itemprop="height" content="450">
                            </div>
                        </a>
                    @endif

                    <div class="d-flex flex-column h-100 pt-3 p-4">
                        @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                            <small class="mb-3 mt-3 d-block" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                            <a href="{{ $item['link'] }}" class="text-dark text-decoration-none" itemprop="url">
                                <h5 class="mb-2" itemprop="name">{{ $item['title'] }}</h5>
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
