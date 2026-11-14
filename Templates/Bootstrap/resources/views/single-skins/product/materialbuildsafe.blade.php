<?php

/*

type: layout
content_type: product
name: Product - Conversion (MaterialBuildSafe)
position: 2
description: High-conversion storefront product page - sticky gallery, bold price with save badge, urgency, trust row and tabbed details.

*/

// Self-contained single-product layout. The template resolver renders this
// file directly when the product's layout_file points at it, so it computes
// its own context (no reliance on product.blade.php) and extends master for
// the site chrome.
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

// Pricing for the buy box. get_product_prices() returns the active price row
// (value) and, when a sale is set, the crossed-out original_value.
$__mbsPrices = get_product_prices(content_id(), true);
$__mbsPrice = isset($__mbsPrices[0]['value']) ? $__mbsPrices[0]['value'] : null;
$__mbsOld = isset($__mbsPrices[0]['original_value']) ? $__mbsPrices[0]['original_value'] : null;
$__mbsSave = null;
if ($__mbsOld && $__mbsPrice && (float) $__mbsOld > (float) $__mbsPrice) {
    $__mbsSave = (int) round((((float) $__mbsOld - (float) $__mbsPrice) / (float) $__mbsOld) * 100);
}
?>
@extends('templates.bootstrap::layouts.master')

@section('content')
<div class="mw-skin-mbs shop-inner-page" id="shop-content-{{ content_id() }}" field="shop-inner-page" rel="page">

    <div class="mw-mbs-urgency">
        <div class="container">
            <span class="mw-mbs-urgency-item">🔥 @lang("Limited-time offer")</span>
            <span class="mw-mbs-urgency-item">🚚 @lang("Free shipping over")&nbsp;{{ currency_format(50) }}</span>
            <span class="mw-mbs-urgency-item">↩️ @lang("30-day returns")</span>
        </div>
    </div>

    <section class="mw-mbs-main">
        <div class="container">
            <nav class="mw-mbs-breadcrumb" aria-label="breadcrumb">
                <a href="{{ site_url() }}">@lang("Home")</a>
                <span>/</span>
                <a href="{{ site_url('shop') }}">@lang("Shop")</a>
                <span>/</span>
                <span class="mw-mbs-crumb-current">{{ content_title() }}</span>
            </nav>

            <div class="row g-4 g-lg-5">
                {{-- Gallery --}}
                <div class="col-12 col-lg-7">
                    <div class="mw-mbs-gallery">
                        <module type="pictures" rel="content" template="shop-inner-templates"/>
                    </div>
                </div>

                {{-- Buy box --}}
                <div class="col-12 col-lg-5">
                    <div class="mw-mbs-buybox">
                        <div class="mw-mbs-rating">
                            <span class="mw-mbs-stars" aria-hidden="true">★★★★★</span>
                            <span class="mw-mbs-rating-text">@lang("Trusted by thousands of buyers")</span>
                        </div>

                        <h1 class="mw-mbs-title edit d-inline-block" field="title" rel="content">{{ content_title() }}</h1>

                        <div class="mw-mbs-price-row">
                            @if($__mbsPrice !== null)
                                <span class="mw-mbs-price">{{ currency_format($__mbsPrice) }}</span>
                            @endif
                            @if($__mbsOld !== null)
                                <span class="mw-mbs-price-old">{{ currency_format($__mbsOld) }}</span>
                            @endif
                            @if($__mbsSave !== null)
                                <span class="mw-mbs-save-badge">@lang("Save") {{ $__mbsSave }}%</span>
                            @endif
                        </div>

                        <div class="mw-mbs-stock">
                            @if($in_stock == true)
                                <span class="mw-mbs-instock"><i class="fa fa-circle" aria-hidden="true"></i> @lang("In Stock")@if($available_qty != '') &middot; {{ $available_qty }} @lang("available")@endif</span>
                            @else
                                <span class="mw-mbs-oos"><i class="fa fa-circle" aria-hidden="true"></i> @lang("Out of Stock")</span>
                            @endif
                            @if(isset($content_data['sku']) && $content_data['sku'] !== '')
                                <span class="mw-mbs-sku">@lang("SKU"): {{ $content_data['sku'] }}</span>
                            @endif
                        </div>

                        <div class="mw-mbs-cart bold">
                            <module type="shop/cart_add"/>
                        </div>

                        <ul class="mw-mbs-trust">
                            <li><span class="mw-mbs-trust-ico">✓</span> @lang("Secure checkout")</li>
                            <li><span class="mw-mbs-trust-ico">✓</span> @lang("Free & fast delivery")</li>
                            <li><span class="mw-mbs-trust-ico">✓</span> @lang("Money-back guarantee")</li>
                            <li><span class="mw-mbs-trust-ico">✓</span> @lang("24/7 customer support")</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Details --}}
            <div class="mw-mbs-details">
                <h2 class="mw-mbs-section-title">@lang("Product details")</h2>
                <div class="mw-mbs-description edit" field="content_body" rel="content">
                    <p>@lang("Describe what makes this product worth buying — materials, dimensions, what's in the box, and how it solves the customer's problem.")</p>
                </div>
            </div>

            {{-- Related --}}
            <div class="mw-mbs-related edit safe-mode" field="related_products" rel="content">
                <h2 class="mw-mbs-section-title">@lang("You may also like")</h2>
                <module type="shop/products" template="skin-1" related="true" limit="4" hide_paging="true"/>
            </div>
        </div>
    </section>
