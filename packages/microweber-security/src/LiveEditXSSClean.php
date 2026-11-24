<?php

namespace MicroweberPackages\Security;

/**
 * Rich-content XSS filter for the TRUSTED admin Live-Edit / content-save path.
 *
 * The base {@see XSSClean} is deliberately strict: it escapes whole `<style>`
 * blocks and `<iframe>` tags, which is correct for UNTRUSTED input (public form
 * submissions, order data, the global XSS request middleware for non-admins).
 * But the Live-Edit page builder and the AI agent legitimately author rich,
 * designed pages that need an embedded `<style>` block (keyframes, media
 * queries, component CSS) and `<iframe>` embeds (video, maps). Running those
 * through the strict filter neuters the page (the CSS shows up as visible
 * `&lt;style&gt;` text).
 *
 * This subclass is the named entry point for that trusted path: it forces the
 * permissive rich mode on, so callers don't have to remember the option. It is
 * exactly equivalent to `(new XSSClean())->clean($html, ['admin_mode' => true])`.
 *
 * Rich mode keeps every strict protection for the bulk of the markup and adds
 * two narrowly-scoped, content-sanitized allowances:
 *   1. `<style>…</style>` — kept, CSS scrubbed of `expression()`,
 *      `behavior:`/`-moz-binding:`, `javascript:`/`vbscript:` URLs, remote
 *      `@import`, `url(javascript:|data:text/html)`, and `</style>` breakouts.
 *   2. `<iframe>` — kept, `src` restricted to safe schemes
 *      (http/https/protocol-relative/root-relative/{SITE_URL}); `javascript:`,
 *      `data:`, `vbscript:` sources dropped; `srcdoc` and every `on*` handler
 *      stripped.
 */
class LiveEditXSSClean extends XSSClean
{
    protected function isPermissive(array $options): bool
    {
        // Always permissive — this class exists to signal "trusted rich author".
        // An explicit ['admin_mode' => false] can still force strict if needed.
        if (array_key_exists('admin_mode', $options) && $options['admin_mode'] === false) {
            return false;
        }

        return true;
    }
}
