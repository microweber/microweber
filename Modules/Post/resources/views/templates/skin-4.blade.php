@php
/*

type: layout

name: Posts Slider 4

description: Posts Slider 4

*/
@endphp

@include('modules.post::partials.slick_options')

<div class="slick-arrows-1">
    <div class="py-4 blog-posts-4 slickslider slick-dots-relative">
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
                            $itemCats .= '<small class="text-outline-primary font-weight-bold d-block mb-2" itemprop="category">' . $category['title'] . '</small> ';
                        @endphp
                    @endforeach
                @endif

                <div class="mb-5 pe-5" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                    <div class="row">
                        @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                            <div class="col-auto text-center">
                                <p itemprop="dateCreated" class="mb-0">{{ date('M', strtotime($item['created_at'])) }}</p>
                                <hr class="thin my-2" style="min-width: 65px;"/>
                                <p itemprop="dateCreated" class="mb-2">{{ date('d', strtotime($item['created_at'])) }}</p>
                            </div>
                        @endif

                        <div class="col pt-3 pb-5">
                            {!! $itemCats !!}
                            @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                                <a href="{{ $item['link'] }}" class="text-dark" itemprop="url"><h4 class="mb-2" itemprop="name">{{ $item['title'] }}</h4></a>
                            @endif

                            @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                                <p itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                            @endif

                            @if (isset($item['created_by']))
                                @php
                                    $user = get_user_by_id($item['created_by']);
                                @endphp
                                <div class="mb-4">
                                    <div class="d-flex d-sm-flex align-items-center">
                                        @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                                            @if (isset($user['thumbnail']))
                                                <div class="me-2">
                                                    <div class="w-40">
                                                        <div class="img-as-background rounded-circle square">
                                                            <img loading="lazy" src="{{ thumbnail($user['thumbnail'], 1200, 1200) }}" itemprop="image"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                        <div>
                                            <small class="mb-1 font-weight-bold d-block">
                                                @if (isset($user['first_name'])){{ $user['first_name'] }}@endif&nbsp;
                                                @if (isset($user['last_name'])){{ $user['last_name'] }}@endif
                                            </small>
                                            @if (isset($user['user_information']))<small class="mb-0 text-dark">{{ $user['user_information'] }}</small>@endif
                                        </div>
                                    </div>
                                </div>
                            @endif
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