</div>

<style>
/* Conversion product skin (MaterialBuildSafe). Scoped to .mw-skin-mbs so it
   never leaks into other layouts. Colours use the template brand tokens with
   safe fallbacks. */
.mw-skin-mbs { --mbs-accent: var(--mw-primary-color, #0d6efd); --mbs-ink: #182433; --mbs-line: #e6e9ef; }
.mw-skin-mbs .mw-mbs-urgency { background: var(--mbs-ink); color: #fff; font-size: .82rem; }
.mw-skin-mbs .mw-mbs-urgency .container { display: flex; gap: 1.5rem; flex-wrap: wrap; justify-content: center; padding: .55rem 1rem; }
.mw-skin-mbs .mw-mbs-urgency-item { white-space: nowrap; opacity: .95; }
.mw-skin-mbs .mw-mbs-main { padding: 2rem 0 3.5rem; }
.mw-skin-mbs .mw-mbs-breadcrumb { font-size: .82rem; color: #6b7280; margin-bottom: 1.25rem; display: flex; gap: .4rem; flex-wrap: wrap; }
.mw-skin-mbs .mw-mbs-breadcrumb a { color: #6b7280; text-decoration: none; }
.mw-skin-mbs .mw-mbs-breadcrumb a:hover { color: var(--mbs-accent); }
.mw-skin-mbs .mw-mbs-crumb-current { color: var(--mbs-ink); font-weight: 600; }
.mw-skin-mbs .mw-mbs-gallery { position: sticky; top: 1.5rem; }
.mw-skin-mbs .mw-mbs-buybox { padding: .25rem 0; }
.mw-skin-mbs .mw-mbs-rating { display: flex; align-items: center; gap: .5rem; margin-bottom: .6rem; }
.mw-skin-mbs .mw-mbs-stars { color: #f5a623; letter-spacing: 2px; }
.mw-skin-mbs .mw-mbs-rating-text { font-size: .8rem; color: #6b7280; }
.mw-skin-mbs .mw-mbs-title { font-size: 2rem; line-height: 1.2; font-weight: 700; color: var(--mbs-ink); margin: 0 0 1rem; }
.mw-skin-mbs .mw-mbs-price-row { display: flex; align-items: baseline; gap: .75rem; flex-wrap: wrap; margin-bottom: .75rem; }
.mw-skin-mbs .mw-mbs-price { font-size: 2rem; font-weight: 800; color: var(--mbs-accent); }
.mw-skin-mbs .mw-mbs-price-old { font-size: 1.15rem; color: #9aa3af; text-decoration: line-through; }
.mw-skin-mbs .mw-mbs-save-badge { background: #e8f7ee; color: #157347; font-weight: 700; font-size: .78rem; padding: .25rem .6rem; border-radius: 999px; }
.mw-skin-mbs .mw-mbs-stock { display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; font-size: .86rem; margin-bottom: 1.25rem; }
.mw-skin-mbs .mw-mbs-instock { color: #157347; font-weight: 600; }
.mw-skin-mbs .mw-mbs-instock i, .mw-skin-mbs .mw-mbs-oos i { font-size: 8px; vertical-align: middle; }
.mw-skin-mbs .mw-mbs-oos { color: #dc3545; font-weight: 600; }
.mw-skin-mbs .mw-mbs-sku { color: #6b7280; }
.mw-skin-mbs .mw-mbs-cart { margin-bottom: 1.5rem; }
.mw-skin-mbs .mw-mbs-trust { list-style: none; padding: 1.25rem 0 0; margin: 0; border-top: 1px solid var(--mbs-line); display: grid; grid-template-columns: 1fr 1fr; gap: .6rem 1rem; }
.mw-skin-mbs .mw-mbs-trust li { font-size: .85rem; color: #4b5563; display: flex; align-items: center; gap: .5rem; }
.mw-skin-mbs .mw-mbs-trust-ico { display: inline-flex; width: 20px; height: 20px; border-radius: 50%; background: #e8f7ee; color: #157347; align-items: center; justify-content: center; font-size: .7rem; font-weight: 700; flex: none; }
.mw-skin-mbs .mw-mbs-details, .mw-skin-mbs .mw-mbs-related { margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--mbs-line); }
.mw-skin-mbs .mw-mbs-section-title { font-size: 1.35rem; font-weight: 700; color: var(--mbs-ink); margin-bottom: 1rem; }
.mw-skin-mbs .mw-mbs-description { color: #4b5563; line-height: 1.7; }
@media (max-width: 991.98px) {
    .mw-skin-mbs .mw-mbs-gallery { position: static; margin-bottom: 1.5rem; }
    .mw-skin-mbs .mw-mbs-title { font-size: 1.6rem; }
}
</style>

@endsection
