<?php

/*

type: layout

name: skin-10

description: skin-10

*/
?>


@php
    $tn = $tn_size ?? [350, 350];
    if (!isset($tn[0]) or ($tn[0]) == 150) {
        $tn[0] = 350;
    }
    if (!isset($tn[1])) {
        $tn[1] = $tn[0];
    }
@endphp

@if (!empty($data))
    <div class="row shop-products">
        @foreach ($data as $key => $item)
            @php
                $categories = content_categories($item['id']);
                $itemData = content_data($item['id']);
                $itemTags = content_tags($item['id']);
                $in_stock = true;
                if (isset($itemData['qty']) and $itemData['qty'] != 'nolimit' and intval($itemData['qty']) == 0) {
                    $in_stock = false;
                }
                if (!isset($itemData['label'])) {
                    $itemData['label'] = '';
                }
                if (!isset($itemData['label-color'])) {
                    $itemData['label-color'] = '';
                }
                $itemCats = '';
                if ($categories) {
                    foreach ($categories as $category) {
                        $itemCats .= $category['title'] . ', ';
                    }
                }
            @endphp

            <div class="mx-auto mx-md-0 col-lg-12 mb-4 item-{{ $item['id'] }}"
                 data-masonry-filter="{{ $itemCats }}" itemscope
                 itemtype="{{ $schema_org_item_type_tag }}">
                <div class="product h-100 d-flex flex-column position-relative">
                    <div class="h-100">
                        @if (is_array($item['prices']))
                            @foreach ($item['prices'] as $k => $v)
                                <input type="hidden" name="price" value="{{ $v }}"/>
                                <input type="hidden" name="content_id" value="{{ $item['id'] }}"/>
                                @break
                            @endforeach
                        @endif

                        @if ($show_fields == false or in_array('thumbnail', $show_fields))
                            <a class="col-md-6 col-12 d-block mx-auto" href="{{ $item['link'] }}">
                                @if (isset($itemData['label-type']) && $itemData['label-type'] === 'text')
                                    <div class="position-absolute top-0 left-0 m-2" style="z-index: 3;">
                                        <div class="badge text-white px-3 pb-1 pt-2 rounded-0"
                                             style="background-color: {{ $itemData['label-color'] }};">{{ $itemData['label'] }}</div>
                                    </div>
                                @endif

                                @if (isset($item['original_price']) and $item['original_price'] != '')
                                    @php
                                        $vals2 = array_values($item['prices']);
                                        $val1 = array_shift($vals2);
                                        $percentChange = 0;
                                        if (isset($item['price_discount_percent']) and $item['price_discount_percent']) {
                                            $percentChange = $item['price_discount_percent'];
                                        }
                                    @endphp
                                    @if (isset($itemData['label-type']) && $itemData['label-type'] === 'percent' && $percentChange > 0)
                                        <div class="discount-label">
                                            <span class="discount-percentage">
                                                {{ $percentChange }}%
                                            </span>
                                            <span class="discount-label-text">@lang("Discount")</span>
                                        </div>
                                    @endif
                                @endif

                                <img loading="lazy" style="object-fit: contain;" class="w-100"
                                     src="{{ thumbnail($item['image'], 1350, 1350) }}"
                                     itemprop="image"/>
                            </a>
                        @endif

                        <div class="col-md-6 col-12 p-2 text-center justify-content-center mx-auto">
                            <div class="d-none">
                                {{ $itemCats }}
                            </div>
                            <div class="row mt-1">
                                <div class="col">
                                    @if ($show_fields == false or in_array('title', $show_fields))
                                        <a href="{{ $item['link'] }}" class="text-dark text-decoration-none">
                                            <h4 itemprop="name">{{ $item['title'] }}</h4>
                                        </a>
                                    @endif

                                    @if (isset($item['description']))
                                        <div class="py-2" itemprop="description">
                                            <p>{{ $item['description'] }}</p>
                                        </div>
                                    @endif

                                    @php
                                        $itemPrices = $item['prices'];
                                        $firstPrice = reset($itemPrices);
                                    @endphp
                                    @if ($firstPrice !== false && $firstPrice > 0)
                                        <div class="price-holder justify-content-center">
                                            @if ($show_fields == false or in_array('price', $show_fields))
                                                @if (isset($item['prices']) and is_array($item['prices']))
                                                    @php
                                                        $vals2 = array_values($item['prices']);
                                                        $val1 = array_shift($vals2);
                                                    @endphp
                                                    @if (isset($item['original_price']) and $item['original_price'] != '')
                                                        [...]
                                                    @endif
                                                    <h6 class="price mb-0">{{ currency_format($val1) }}</h6>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                @if ($firstPrice !== false && $firstPrice > 0)
                                    <div class="py-4">
                                        @if ($show_fields == false or ($show_fields != false and in_array('add_to_cart', $show_fields)))
                                            @if ($in_stock == true)
                                                <a href="javascript:;"
                                                   onclick="mw.cart.add('.shop-products .item-{{ $item['id'] }}');"
                                                   class="btn btn-primary px-5 btn-lg" itemprop="url"> @lang("Buy now")</a>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
