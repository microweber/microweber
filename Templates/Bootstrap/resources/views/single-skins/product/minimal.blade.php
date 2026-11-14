<?php

/*

type: layout
content_type: product
name: Product - Minimal
position: 3
description: Clean, distraction-free single product - centered gallery, title, one price and add-to-cart.

*/

// Self-contained single-product layout (rendered directly by the resolver).
$content_data = content_data(content_id());
$in_stock = true;
if (isset($content_data['qty']) and $content_data['qty'] != 'nolimit' and intval($content_data['qty']) == 0) {
    $in_stock = false;
}
$__minPrices = get_product_prices(content_id(), true);
?>
@extends('templates.bootstrap::layouts.master')

@section('content')
<div class="mw-skin-min shop-inner-page" id="shop-content-{{ content_id() }}" field="shop-inner-page" rel="page">
    <section class="mw-min-main">
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-start justify-content-center">
                <div class="col-12 col-md-6">
                    <module type="pictures" rel="content" template="shop-inner-templates"/>
                </div>
                <div class="col-12 col-md-6 col-lg-5">
                    <h1 class="mw-min-title edit" field="title" rel="content">{{ content_title() }}</h1>

                    <div class="mw-min-price-row">
                        @if(isset($__minPrices[0]['original_value']))
                            <span class="mw-min-price-old">{{ currency_format($__minPrices[0]['original_value']) }}</span>
                        @endif
                        @if(isset($__minPrices[0]['value']))
                            <span class="mw-min-price">{{ currency_format($__minPrices[0]['value']) }}</span>
                        @endif
                    </div>

                    <div class="mw-min-stock">
                        @if($in_stock == true)
                            <span class="text-success">@lang("In Stock")</span>
                        @else
                            <span class="text-danger">@lang("Out of Stock")</span>
                        @endif
                        @if(isset($content_data['sku']) && $content_data['sku'] !== '')
                            <span class="mw-min-sku">@lang("SKU"): {{ $content_data['sku'] }}</span>
                        @endif
                    </div>

                    <div class="mw-min-cart bold">
                        <module type="shop/cart_add"/>
                    </div>

                    <div class="mw-min-desc edit" field="content_body" rel="content">
                        <p>@lang("Add a short, focused description that tells the customer exactly what they're getting.")</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Minimal product skin. Scoped to .mw-skin-min. */
.mw-skin-min .mw-min-main { padding: 2.5rem 0 4rem; }
.mw-skin-min .mw-min-title { font-size: 1.8rem; font-weight: 700; color: #182433; margin-bottom: 1rem; }
.mw-skin-min .mw-min-price-row { display: flex; align-items: baseline; gap: .6rem; margin-bottom: 1rem; }
.mw-skin-min .mw-min-price { font-size: 1.6rem; font-weight: 700; color: var(--mw-primary-color, #0d6efd); }
.mw-skin-min .mw-min-price-old { font-size: 1.05rem; color: #9aa3af; text-decoration: line-through; }
.mw-skin-min .mw-min-stock { display: flex; gap: 1rem; font-size: .85rem; margin-bottom: 1.5rem; }
.mw-skin-min .mw-min-sku { color: #6b7280; }
.mw-skin-min .mw-min-cart { margin-bottom: 1.75rem; }
.mw-skin-min .mw-min-desc { color: #4b5563; line-height: 1.7; }
</style>

@endsection
