<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-0e6cfa / AI-796 — /cart empty-state cleanup.
 * Jira: https://microweber.atlassian.net/browse/AI-796
 *
 * Lineage:
 *   - AI-204 / cycle-163 (2026-05-10) — original /cart standalone page
 *   - AI-737 — brand-blue #0d6efd standardisation across surfaces
 *   - AI-759 — competing-CTA anti-pattern (4th cross-surface instance)
 *   - AI-731 — empty-state CTA chrome (.mw-table-empty-cta) precedent
 *
 * Pre-fix shape: `/cart` on an empty cart rendered TWO CTAs
 * simultaneously — the Livewire CartItems @else-branch's "Continue
 * Shopping" link AND the hardcoded "Proceed to Checkout" button in
 * page.blade.php (which was rendered unconditionally outside the
 * Livewire scope, so it ALWAYS appeared even with zero items). The
 * checkout button was also salmon-orange (Big2's .btn-primary skin)
 * instead of brand-blue #0d6efd. The empty-state copy ("You have no
 * items in your cart.") was a flat sentence with no heading hierarchy.
 *
 * Fix shape — 4 slices in one ship:
 *   - Slice A: conditional-render the "Proceed to Checkout" CTA in
 *     page.blade.php only when `app()->cart_manager->get()` is non-empty.
 *   - Slice B: brand-blue #0d6efd override on .mw-cart-standalone-checkout-cta
 *     + new .mw-cart-empty-cta class for the Livewire empty-state CTA
 *     (matches .mw-table-empty-cta colour shape from microweber-filament-theme.css).
 *   - Slice C: copy pass in Livewire cart-items.blade.php @else branch:
 *     "You have no items in your cart." → heading "Your cart is empty" +
 *     body "Browse our products and add items." + ONE CTA "Continue shopping".
 *   - Slice D: layout extends active template (satisfied since cycle-163;
 *     this slice's contract test re-pins it as a regression guard).
 *
 * One CTA per empty state — the project-wide rule emerging from the
 * AI-759 cross-surface pattern (Live-edit Create + Orders + Comments +
 * /cart all hit the same competing-CTA anti-pattern; this ship is the
 * 4th instance fixed).
 */
class Cart0e6cfaAI796EmptyStateContractTest extends TestCase
{
    private string $page;
    private string $livewire;

    protected function setUp(): void
    {
        parent::setUp();
        $this->page = (string) file_get_contents(base_path(
            'Modules/Cart/resources/views/page.blade.php'
        ));
        $this->livewire = (string) file_get_contents(base_path(
            'Modules/Checkout/resources/views/livewire/cart-items.blade.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Slice A: conditional-render the Proceed-to-Checkout CTA
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function slice_a_proceed_to_checkout_cta_gated_on_cart_having_items(): void
    {
        // The bottom "Proceed to Checkout" anchor must be wrapped in
        // an @if that checks a cart-items-non-empty flag.
        $this->assertStringContainsString(
            '$mwCartCartItems = function_exists(\'mw\') ? (app()->cart_manager->get() ?? []) : [];',
            $this->page,
            'page.blade.php must resolve $mwCartCartItems from cart_manager (coerce null→[]) so the empty-check is reliable.'
        );
        $this->assertStringContainsString(
            '$mwCartHasItems = !empty($mwCartCartItems);',
            $this->page,
            'page.blade.php must derive $mwCartHasItems boolean from the resolved cart items.'
        );
        $this->assertMatchesRegularExpression(
            '/@if\(\$mwCartHasItems\)\s*\n.*?Proceed to Checkout.*?\n.*?@endif/s',
            $this->page,
            'The "Proceed to Checkout" anchor must be wrapped in @if($mwCartHasItems) ... @endif.'
        );
    }

    #[Test]
    public function slice_a_no_unconditional_proceed_to_checkout_render(): void
    {
        // Negative regression guard: the legacy unconditional-render
        // wrapper div must be gone.
        // Pre-strip Blade {{-- ... --}} comments so the docblock's
        // historical "rendered unconditionally" prose doesn't false-fail.
        $stripped = preg_replace('!{{--.*?--}}!s', '', $this->page);

        // The wrapper class .mw-cart-standalone-checkout-cta-wrap should
        // exist ONLY inside the @if($mwCartHasItems) branch — never
        // appear before it as an unconditional sibling.
        $ifStart = strpos($stripped, '@if($mwCartHasItems)');
        $this->assertNotFalse($ifStart, 'page.blade.php must contain the @if($mwCartHasItems) gate.');

        $beforeIf = substr($stripped, 0, $ifStart);
        $this->assertStringNotContainsString(
            'Proceed to Checkout',
            $beforeIf,
            'The "Proceed to Checkout" anchor must NOT appear before the @if($mwCartHasItems) gate.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Slice B: brand-blue #0d6efd CTAs
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function slice_b_checkout_cta_overridden_to_brand_blue(): void
    {
        $this->assertMatchesRegularExpression(
            '/#mw-cart-standalone-page\s+\.mw-cart-standalone-checkout-cta\s*\{[^}]*background-color:\s*#0d6efd\s*!important/',
            $this->page,
            '.mw-cart-standalone-checkout-cta must declare `background-color: #0d6efd !important` to override Big2 .btn-primary salmon orange.'
        );
        $this->assertMatchesRegularExpression(
            '/#mw-cart-standalone-page\s+\.mw-cart-standalone-checkout-cta:hover[^{]*\{[^}]*background-color:\s*#0b5ed7/',
            $this->page,
            '.mw-cart-standalone-checkout-cta:hover must darken to #0b5ed7 (same hover token as .mw-table-empty-cta).'
        );
    }

    #[Test]
    public function slice_b_empty_cta_defined_at_brand_blue(): void
    {
        $this->assertMatchesRegularExpression(
            '/#mw-cart-standalone-page\s+\.mw-cart-empty-cta\s*\{[^}]*background-color:\s*#0d6efd/',
            $this->page,
            '.mw-cart-empty-cta must declare `background-color: #0d6efd` (brand blue / MwColors::Blue).'
        );
        $this->assertMatchesRegularExpression(
            '/#mw-cart-standalone-page\s+\.mw-cart-empty-cta\s*\{[^}]*min-height:\s*44px/',
            $this->page,
            '.mw-cart-empty-cta must enforce min-height: 44px (WCAG 2.5.5 touch-target floor).'
        );
        $this->assertMatchesRegularExpression(
            '/#mw-cart-standalone-page\s+\.mw-cart-empty-cta:hover[^{]*\{[^}]*background-color:\s*#0b5ed7/',
            $this->page,
            '.mw-cart-empty-cta:hover must darken to #0b5ed7.'
        );
    }

    #[Test]
    public function slice_b_reduced_motion_guard_present(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(prefers-reduced-motion:\s*reduce\)\s*\{[^}]*\.mw-cart-standalone-checkout-cta[\s\S]*?\.mw-cart-empty-cta[\s\S]*?transition:\s*none/',
            $this->page,
            'page.blade.php must include a prefers-reduced-motion guard that collapses transitions on both CTAs.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Slice C: empty-state copy + ONE-CTA shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function slice_c_livewire_empty_state_uses_mw_cart_empty_chrome(): void
    {
        $this->assertStringContainsString(
            'class="mw-cart-empty"',
            $this->livewire,
            'Livewire empty-state must use the .mw-cart-empty wrapper.'
        );
        $this->assertStringContainsString(
            'class="mw-cart-empty__heading"',
            $this->livewire,
            'Livewire empty-state must use the .mw-cart-empty__heading class for the headline.'
        );
        $this->assertStringContainsString(
            'class="mw-cart-empty__body"',
            $this->livewire,
            'Livewire empty-state must use the .mw-cart-empty__body class for the descriptive copy.'
        );
        $this->assertStringContainsString(
            'class="mw-cart-empty__actions"',
            $this->livewire,
            'Livewire empty-state must use the .mw-cart-empty__actions class for the CTA wrapper.'
        );
        $this->assertStringContainsString(
            'class="mw-cart-empty-cta"',
            $this->livewire,
            'Livewire empty-state CTA must use the .mw-cart-empty-cta class (styled brand-blue by page.blade.php).'
        );
    }

    #[Test]
    public function slice_c_copy_pass_landed(): void
    {
        $this->assertStringContainsString(
            'Your cart is empty',
            $this->livewire,
            'Livewire empty-state heading must read "Your cart is empty" (designer spec).'
        );
        $this->assertStringContainsString(
            'Browse our products and add items.',
            $this->livewire,
            'Livewire empty-state body must read "Browse our products and add items." (designer spec).'
        );
        $this->assertStringContainsString(
            'Continue shopping',
            $this->livewire,
            'Livewire empty-state CTA must read "Continue shopping" (lowercase second word per house style).'
        );
    }

    #[Test]
    public function slice_c_legacy_empty_state_strings_removed(): void
    {
        // Pre-strip Blade {{-- ... --}} comments so the docblock's
        // historical prose ("Replaces the prior 'You have no items...' line")
        // doesn't false-fail this absence check.
        $stripped = preg_replace('!{{--.*?--}}!s', '', $this->livewire);

        $this->assertStringNotContainsString(
            'You have no items in your cart',
            $stripped,
            'Legacy empty-state copy "You have no items in your cart" must be gone.'
        );
        $this->assertStringNotContainsString(
            '<x-filament::button',
            $stripped,
            'Legacy <x-filament::button> usage must be gone — Filament components are unreliable on public templates.'
        );
        $this->assertStringNotContainsString(
            'dark:bg-indigo-700',
            $stripped,
            'Legacy Tailwind dark-indigo hack class must be gone — replaced by token-driven .mw-cart-empty-cta.'
        );
    }

    #[Test]
    public function slice_c_one_cta_only_in_empty_state(): void
    {
        // ONE CTA per empty state — the project-wide rule emerging from
        // the AI-759 cross-surface pattern. The Livewire empty-state must
        // emit exactly ONE <a> with the .mw-cart-empty-cta class.
        $count = substr_count($this->livewire, 'class="mw-cart-empty-cta"');
        $this->assertSame(
            1,
            $count,
            'Livewire empty-state must emit exactly ONE .mw-cart-empty-cta anchor (project-wide one-CTA-per-empty-state rule).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Slice D: layout extends active template (regression guard)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function slice_d_page_extends_active_template_master(): void
    {
        $this->assertStringContainsString(
            "@extends(\$masterLayout)",
            $this->page,
            'page.blade.php must @extends($masterLayout) so the public navigation + footer chrome wraps the cart.'
        );
        // task-2026-05-22 / AI-944 superseded AI-796 Slice D: the master
        // layout is still resolved at runtime from the active template,
        // but a `view()->exists()` Bootstrap fallback was added (for
        // gitignored templates like Big2 on fresh clones). The candidate
        // is built as `templates.{$activeTemplate}::layouts.master` and
        // assigned into $masterLayout via the exists()-guarded ternary.
        $this->assertStringContainsString(
            "\$candidateMaster = \"templates.{\$activeTemplate}::layouts.master\";",
            $this->page,
            'page must build the candidate master from the active template name at runtime so the page works under any installed template.'
        );
        $this->assertMatchesRegularExpression(
            '/\\$masterLayout\\s*=\\s*view\\(\\)->exists\\(\\$candidateMaster\\)/',
            $this->page,
            '$masterLayout must resolve from the active template with a Bootstrap fallback (AI-944) so it never breaks on a missing template master.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — markers + lineage
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai796_markers_present_on_both_surfaces(): void
    {
        $this->assertStringContainsString('task-2026-05-17-0e6cfa', $this->page);
        $this->assertStringContainsString('AI-796', $this->page);
        $this->assertStringContainsString('task-2026-05-17-0e6cfa', $this->livewire);
        $this->assertStringContainsString('AI-796', $this->livewire);
    }

    #[Test]
    public function page_docblock_cites_lineage_tickets(): void
    {
        $this->assertStringContainsString(
            'AI-759',
            $this->page,
            'page.blade.php docblock must cite AI-759 (competing-CTA anti-pattern, 4th cross-surface instance).'
        );
        $this->assertStringContainsString(
            'AI-737',
            $this->page,
            'page.blade.php docblock must cite AI-737 (#0d6efd brand-blue standardisation).'
        );
        $this->assertStringContainsString(
            'AI-731',
            $this->page,
            'page.blade.php docblock must cite AI-731 (empty-state CTA chrome precedent).'
        );
    }
}
