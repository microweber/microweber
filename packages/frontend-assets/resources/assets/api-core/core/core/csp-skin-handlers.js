/*
 * AI-75 / TICKET-BB (cycle-87 2026-05-09): CSP-hardening — delegated
 * click listener for the public module-skin handlers that previously
 * used inline `onclick=` attributes.
 *
 * Inline `onclick="..."` attributes are blocked by a strict
 * Content-Security-Policy (`script-src 'self'`); replacing them with
 * data-* attributes + a single delegated listener keeps the same
 * functionality without violating CSP. Same pattern used in
 * cycle-41/52/53 for the cart Add-to-cart button family
 * (`mw-add-to-cart-btn`).
 *
 * Four handler families covered:
 *
 *   1. data-mw-gallery="<base64-encoded JSON>" + data-mw-gallery-index
 *      Pictures gallery launcher. Replaces:
 *          onclick="mw.gallery(galleryXXX, N)"
 *      The data-mw-gallery JSON carries the array of {image,
 *      description} items so we don't need a per-instance global
 *      `galleryXXX` variable (also closes a CSP-inline-script hole).
 *
 *   2. data-mw-product-image="<thumbnail-url>" +
 *      data-mw-product-image-target="<id>"
 *      Replaces: onclick="setProductImage('id', 'url', N)"
 *
 *   3. data-mw-cart-add-and-checkout="<item-id>"
 *      Replaces: onclick="mw.cart.add_and_checkout('123')"
 *
 *   4. data-mw-pinmarklet
 *      Replaces: onclick="mw.pinMarklet()"
 *
 * The listener is attached at document level once on DOMContentLoaded
 * (idempotent guard). Click events bubble up; we walk the target's
 * ancestor chain via Element.closest() to find the data-* attribute,
 * which lets a click on an inner <img> still trigger the wrapper's
 * handler.
 */
(function () {
    'use strict';

    if (window.__mwCspSkinHandlersAttached) {
        return;
    }
    window.__mwCspSkinHandlersAttached = true;

    function safeJsonParse(value) {
        if (typeof value !== 'string' || value === '') {
            return null;
        }
        // The data-mw-gallery payload is base64-encoded JSON to dodge
        // HTML-attribute quoting issues (descriptions can contain
        // any character including ", ', and <). atob is universally
        // available in browser; decodeURIComponent + escape handles
        // UTF-8 round-trip safely without TextDecoder.
        try {
            var decoded = decodeURIComponent(escape(atob(value)));
            return JSON.parse(decoded);
        } catch (e) {
            return null;
        }
    }

    document.addEventListener('click', function (event) {
        if (! event.target || typeof event.target.closest !== 'function') {
            return;
        }

        // (1) Pictures gallery launcher.
        var galleryEl = event.target.closest('[data-mw-gallery]');
        if (galleryEl) {
            var galleryData = safeJsonParse(galleryEl.getAttribute('data-mw-gallery'));
            if (Array.isArray(galleryData)) {
                var idx = parseInt(galleryEl.getAttribute('data-mw-gallery-index') || '0', 10) || 0;
                if (window.mw && typeof window.mw.gallery === 'function') {
                    event.preventDefault();
                    window.mw.gallery(galleryData, idx);
                    return;
                }
            }
        }

        // (2) Product image swap on shop-inner Pictures skins.
        var productImageEl = event.target.closest('[data-mw-product-image]');
        if (productImageEl) {
            var src = productImageEl.getAttribute('data-mw-product-image');
            var targetId = productImageEl.getAttribute('data-mw-product-image-target') || '';
            var pidIndex = parseInt(productImageEl.getAttribute('data-mw-product-image-index') || '0', 10) || 0;
            if (typeof window.setProductImage === 'function') {
                event.preventDefault();
                window.setProductImage(targetId, src, pidIndex);
                return;
            }
        }

        // (3) Add-and-checkout button — Content / Page skins.
        var addCheckoutEl = event.target.closest('[data-mw-cart-add-and-checkout]');
        if (addCheckoutEl) {
            var itemId = addCheckoutEl.getAttribute('data-mw-cart-add-and-checkout');
            if (itemId && window.mw && window.mw.cart && typeof window.mw.cart.add_and_checkout === 'function') {
                event.preventDefault();
                window.mw.cart.add_and_checkout(itemId);
                return;
            }
        }

        // (4) Pinterest sharer.
        var pinEl = event.target.closest('[data-mw-pinmarklet]');
        if (pinEl) {
            if (window.mw && typeof window.mw.pinMarklet === 'function') {
                event.preventDefault();
                window.mw.pinMarklet();
                return;
            }
        }
    }, false);
})();
