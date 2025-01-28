@php
    $columns = get_option('columns', $params['id']);
    if ($columns) {
        $columns = $columns;
    } elseif (isset($params['data-columns'])) {
        $columns = $params['data-columns'];
    } else {
        $columns = 'col-md-6 col-lg-4';
    }

    $columns_xl = get_option('columns-xl', $params['id']);
    $columns_md = get_option('columns-md', $params['id']);
    $columns_sm = get_option('columns-sm', $params['id']);

    if ($columns_xl) {
        $columns .= ' ' . $columns_xl;
    }
    if ($columns_md) {
        $columns .= ' ' . $columns_md;
    }

    $thumb_quality = '1920';
    if ($columns_xl != null || $columns_xl != false || $columns_xl != '') {
        if ($columns_xl == 'col-lg-12') {
            $thumbs_columns = 1;
        } elseif ($columns_xl == 'col-lg-6') {
            $thumbs_columns = 2;
        } elseif ($columns_xl == 'col-lg-4') {
            $thumbs_columns = 3;
        } elseif ($columns_xl == 'col-lg-3') {
            $thumbs_columns = 4;
        } elseif ($columns_xl == 'col-lg-2') {
            $thumbs_columns = 6;
        }

        $thumb_quality = 1920;
    }
    $count = 0;

@endphp

<style>
    #posts-{{ $params['id'] }} .thumbnail-image-holder {
        position: relative;
        overflow: hidden;
        height: 300px;
    }


    #posts-{{ $params['id'] }} .thumbnail-image-holder .thumbnail{
        object-fit: cover;
        width: 100%;
        height: 100%;
        min-width: calc(100% + 2px);
        min-height: 100%;
    }
    #posts-{{ $params['id'] }} .mw-products-title{
        a {

            color: var(--mw-heading-color);
            text-decoration: none;
        }
    }

    #posts-{{ $params['id'] }} .post-bottom-holder {
        padding: 25px;
        padding-top: 0;
    }

    #posts-{{ $params['id'] }} .big-news .post-holder h3 a{
        text-decoration: none;
    }
    #posts-{{ $params['id'] }} .big-news .post-holder {
        background: var(--background);
        box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        margin: 20px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
        text-align: left;
    }

    #posts-{{ $params['id'] }} .post-list-image{
        width: 240px;
    }
</style>

<div class="row" id="posts-{{ $params['id'] }}">
    <div class="col-lg-12 mx-auto">
        <div class="row big-news">
            @if(empty($data))
                <p class="mw-pictures-clean">No content added. Please add content to the gallery.</p>
            @else
                @foreach ($data as $key => $item)
                @php
                    $itemData = content_data($item['id']);
                    $itemTags = content_tags($item['id']);
                    $count++;
                @endphp

                <div class="{{ $columns }}" data-aos="fade-up" data-aos-delay="{{ $key }}00" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                    <div class="post-holder">
                        <a href="{{ $item['link'] }}" itemprop="url">
                            <div class="thumbnail-holder">
                                @if ($itemTags)
                                    <div class="tags">
                                        @foreach ($itemTags as $tag)
                                            @if ($key < 3)
                                                <span class="badge badge-primary">{{ $tag }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                @if (!isset($show_fields) || $show_fields == false || in_array('thumbnail', $show_fields))
                                    <div class="thumbnail-image-holder">
                                        <img class="thumbnail" src="{{ thumbnail($item['image'], 535, 285) }}" alt="">
                                    </div>
                                @endif
                            </div>
                        </a>
                        <br>
                        <div class="post-bottom-holder">
                            @if (!isset($show_fields) || $show_fields == false || in_array('title', $show_fields))
                                <h3 itemprop="name" class="mw-products-title m-0"><a href="{{ $item['link'] }}" itemprop="url">{{ $item['title'] }}</a></h3>
                            @endif

                            @if (!isset($show_fields) || $show_fields == false || in_array('created_at', $show_fields))
                                <small class="text-muted" itemprop="dateCreated">{{ __("Posted on") }}: {{ date_system_format($item['created_at']) }}</small>
                            @endif

                                @if (!isset($show_fields) || $show_fields == false || in_array('description', $show_fields))
                                    <p itemprop="description" class="mt-3">{{ \Illuminate\Support\Str::limit($item['description'], 150) }}</p>
                                @endif

                            @if (!isset($show_fields) || $show_fields == false || in_array('read_more', $show_fields))
                                <a href="{{ $item['link'] }}" itemprop="url" class="button-8 m-t-20">
                                    <span>{{ $read_more_text ?? __('Read more') }}</span>
                                </a>
                            @endif

                            @if (is_array($item['prices']) && !empty($item['prices']))

                                @php
                                    $prices =  $item['prices'];
                                    $val1 = array_shift($prices);
                                @endphp


                                <div class="post-price-holder d-flex align-items-center justify-content-between mt-3">
                                    @if (!$show_fields || in_array('price', $show_fields))
                                        @if (isset($item['prices']) && is_array($item['prices']))

                                            <h5 class="price">{{ currency_format($val1) }}</h5>

                                        @endif
                                    @endif

                                    @if (!$show_fields || in_array('add_to_cart', $show_fields))
                                        @php
                                            $add_cart_text = $add_to_cart_text ?? __('Add to cart');
                                        @endphp
                                        @if (is_array($item['prices']) && !empty($item['prices']))
                                            <button class="btn btn-primary" type="button" onclick="mw.cart.add_and_checkout('{{ $item['id'] }}');">
                                                <i class="mdi mdi-cart icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>&nbsp;{{ $add_cart_text }}
                                            </button>
                                        @endif
                                    @endif
                                </div>

                                @foreach ($item['prices'] as $k => $v)
                                    <div class="clear posts-list-proceholder mw-add-to-cart-{{ $item['id'].$count }}">
                                        <input type="hidden" name="price" value="{{ $v }}"/>
                                        <input type="hidden" name="content_id" value="{{ $item['id'] }}"/>
                                    </div>
                                    @break
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
           @endif
        </div>
    </div>
</div>
