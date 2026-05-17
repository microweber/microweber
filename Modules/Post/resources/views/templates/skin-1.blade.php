@php

/*

type: layout

name: Posts 1

description: Posts 1 - with Hover

*/

// task-2026-05-17-bb503e / AI-841 — Posts shop.js perf cost gate.
//
// Sibling of AI-806-defect-#1 in the legacy-Blade audit class (the
// AI-806 family that removed unconditional mw.require('shop.js') from
// Pages entirely; Posts CAN carry prices via the extends-from-Content
// pattern so the shop.js load is legitimate when at least one item
// has a non-empty prices array — but unconditional loading wastes a
// network round-trip on every Posts list render where no item is
// priced).
//
// Pre-fix: <script>mw.require('shop.js');</script> emitted
// unconditionally at the top of this wrapper template (line 14
// pre-fix) before the Content skin-1 @include. Cart buttons in the
// included Content template are correctly gated behind
// !empty($item['prices']) checks (Content skin-1 lines 216/226/237),
// so the cart UI itself never appears for priceless items — but
// shop.js still loads.
//
// Post-fix (Slice A inline): pre-scan $data for any item carrying a
// non-empty prices array; only emit the mw.require() <script> tag if
// the result is truthy. Belt-and-braces: Content template's own
// gating remains intact (cart buttons stay conditional); this gate
// just skips the script load entirely when no cart UI will render.
//
// Slice B option (deferred — AI-841b follow-up candidate): extract
// the pre-scan + @if-gate to a shared partial at
// Modules/Post/resources/views/templates/partials/_shop-require-gate
// .blade.php once a 3rd Posts wrapper joins the cart-supporting set.
// Currently 2-file scope; inline keeps the audit trail per-file.
$mwAi841NeedsShop = false;
if (isset($data) && is_iterable($data)) {
    foreach ($data as $mwAi841Item) {
        if (is_array($mwAi841Item)
            && isset($mwAi841Item['prices'])
            && is_array($mwAi841Item['prices'])
            && !empty($mwAi841Item['prices'])
        ) {
            $mwAi841NeedsShop = true;
            break;
        }
    }
}

@endphp
@if ($mwAi841NeedsShop)
<script>
    mw.require('shop.js');
</script>
@endif
@include('modules.content::templates.skin-1')
