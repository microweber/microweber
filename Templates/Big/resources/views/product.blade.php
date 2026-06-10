<?php
$content_data = content_data(content_id());
$in_stock = true;
if (isset($content_data['qty']) and $content_data['qty'] != 'nolimit' and intval($content_data['qty']) == 0) {
    $in_stock = false;
}

if (isset($content_data['qty']) and $content_data['qty'] == 'nolimit') {
    $available_qty = '';
} elseif (isset($content_data['qty']) and $content_data['qty'] != 0) {
    $available_qty = $content_data['qty'];
} else {
    $available_qty = 0;
}

$item = get_content_by_id(content_id());
$itemData = content_data(content_id());
$itemTags = content_tags(content_id());

if (!isset($itemData['label'])) {
    $itemData['label'] = '';
}
if (!isset($itemData['label-color'])) {
    $itemData['label-color'] = '';
}

$next = next_content(content_id());
$prev = prev_content(content_id());
?>
@extends('templates.big::layouts.master')

@section('content')
    <div class="shop-inner-page" id="shop-content-{{ content_id() }}" field="shop-inner-page" rel="page">
        <section class="py-md-5 mb-md-5 fx-particles">
            <div class="container-fluid mw-m-t-30 mw-m-b-50">
                <div class="row justify-content-center">
                    <div class="row product-holder">
                        <div class="col-12 col-md-6 col-lg-6">
                            <module type="pictures" rel="content" template="shop-inner-templates"/>
                        </div>

                        <div class="col-12 col-md-6 col-lg-6 relative product-info-wrapper">
                            <div class="product-info">
                                <div class="product-info-content">
                                    <div class="heading mt-sm-4 mt-md-0 pb-0 mb-2">
                                        <h1 class="edit d-inline-block" field="title" rel="content">{{ content_title() }}</h1>

                                        <div class="next-previous-content float-end">
                                            @if($prev != false)
                                            <a href="{{ content_link($prev['id']) }}"
                                               class="prev-content tip btn btn-outline-default" data-tip="#prev-tip"><i
                                                    class="mdi mdi-arrow-left"></i></a>
                                            <div id="prev-tip" style="display: none">
                                                <div class="next-previous-tip-content text-center">
                                                    {{-- AI-88 / BIG2-B (cycle-98 2026-05-09):
                                                         was alt="" — empty alt on a meaningful
                                                         thumbnail blocks SR users from knowing
                                                         which product they're navigating to.
                                                         Title-as-alt + lazy/decoding hints. --}}
                                                    <img src="{{ get_picture($prev['id']) }}" alt="{{ $prev['title'] ?? '' }}"
                                                         loading="lazy" decoding="async"
                                                         width="90"/>
                                                    <h6>{{ $prev['title'] }}</h6>
                                                </div>
                                            </div>
                                            @endif

                                            @if($next != false)
                                            <a href="{{ $next['url'] }}"
                                               class="next-content tip btn btn-outline-default" data-tip="#next-tip"><i
                                                    class="mdi mdi-arrow-right"></i></a>

                                            <div id="next-tip" style="display: none">
                                                <div class="next-previous-tip-content text-center">
                                                    {{-- AI-88 / BIG2-B (cycle-98): same as prev. --}}
                                                    <img src="{{ get_picture($next['id']) }}" alt="{{ $next['title'] ?? '' }}"
                                                         loading="lazy" decoding="async"
                                                         width="90"/>
                                                    <h6>{{ $next['title'] }}</h6>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row main-price">
                                        <div class="col-12 d-flex">
                                            <div class="col-6">
                                                @php $prices = get_product_prices(content_id(), true); @endphp
                                                @if(isset($prices[0]) and is_array($prices))
                                                <p>
                                                    @if(isset($prices[0]['original_value']))
                                                        <span class="price-old">{{ currency_format($prices[0]['original_value']) }}</span>
                                                    @endif
                                                    @if(isset($prices[0]['value']))
                                                        <span class="price">{{ currency_format($prices[0]['value']) }}</span>
                                                    @endif
                                                </p>
                                                @endif
                                            </div>

                                            <div class="availability col-6 text-end text-right align-self-center">
                                                {{-- AI-93 / BIG2-F (cycle-98 2026-05-09): the
                                                     `<i class="fa fa-circle">` is decorative
                                                     (the colour conveys In/Out stock; the text
                                                     "In Stock" / "Out of Stock" is the
                                                     informational content). aria-hidden="true"
                                                     keeps SR from announcing "circle" before
                                                     the meaningful text. --}}
                                                @if($in_stock == true)
                                                <span class="text-success"><i class="fa fa-circle" aria-hidden="true"
                                                                              style="font-size: 8px;"></i> @lang("In Stock")</span>
                                                <span class="text-muted">@if($available_qty != '')({{ $available_qty }})@endif</span>
                                                @else
                                                <span class="text-danger"><i class="fa fa-circle" aria-hidden="true"
                                                                             style="font-size: 8px;"></i> @lang("Out of Stock")</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 mt-3">
                                            @if(isset($content_data['sku']))
                                                @lang("SKU")
                                            - {{ $content_data['sku'] }}
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="description">
                                                <div class="edit" field="content_body" rel="content">
                                                    <p>@lang("How to write product descriptions that sell")</p>
                                                    <p>@lang("One of the best things you can do to make your store successful is invest some time in writing great product descriptions. You want to provide detailed yet concise information that will entice potential customers to buy.")</p>

                                                    <p>@lang("Think like a consumer")</p>
                                                    <p>@lang("Think about what you as a consumer would want to know, then include those features in your description. For clothes: materials and fit. For food: ingredients and how it was prepared. Bullets are your friends when listing features — try to limit each one to 5-8 words.")</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bold">
                                        <module type="shop/cart_add"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="edit safe-mode  py-5" field="related_products" rel="content">
                            <div class="col-12 text-start text-left mb-4">
                                <h2 class="related-title">@lang('Related products')</h2>
                            </div>

                            <div class="col-12">
                                <module type="shop/products" template="skin-1" related="true" limit="4" hide_paging="true"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
