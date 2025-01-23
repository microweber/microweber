@php

/*

type: layout

name: Posts 6

description: Posts 6

*/
@endphp

<div class="row py-4 blog-posts-6">
    @if (!empty($data))
        @foreach ($data as $item)
            @php
                $categories = content_categories($item['id']);
                $itemCats = '';
                if ($categories) {
                    foreach ($categories as $category) {
                        $itemCats .= '<small class="text-outline-primary font-weight-bold d-inline-block mb-2 me-2" itemprop="category">' . $category['title'] . '</small> ';
                    }
                }
            @endphp

            <div class="mx-auto col-sm-10 mx-md-0 col-md-4 mb-5" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="px-5 h-100">
                    <div class="d-flex flex-column h-100">
                        <div>
                            @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                                <a href="{{ $item['link'] }}" class="text-dark text-decoration-none"><h4 class="mb-2">{{ $item['title'] }}</h4></a>
                            @endif

                            @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                                <div class="mb-3">
                                    <small>{{ date_system_format($item['created_at']) }}</small>
                                </div>
                            @endif

                            @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                                <p itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                            @endif
                        </div>

                        @if (isset($item['created_by']))
                            @php
                                $user = get_user_by_id($item['created_by']);
                            @endphp

                            <div class="d-flex d-sm-flex align-items-center">
                                @if (isset($user['thumbnail']))
                                    <div class="me-2">
                                        @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                                            <div class="w-40">
                                                <div class="img-as-background rounded-circle square">
                                                    <img loading="lazy" src="{{ thumbnail($user['thumbnail'], 1200, 1200) }}" />
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div>
                                    <small class="mb-1 font-weight-bold d-block">
                                        @if (isset($user['first_name'])){{ $user['first_name'] }}@endif&nbsp;
                                        @if (isset($user['last_name'])){{ $user['last_name'] }}@endif
                                    </small>
                                    @if (isset($user['user_information']))<small class="mb-0 text-dark">{{ $user['user_information'] }}</small>@endif
                                </div>
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
