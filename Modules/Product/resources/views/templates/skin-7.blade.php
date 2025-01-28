<?php

/*

type: layout

name: Skin-7

description: Skin-7

*/
?>

<style>

    <?php echo '#'.$params['id']; ?>
    .heading-holder {
        color: var(--mw-heading-color);
    }
</style>

@php
    $tn = $tn_size;
    if (!isset($tn[0]) or ($tn[0]) == 150) {
        $tn[0] = 350;
    }
    if (!isset($tn[1])) {
        $tn[1] = $tn[0];
    }
@endphp

   @if(empty($data))
       <p class="mw-pictures-clean">No products added. Please add products to the gallery.</p>
   @else
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

            <div class="col-12 col-md-4 item-{{ $item['id'] }}" data-masonry-filter="{{ $itemCats }}" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="product mb-4 {{ $in_stock ? 'mw-layout-product-stock' : 'mw-layout-product-outOfStock' }}" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    @if (is_array($item['prices']))
                        @foreach ($item['prices'] as $k => $v)
                            <input type="hidden" name="price" value="{{ $v }}"/>
                            <input type="hidden" name="content_id" value="{{ $item['id'] }}"/>
                            @break
                        @endforeach
                    @endif

                    @if ($show_fields == false or in_array('thumbnail', $show_fields))
                        <div class="image" style="background-image: url('{{ thumbnail($item['image'], 1250, 1250) }}'); height: 500px;" itemprop="image">
                            @if (isset($itemData['label-type']) && $itemData['label-type'] === 'text')
                                <div class="position-absolute top-0 left-0 m-2" style="z-index: 3;">
                                    <div class="badge text-white px-3 pb-1 pt-2 rounded-0" style="background-color: {{ $itemData['label-color'] }};">{{ $itemData['label'] }}</div>
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
                                        <span class="discount-percentage">{{ $percentChange }}%</span>
                                        <span class="discount-label-text">@lang("Discount")</span>
                                    </div>
                                @endif
                            @endif
                            <a href="{{ $item['link'] }}" class="d-flex h-100 w-100" itemprop="url"></a>
                        </div>
                    @endif

                    <div>
                        @if ($show_fields == false or in_array('title', $show_fields))
                            <a href="{{ $item['link'] }}" class="text-decoration-none" itemprop="name">
                                <div class="heading-holder text-md-start text-center mt-3">
                                    <h5>{{ $item['title'] }}</h5>
                                </div>
                            </a>
                        @endif

                        @php
                            $itemPrices = $item['prices'] ?? [0];
                            $firstPrice = reset($itemPrices);
                        @endphp
                        @if ($firstPrice !== false && $firstPrice > 0)
                            <div class="row justify-content-center">
                                <div class="col-6 price-holder justify-content-md-start align-items-center justify-content-center">
                                    @if ($show_fields == false or in_array('price', $show_fields))
                                        @if (isset($item['prices']) and is_array($item['prices']))
                                            @php
                                                $vals2 = array_values($item['prices']);
                                                $val1 = array_shift($vals2);
                                            @endphp
                                            <p itemprop="price">
                                                @if (isset($item['original_price']) and $item['original_price'] != '')
                                                    <span class="price-old">{{ currency_format($item['original_price']) }}</span>
                                                @endif
                                                <span class="price">{{ currency_format($val1) }}</span>
                                            </p>
                                        @endif
                                    @endif
                                </div>

                                <div class="col-sm-6 col-12 d-flex justify-content-md-end justify-content-center">
                                    <div>
                                        @if ($show_fields == false or ($show_fields != false and in_array('add_to_cart', $show_fields)))
                                            @if ($in_stock == true)
                                                <a href="javascript:;" onclick="mw.cart.add('.shop-products .item-{{ $item['id'] }}');" class="btn btn-outline-primary btn-sm"> @lang("Buy")</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
