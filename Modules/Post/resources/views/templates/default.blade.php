<?php

/*

type: layout

name: Default

description: Default - 3 Columns

*/


?>

@php
    $tn = $tn_size ?? [350, 350];
    $tn[1] = $tn[1] ?? $tn[0];
@endphp

@if ($data and !empty($data))
    @php
        $count = 0;
        $len = count($data);
        $helpclass = '';

    @endphp

    <div class="clearfix module-posts-template-default {{ $helpclass }}">
        @foreach ($data as $item)
            @php $count++; @endphp
            <div class="mw-ui-row">

                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <div class="mw-module-posts-default-item post-item-single" itemscope
                             itemtype="{{ $schema_org_item_type_tag }}">
                            @if (!$show_fields || in_array('thumbnail', $show_fields))
                                <a class="img-polaroid img-rounded" href="{{ $item['link'] }}">
                                <span class="valign">
                                    <span class="valign-cell">
                                        <img @if (!$item['image']) class="pixum"
                                             @endif src="{{ thumbnail($item['image'], $tn[0], $tn[1]) }}"
                                             alt="{{ $item['title'] }}" title="{{ $item['title'] }}" temprop="image"/>
                                    </span>
                                </span>
                                </a>
                            @endif
                            @if (!$show_fields || in_array('title', $show_fields))
                                <h3 itemprop="name"><a itemprop="url" class="lead"
                                                       href="{{ $item['link'] }}">{{ $item['title'] }}</a></h3>
                            @endif
                            @if ($show_fields && in_array('created_at', $show_fields))
                                <span class="date" itemprop="dateCreated">{{ $item['created_at'] }}</span>
                            @endif
                            @if (!$show_fields || ($show_fields && in_array('description', $show_fields)))
                                <p class="description" itemprop="description">{{ $item['description'] }}</p>
                            @endif
                            @if ($show_fields && in_array('read_more', $show_fields))
                                <a href="{{ $item['link'] }}" itemprop="url" class="mw-more">
                                    {{ $read_more_text ?? __('Read More', true) }}
                                </a>
                            @endif
                            <div class="post-price-holder clearfix">
                                @if (!$show_fields || in_array('price', $show_fields))
                                    @if (isset($item['prices']) && is_array($item['prices']))
                                        @php $val1 = array_shift($item['prices']); @endphp
                                        <span class="price">{{ currency_format($val1) }}</span>
                                    @endif
                                @endif
                                @if (!$show_fields || in_array('add_to_cart', $show_fields))

                                    @php
                                        $add_cart_text = $add_to_cart_text ?? __('Add to cart');
                                    @endphp
                                    @if (is_array($item['prices']) and !empty($item['prices']))
                                        <button class="btn btn-primary" type="button"
                                                onclick="mw.cart.add_and_checkout('{{ $item['id'] }}');">
                                            <i class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>&nbsp;{{ $add_cart_text }}
                                        </button>
                                    @endif


                                @endif
                            </div>
                            @if (is_array($item['prices']))
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

            </div>

        @endforeach

        @if (isset($pages_count) && $pages_count > 1 && isset($paging_param))
            {{ paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") }}
        @endif
    </div>
@else
    <div class="module-posts-template-no-data">
        <div>Sorry no posts</div>
    </div>
@endif
