/*
 * AI-139 / A11Y-06 (cycle-125 2026-05-09): WCAG contrast computation.
 *
 * Plain-JS helpers for the ESE colour-picker contrast badge:
 *
 *   contrastRatio(fg, bg)
 *     Returns the WCAG 2.x contrast ratio (1..21) between two
 *     hex colours. Pass either "#rgb", "#rrggbb", "rgb(...)", or
 *     "rgba(...)" — anything `parseColor` understands.
 *
 *   contrastBadge(fg, bg)
 *     Returns one of "aaa" | "aa" | "fail" — the badge tier the
 *     `.mw-contrast-badge` CSS hooks expect.
 *
 *   hexToRgb(hex)
 *     "#0d6efd" → [13, 110, 253].
 *
 * Used by the ESE colour-picker render to attach a live contrast
 * badge next to each foreground/background pair the user is
 * tweaking.
 */

export function hexToRgb(hex) {
    if (typeof hex !== 'string') return null;
    const m = hex.trim().toLowerCase().match(
        /^#?([a-f\d]{1,2})([a-f\d]{1,2})([a-f\d]{1,2})$/
    );
    if (!m) return null;
    const norm = (v) => (v.length === 1 ? v + v : v);
    return [
        parseInt(norm(m[1]), 16),
        parseInt(norm(m[2]), 16),
        parseInt(norm(m[3]), 16),
    ];
}

export function relativeLuminance([r, g, b]) {
    // sRGB → linear, then weighted sum (WCAG formula).
    const lin = (c) => {
        const s = c / 255;
        return s <= 0.03928 ? s / 12.92 : Math.pow((s + 0.055) / 1.055, 2.4);
    };
    return 0.2126 * lin(r) + 0.7152 * lin(g) + 0.0722 * lin(b);
}

export function contrastRatio(fg, bg) {
    const a = hexToRgb(fg);
    const b = hexToRgb(bg);
    if (!a || !b) return 1; // unparseable → "no contrast"
    const la = relativeLuminance(a);
    const lb = relativeLuminance(b);
    const [hi, lo] = la > lb ? [la, lb] : [lb, la];
    return (hi + 0.05) / (lo + 0.05);
}

/**
 * WCAG 2.1 contrast tiers:
 *   AAA — 7:1 (normal text)
 *   AA  — 4.5:1 (normal text)
 *   FAIL — anything less.
 *
 * For LARGE text the thresholds drop to 4.5/3 — a future
 * `contrastBadgeForLargeText()` can land if needed.
 */
export function contrastBadge(fg, bg) {
    const ratio = contrastRatio(fg, bg);
    if (ratio >= 7) return 'aaa';
    if (ratio >= 4.5) return 'aa';
    return 'fail';
}
