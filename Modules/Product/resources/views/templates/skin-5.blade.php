@php
    /*

    type: layout

    name: Skin-5

    description: Skin-5

    */
@endphp

@include('modules.product::templates.slick_options')

<style>
    #{{ $params['id'] }} .slick-arrows-1 .slick-prev {
        top: 33%;
    }

    #{{ $params['id'] }} .slick-arrows-1 .slick-next {
        top: 33%;
    }

    @media (max-width: 950px) {
        .slick-arrows-1 .slick-next, .slick-arrows-1 .slick-prev {
            display: none !important;
        }
    }
</style>

<div class="slick-arrows-1">
    @if (!empty($data))
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
                            $itemCats .= '<small class="text-dark d-inline-block mb-2">' . $category['title'] . '</small> ';
                        }
                    }
                @endphp

                <div class="mb-5 item-{{ $item['id'] }}" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                    <div class="product h-100 d-flex flex-column px-sm-2 position-relative show-on-hover-root">
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
                                    <div class="img-as-background h-350 overflow-hidden" itemprop="image">
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
                                                    <span class="discount-percentage" itemprop="priceDiscount">{{ $percentChange }}%</span>
                                                    <span class="discount-label-text">{{ _lang("Discount") }}</span>
                                                </div>
                                            @endif
                                        @endif
                                        <img loading="lazy" style="object-fit: contain;" src="{{ thumbnail($item['image'], 850, 850) }}" itemprop="image" />
                                    </div>
                                </a>
                            @endif

                            <div class="pt-2 mt-auto">
                                @if ($show_fields == false || in_array('title', $show_fields))
                                    <a href="{{ $item['link'] }}" class="text-dark text-decoration-none text-center" itemprop="url">
                                        <h5 itemprop="name">{{ $item['title'] }}</h5>
                                    </a>
                                @endif

                                @php
                                    $itemPrices = $item['prices'];
                                    $firstPrice = reset($itemPrices);
                                @endphp
                                @if ($firstPrice !== false && $firstPrice > 0)
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="price-holder text-center">
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
                                                        </p>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-6 text-end text-right">
                                            <a href="{{ $item['link'] }}" class="btn btn-outline-primary btn-sm">View</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
