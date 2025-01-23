@php

/*

type: layout

name: Posts 11

description: Posts 11

*/
@endphp

<div class="row py-4">
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

            <div class="mx-auto col-sm-10 col-md-10 col-lg-8 mb-7" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="text-center">
                    @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                        <div class="mb-3">
                            <small class="" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                        </div>
                    @endif

                    @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                        <a href="{{ $item['link'] }}" class="text-dark"><h3 class="mb-2" itemprop="name">{{ $item['title'] }}</h3></a>
                    @endif

                    @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                        <p itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                    @endif

                    @if (isset($item['created_by']))
                        @php
                            $user = get_user_by_id($item['created_by']);
                        @endphp

                        <div class="d-flex d-sm-flex align-items-center justify-content-center">
                            @if (isset($user['thumbnail']))
                                <div class="me-2">
                                    @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                                        <div class="w-40">
                                            <div class="img-as-background rounded-circle square">
                                                <img loading="lazy" src="{{ thumbnail($user['thumbnail'], 1200, 1200) }}" itemprop="image" />
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div>
                                <small class="mb-1 font-weight-bold d-block" itemprop="author">
                                    @if (isset($user['first_name'])){{ $user['first_name'] }}@endif&nbsp;
                                    @if (isset($user['last_name'])){{ $user['last_name'] }}@endif
                                </small>
                                @if (isset($user['user_information']))<small class="mb-0 text-dark">{{ $user['user_information'] }}</small>@endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
