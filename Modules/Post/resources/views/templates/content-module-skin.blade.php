@php
    /*

    type: layout

    name: For content module - works with pages

    description: For content module - works with pages

    */
@endphp

<div class="blog-posts-2 section content-module-wrapper">
    @if(empty($data))
       <p class="mw-pictures-clean">No posts added. Please add posts to the gallery.</p>
   @else
        <div class="container-fluid">
            <div class="row">
                @foreach ($data as $item)
                    @php
                        $categories = content_categories($item['id']);
                        $itemCats = '';
                        if ($categories) {
                            foreach ($categories as $category) {
                                $itemCats .= '<p class="text-dark font-weight-bold d-block mb-2" itemprop="description">' . $category['title'] . '</p> ';
                            }
                        }
                    @endphp
                    <div class="col-12 col-md-6 mb-3 py-4 mx-auto mb-5" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                        <div class="h-100 d-flex flex-column">
                            <a href="{{ $item['link'] }}" itemprop="url">
                                <div class="img__wrap">
                                    <div class="img-as-background h-650">
                                        <img class="img_img" alt="image" src="{{ $item['image'] }}" itemprop="image" />
                                    </div>
                                    <div class="img__description_layer">
                                        <div class="row text-center align-self-center"></div>
                                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                                            <h2 class="img__description" itemprop="name">
                                                <small class="mb-2 d-block" itemprop="dateCreated">{{ date('d M Y', strtotime($item['created_at'])) }}</small>
                                                {{ $item['title'] }}
                                            </h2>
                                        @endif
                                    </div>
                                </div>
                            </a>
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
