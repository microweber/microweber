@php
    /*

    type: layout

    name: Skin-4

    description: Skin-4

    */
@endphp

@include('modules.product::templates.slick_options')

<style>
    #{{ $params['id'] }} .slick-arrows-1 .slick-prev {
        margin-left: -70px
    }

    #{{ $params['id'] }} .slick-arrows-1 .slick-next {
        margin-right: -70px
    }
</style>

<div class="slick-arrows-1">
       @if(empty($data))
       <p class="mw-pictures-clean">No products added. Please add products to the gallery.</p>
   @else
        <div class="slickslider slick-dots-relative shop-products">
            @foreach ($data as $item)
                @php
                    $categories = content_categories($item['id']);
                    $itemData = content_data($item['id']);
                    $itemTags = content_tags($item['id']);
                    $in_stock = !isset($itemData['qty']) || $itemData['qty'] == 'nolimit' || intval($itemData['qty']) > 0;
                    $itemCats = '';
                    if ($categories) {
                        foreach ($categories as $category) {
                            $itemCats .= $category['title'] . ', ';
                        }
                    }
                @endphp

                <div class="item-{{ $item['id'] }}" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                    <div class="product h-100 d-flex flex-column {{ $in_stock ? 'mw-layout-product-stock' : 'mw-layout-product-outOfStock' }}">
                        <div class="h-100 d-flex flex-column">
                            @if (is_array($item['prices']))
                                @foreach ($item['prices'] as $k => $v)
                                    <input type="hidden" name="price" value="{{ $v }}"/>
                                    <input type="hidden" name="content_id" value="{{ $item['id'] }}"/>
                                    @break
                                @endforeach
                            @endif

                            @if ($show_fields == false || in_array('thumbnail', $show_fields))
                                <a href="{{ $item['link'] }}">
                                    <div class="img-as-background square" itemprop="image">
                                        @if (isset($itemData['label-type']) && $itemData['label-type'] === 'text')
                                            <div class="position-absolute top-0 left-0 m-2" style="z-index: 3;">
                                                <div class="badge text-white px-3 pb-1 pt-2 rounded-0" style="background-color: {{ $itemData['label-color'] }};">{{ $itemData['label'] }}</div>
                                            </div>
                                        @endif

                                        @if (isset($item['original_price']) && $item['original_price'] != '')
                                            @php
                                                $vals2 = array_values($item['prices']);
                                                $val1 = array_shift($vals2);
                                                $percentChange = $item['price_discount_percent'] ?? 0;
                                            @endphp

                                            @if (isset($itemData['label-type']) && $itemData['label-type'] === 'percent' && $percentChange > 0)
                                                <div class="discount-label">
                                                    <span class="discount-percentage">{{ $percentChange }}%</span>
                                                    <span class="discount-label-text">{{ _lang("Discount") }}</span>
                                                </div>
                                            @endif
                                        @endif
                                        <img loading="lazy" style="object-fit: contain;" src="{{ thumbnail($item['image'], 1250, 1250) }}" itemprop="url" />
                                    </div>
                                </a>
                            @endif

                            <div class="pt-2">
                                @if ($show_fields == false || in_array('title', $show_fields))
                                    <a href="{{ $item['link'] }}" class="text-dark text-decoration-none text-center" itemprop="url">
                                        <h5 class="mt-1 mb-2" itemprop="name">{{ $item['title'] }}</h5>
                                    </a>
                                @endif

                                @php
                                    $itemPrices = $item['prices'];
                                    $firstPrice = reset($itemPrices);
                                @endphp
                                @if ($firstPrice !== false && $firstPrice > 0)
                                    <div class="price-holder justify-content-center">
                                        @if ($show_fields == false || in_array('price', $show_fields))
                                            @if (isset($item['prices']) && is_array($item['prices']))
                                                @php
                                                    $vals2 = array_values($item['prices']);
                                                    $val1 = array_shift($vals2);
                                                @endphp
                                                <p itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                                    @if (isset($item['original_price']) && $item['original_price'] != '')
                                                        <span class="price-old">{{ currency_format($item['original_price']) }}</span>
                                                    @endif
                                                    <span class="price" itemprop="price">{{ currency_format($val1) }}</span>
                                                    <meta itemprop="priceCurrency" content="{{ get_currency_symbol() }}" />
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </div>

                            @if ($firstPrice !== false && $firstPrice > 0)
                                @if ($show_fields == false || ($show_fields != false && in_array('add_to_cart', $show_fields)))
                                    @if ($in_stock == true)
                                        <div class="d-flex justify-content-center">
                                            <a href="javascript:;" onclick="mw.cart.add('.shop-products .item-{{ $item['id'] }}');" class="btn btn-outline-primary">Add to cart</a>
                                        </div>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
