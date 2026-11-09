{{--
    Self-contained /shop filter sidebar refinements.

    The same rules live in the framework's mw-shop.css, but that file is NOT
    loaded on every template (e.g. Big2), so on those templates the filter fell
    back to raw Bootstrap: a SOLID orange "All Categories" block, solid orange "$"
    input prefixes and the dated grey noUiSlider handle. Carrying the refinements
    with the shop component keeps the sidebar looking considered on ANY template —
    accent-aware via --mw-primary-color. wire:ignore so Livewire never re-morphs
    it on a filter interaction.
--}}
<div wire:ignore class="mw-shop-filter-styles" style="display:contents">
<style>
.mw-shop-filter-heading {
    font-size: 0.8rem !important;
    font-weight: 600 !important;
    letter-spacing: 0.04em !important;
    text-transform: uppercase !important;
    color: #6b7280 !important;
    margin-bottom: 0.6rem !important;
}
.mw-shop-filter-categories .list-group-item.list-group-item-action {
    border: 0;
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    margin-bottom: 2px;
    color: #333;
    background: transparent;
    font-weight: 500;
    transition: background-color 0.12s ease, color 0.12s ease;
}
.mw-shop-filter-categories .list-group-item.list-group-item-action:hover {
    background: #f4f5f7;
    color: #111;
}
.mw-shop-filter-categories .list-group-item.list-group-item-action.active {
    /* !important: a template/live_edit.css rule sets the Bootstrap .active
       background to the solid accent — beat it so the selected row is the soft
       tint, not a heavy orange block. */
    background: #fdf0e6 !important;
    background: color-mix(in srgb, var(--mw-primary-color, #f4a261) 14%, #fff) !important;
    color: #b8622a !important;
    color: color-mix(in srgb, var(--mw-primary-color, #f4a261) 82%, #000) !important;
    font-weight: 600;
    box-shadow: inset 3px 0 0 var(--mw-primary-color, #f4a261) !important;
}
.mw-shop-filter-categories .list-group-item.list-group-item-action:focus-visible {
    outline: 2px solid var(--mw-primary-color, #f4a261);
    outline-offset: -2px;
}
.mw-shop-filter-price-range .input-group-text {
    background: #f8f9fa !important;
    color: #6b7280 !important;
    border-color: #dee2e6 !important;
    font-weight: 500;
}
.mw-shop-filter-price-range label {
    font-size: 0.8rem;
    color: #6b7280;
    margin-bottom: 0.25rem;
}
.mw-shop-filter-price-range .form-control {
    border-color: #dee2e6;
}
.mw-shop-filter-price-range .form-control:focus {
    border-color: var(--mw-primary-color, #f4a261);
    box-shadow: none;
}
.mw-shop-filter-price-range .noUi-target {
    border: 0 !important;
    box-shadow: none !important;
    background: #e9ecef !important;
    border-radius: 999px !important;
}
.mw-shop-filter-price-range.noUi-horizontal,
.mw-shop-filter-price-range .noUi-horizontal {
    height: 6px !important;
}
.mw-shop-filter-price-range .noUi-connect {
    background: var(--mw-primary-color, #f4a261) !important;
}
.mw-shop-filter-price-range .noUi-horizontal .noUi-handle {
    width: 18px !important;
    height: 18px !important;
    right: -9px !important;
    top: -6px !important;
    border-radius: 50% !important;
    background: #fff !important;
    border: 2px solid var(--mw-primary-color, #f4a261) !important;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2) !important;
    cursor: pointer;
}
.mw-shop-filter-price-range .noUi-handle:before,
.mw-shop-filter-price-range .noUi-handle:after {
    display: none !important;
}
/* Gutter between the filter sidebar and the products — the two columns sum to
   100% (col-lg-3 + col-lg-9) with justify-content-between, so nothing separated
   them and the grid butted right against the filter. Inset each column's content
   on desktop (box-sizing:border-box, so no overflow). Applied here rather than a
   Bootstrap gap utility so it holds on templates that don't load those. */
@media (min-width: 992px) {
    .mw-shop-filter-col { padding-right: 1.25rem; }
    .mw-shop-products-col { padding-left: 1.75rem; }
}
</style>
</div>
