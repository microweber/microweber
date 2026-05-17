<div class="logo-module">
    {{-- task-2026-05-17-e2e29a / AI-805 -- collapsed dead conditional.
         Pre-fix had a Blade if/else where BOTH arms emitted identical
         href={{ site_url() }} (both arms returned the same value, so
         the condition was dead code). Designer dispatch offered Path A
         (collapse, no behaviour change -- shipped at task-e2e29a) vs
         Path B (formalise live-edit-disabled-navigation -- the AI-805a
         follow-up).

         task-2026-05-17-af2b73 / AI-805a -- Path B shipped per
         designer's AI-805 ACK authorization. Recommended shape:
         is_live_edit() ? 'javascript:void(0)' : site_url(). Tooltip
         "Use the menu to navigate" + aria-disabled="true" added so AT
         users understand why the link doesn't navigate.

         Product-call validation: designer's own audit work hit the
         data-loss bug "happened to me twice today" -- clicking the
         logo mid-edit navigated away from the live-edit session and
         lost unsaved work. Path B prevents the lossage at the entry
         point. javascript:void(0) (not '#') chosen because '#' jumps
         to top of page if not handled; void(0) is explicit "do
         nothing" semantics.

         Public mode unchanged: href={{ site_url() }} as it always
         was. The single-href contract preserved AI-805 (no dead
         conditional, no Blade @if/@else/@endif overhead) -- the only
         conditional now is the runtime is_live_edit() ternary which
         is a behaviour gate, not a dead branch. --}}
    <a href="{{ is_live_edit() ? 'javascript:void(0)' : site_url() }}" class="logo-link"@if(is_live_edit()) title="Use the menu to navigate" aria-disabled="true"@endif>
        @if(isset($logoimage) && !empty($logoimage))
            {{-- task-2026-05-17-488fa3 / AI-804 -- WCAG 2.1 SC 1.1.1 fix.
                 Pre-fix alt="{{ isset($text) ? $text : '' }}" rendered
                 empty alt when no module text override was set --
                 screen-reader users heard nothing on the brand mark.
                 Fallback precedence: user-set $text -> configured site
                 website_title -> literal 'Home' safety net (never empty).
                 Designer dispatch suggested option_get(); Microweber's
                 canonical helper is get_option(key, group) -- matches
                 the existing pattern at src/MicroweberPackages/App
                 /resources/views/email/simple.blade.php:348. --}}
            <img src="{{ $logoimage }}" alt="{{ isset($text) && $text !== '' ? $text : (get_option('website_title', 'website') ?: 'Home') }}" style="max-width: {{ isset($size) ? $size . 'px' : '200px' }};"/>
        @elseif(isset($text) && !empty($text))
            <span class="logo-text" style="color: {{ isset($text_color) ? $text_color : 'inherit' }}; font-family: {{ isset($font_family) ? $font_family : 'inherit' }}; font-size: {{ isset($font_size) ? $font_size : '30' }}px;">
                {{ $text }}
            </span>
        @else
            <span class="logo-text">
               {!! lnotif('Click to add logo') !!}
            </span>
        @endif
    </a>
</div>

