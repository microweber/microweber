@php
/*

  type: layout

  name: Posts 5

  description: Posts 5

 */
@endphp

<div class="row py-4 blog-posts-5">
    @if (!empty($data))
        @foreach ($data as $key => $item)
            @php
                $itemData = content_data($item['id']);
            @endphp
            @if ($key == 0)
                <div class="col-lg-6 col-lg-8 mx-auto py-2" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                    <div class="post-holder mb-4">
                        @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                            <small class="d-block mb-4" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                            <a href="{{ $item['link'] }}" itemprop="url"><h3 itemprop="name">{{ $item['title'] }}</h3></a>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                            <p itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                        @endif

                        @if (isset($item['created_by']))
                            @php
                                $user = get_user_by_id($item['created_by']);
                            @endphp
                            <div class="d-flex d-sm-flex align-items-center mt-6">
                                @if (isset($user['thumbnail']))
                                    <div class="me-3">
                                        @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                                            <div class="w-40 mx-auto">
                                                <div class="img-as-background rounded-circle square">
                                                    <img loading="lazy" src="{{ thumbnail($user['thumbnail'], 1200, 1200) }}" itemprop="image"/>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div>
                                    <p class="mb-1 font-weight-bold d-block">
                                        @if (isset($user['first_name'])){{ $user['first_name'] }}@endif&nbsp;
                                        @if (isset($user['last_name'])){{ $user['last_name'] }}@endif
                                    </p>
                                    @if (isset($user['user_information']))<p class="mb-0">{{ $user['user_information'] }}</p>@endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach

        <div class="col-lg-6 col-lg-8 mx-auto py-2">
            @foreach ($data as $key => $item)
                @php
                    $itemData = content_data($item['id']);
                @endphp
                @if ($key == 1 or $key == 2)
                    <div class="post-holder mb-4">
                        @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                            <small class="d-block mb-4" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                            <a href="{{ $item['link'] }}" class="text-dark" itemprop="url"><h3 itemprop="name">{{ $item['title'] }}</h3></a>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                            <p itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                        @endif

                        @if (isset($item['created_by']))
                            @php
                                $user = get_user_by_id($item['created_by']);
                            @endphp
                            <div class="d-flex d-sm-flex align-items-center mt-6">
                                @if (isset($user['thumbnail']))
                                    <div class="me-3">
                                        <div class="w-40">
                                            <div class="img-as-background rounded-circle square">
                                                <img loading="lazy" src="{{ thumbnail($user['thumbnail'], 80, 80) }}" itemprop="image"/>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <p class="mb-1 font-weight-bold d-block">
                                        @if (isset($user['first_name'])){{ $user['first_name'] }}@endif&nbsp;
                                        @if (isset($user['last_name'])){{ $user['last_name'] }}@endif
                                    </p>
                                    @if (isset($user['user_information']))<p class="mb-0">{{ $user['user_information'] }}</p>@endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
