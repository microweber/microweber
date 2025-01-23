@php
/*

  type: layout

  name: Posts 10

  description: Posts 10

 */
@endphp

<div class="row py-4 blog-posts-10">
    @if (!empty($data))
        @foreach ($data as $key => $item)
            @php
                $categories = content_categories($item['id']);
                $itemCats = '';
                if ($categories) {
                    foreach ($categories as $category) {
                        $itemCats .= '<small class="text-dark bg-body font-weight-bold d-inline-block mb-2">' . $category['title'] . '</small> ';
                    }
                }
                $itemData = content_data($item['id']);
            @endphp
            @if ($key == 0)
                <div class="col-lg-6 col-lg-6" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                    <div class="post-holder mb-4">
                        <div class="d-flex flex-column">
                            <a href="{{ $item['link'] }}" class="d-block position-relative" itemprop="url">
                                @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                                    <div class="img-as-background">
                                        <img loading="lazy" style="max-height: 750px; width: auto; position: relative !important;" src="{{ $item['image'] }}" itemprop="image"/>
                                    </div>
                                @endif
                            </a>

                            <small class="mt-2 d-block" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                            @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                                <a href="{{ $item['link'] }}" class="text-dark text-decoration-none mb-2"><h3 itemprop="name">{{ $item['title'] }}</h3></a>
                            @endif

                            @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                                <p itemprop="description" class="">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        <div class="col-lg-6 col-lg-6">
            <div class="row">
                @foreach ($data as $key => $item)
                    @php
                        $itemData = content_data($item['id']);
                    @endphp
                    @if ($key == 1 or $key == 2 or $key == 3 or $key == 4)
                        <div class="col-md-6 post-holder">
                            <div class="d-flex flex-column">
                                <a href="{{ $item['link'] }}" class="d-block position-relative" itemprop="url">
                                    <div class="position-absolute">{!! $itemCats !!}</div>
                                    @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                                        <div class="img-as-background">
                                            <img loading="lazy" height="150" width="250" src="{{ $item['image'] }}" style="position: relative !important;" itemprop="image"/>
                                        </div>
                                    @endif
                                </a>

                                @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                                    <small class="mt-2 d-block" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                                @endif

                                @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                                    <a href="{{ $item['link'] }}" class="text-dark"><h4 class="text-start text-left" itemprop="name">{{ $item['title'] }}</h4></a>
                                @endif

                                @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                                    <p class="" itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