<style>
    /*
     * task-2026-05-17-fa5dc3 / AI-803 [P1 HIGHEST] — logo invisible on
     * frontend. Pre-fix `.logo-module { min-width: 0; overflow: hidden }`
     * was declared UNCONDITIONALLY despite a docblock saying it was
     * meant for the narrow-viewport (≤390px) hamburger-row scenario.
     * Effect: at every viewport (including desktop) the parent flex
     * column collapsed to 0×0, so the SVG logo (300×82 natural) loaded
     * but rendered invisible. Brand collapse on every install.
     *
     * Stage-3 wrong-surface-mount: fix designed for one viewport
     * mounted on all viewports.
     *
     * Fix: gate the narrow-viewport rules inside `@media (max-width: 575px)`
     * (Bootstrap 5 `sm` breakpoint — first breakpoint above the 390px
     * iPhone class). At ≥576px the parent stays at its natural width
     * and the logo renders visible.
     *
     * `.logo-module img { max-width: 100%; height: auto }` stays
     * OUTSIDE the media query — that's responsive-image safety,
     * applies at every viewport.
     *
     * Sibling tickets per designer dispatch (same template, NOT
     * shipped in this slice — separate dispatches AI-804 + AI-805):
     *   - AI-804 (Medium): logo alt="" empty fallback - WCAG 1.1.1
     *   - AI-805 (Low):    dead is_live_edit() blade branch (both
     *                      arms emit identical href)
     */
    .logo-module {
        /*text-align: center;*/
        margin: 20px 0;
    }
    /*
     * task-2026-05-17-5be57f / AI-803 CHANGE v2 — Slice B per
     * designer's deep Tier-3 verify (first CHANGE absorption at
     * task-2026-05-17-5b0a92 / AI-803 CHANGE fixed only one of two
     * layers; this v2 closes the remaining two). Lineage: task-fa5dc3
     * (AI-803 v0 @media gate) → task-5b0a92 (AI-803 CHANGE v1 .logo-link
     * inline-block) → task-5be57f (this AI-803 CHANGE v2 Slice B).
     * Same Stage-2 sub-variant as AI-848 — `CSS-rules-mutual-dependency`
     * shrink-to-fit cycle — just at a different DOM level.
     *
     * Two reasons AI-803 v1 still rendered 0×0 at runtime:
     *
     *  (1) Specificity loss. The active template's skin layer
     *      ships
     *          .header-background.mw-menu-skin-com .mw-big-header-logo a
     *              { display: flex; align-items: center; ... }
     *      at specificity 0,3,1 which beats the AI-803 CHANGE
     *      rule `.logo-module .logo-link { display: inline-block }`
     *      at specificity 0,2,0. The new rule landed in the
     *      cascade but didn't fire — `.logo-link` computed display
     *      stayed `flex`.
     *
     *  (2) Even if (1) resolved, the shrink-to-fit cycle just
     *      moved up one DOM level. `.logo-module` itself is inside
     *      Bootstrap `col-xl-4 w-auto` (column shrinks to content);
     *      `.logo-module` is `display: block, width: 0px`. Same
     *      chicken-and-egg layout cycle as AI-848 just one level
     *      higher.
     *
     * Slice B fix (mirrors AI-848 — explicit dimensions on img
     * break the cycle independently of any parent's computed
     * width):
     *
     *   (a) `.logo-module { display: inline-block !important;
     *                       min-width: 160px; }`
     *       - inline-block → fits content (not block-width-of-0)
     *       - min-width: 160px → defends against col-xl-4 w-auto
     *         column-collapse pattern (reasonable floor for any
     *         brand mark)
     *       - !important → beats any future template-side override
     *
     *   (b) `.logo-module .logo-link { display: inline-block
     *                                  !important; }`
     *       - !important defeats the active-template skin's
     *         `.header-background.mw-menu-skin-com .mw-big-header-logo a
     *           { display: flex }` 0,3,1 selector
     *
     *   (c) `.logo-module img { width: auto; height: 60px;
     *                          max-width: 100%; }`
     *       - explicit height: 60px → img's intrinsic width
     *         resolves from natural ratio (300×82 SVG → 219px
     *         wide at h=60)
     *       - max-width: 100% → still bounded by .logo-module
     *         min-width floor for narrow viewports
     *
     * The cycle is broken at the IMG level — even if `.logo-module`
     * or `.logo-link` continue to be defeated by future template
     * layers, the img's `height: 60px` always wins (no specificity
     * competitor on img height in this module's scope), and the
     * width resolves from the SVG's intrinsic ratio.
     *
     * Stage-2 sub-variant 4 (`CSS-rules-mutual-dependency` cycle)
     * 3rd-instance recurrence: AI-803 v1 + AI-848 + AI-803 v2
     * today. Designer flagged "explicit-dimensions-on-img" as the
     * canonical fix-shape for any logo/avatar/icon-shrink-to-fit-
     * collapse going forward.
     */
    .logo-module {
        display: inline-block !important;
        min-width: 160px;
    }
    .logo-module .logo-link {
        display: inline-block !important;
    }
    .logo-module img {
        width: auto;
        height: 60px;
        max-width: 100%;
    }
    .logo-text {
        display: inline-block;
        margin-top: 10px;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    @media (max-width: 575px) {
        /*
         * Narrow viewport: the logo shares its header row with the
         * hamburger icon. Without `min-width: 0` the logo's intrinsic
         * text width forces the column to stay at full size and pushes
         * the hamburger off-screen. min-width:0 + overflow:hidden lets
         * the column shrink; the inner ellipsis on .logo-link / .logo
         * -text gracefully truncates very long brand names instead of
         * breaking the layout. Originally landed as cross-viewport
         * rule (AI-803 root cause); scoped to ≤575px here.
         */
        .logo-module {
            min-width: 0;
            overflow: hidden;
        }
        .logo-module .logo-link {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
        }
    }
</style>
