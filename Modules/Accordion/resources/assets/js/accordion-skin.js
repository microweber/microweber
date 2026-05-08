/*
 * AI-80 / TICKET-AK (cycle-92 2026-05-09): Accordion default skin
 * chevron toggle handler.
 *
 * Lifted from the per-instance inline `<script>` block at the top of
 * `Modules/Accordion/resources/views/templates/default.blade.php`.
 * The inline shape collided with `script-src 'self'` (no
 * `unsafe-inline`) AND bound the same handler N times when N
 * Accordion modules were dropped on a page.
 *
 * This file replaces the per-instance binding with ONE document-
 * level delegated jQuery handler that:
 *
 *   1. Listens for Bootstrap 5's `shown.bs.collapse` /
 *      `hidden.bs.collapse` events on `.mw-accordion-faq-skin-card`
 *      collapse panels.
 *   2. Locates the sibling `.mw-accordion-faq-skin-header` and
 *      toggles its `i.mdi` between `mdi-plus` ⇄ `mdi-minus` plus
 *      the `.active` class.
 *
 * Multi-instance safe — each event fires on the panel that opened
 * or closed; we walk to the matching header inside the same card.
 */
(function () {
    if (typeof window.jQuery === 'undefined') {
        return;
    }
    var $ = window.jQuery;

    function toggleChevron(e) {
        var $panel = $(e.target);
        if (!$panel.closest('.mw-accordion-faq-skin-card').length) {
            return;
        }
        $panel
            .prev('.mw-accordion-faq-skin-header')
            .find('i.mdi')
            .toggleClass('mdi-minus mdi-plus')
            .toggleClass('active');
    }

    $(document).on(
        'shown.bs.collapse hidden.bs.collapse',
        '.mw-accordion-faq-skin-card > .collapse',
        toggleChevron
    );
})();
