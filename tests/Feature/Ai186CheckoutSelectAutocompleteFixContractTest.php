<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-160 / AI-186 — `/checkout` 500 crash:
 * `Method Filament\Forms\Components\Select::autocomplete does not exist.`
 *
 * agent-test reported on the AI-185 cycle-159 verification: clicking
 * Add to Cart and visiting `/checkout` crashed with a
 * BadMethodCallException at `Modules/Checkout/Livewire/CheckoutWizard
 * .php:210`. Stack trace pinpointed `->autocomplete('country')` chained
 * onto `Select::make('country')`.
 *
 * Filament v5's Select component does NOT expose an `autocomplete()`
 * method (only TextInput does — Select renders a custom Alpine
 * combobox, not a native `<select>`, so the HTML5 autocomplete token
 * has no useful target on it). The cycle-N audit added the call
 * intending a browser-autofill hint; the call worked at the time only
 * because something about the local install masked it (likely a stale
 * package cache).
 *
 * Cycle-160 fix: replace the chained `->autocomplete('country')` with
 * `->extraAttributes(['aria-required' => 'true', 'autocomplete' =>
 * 'country'])` so the Select wrapper still carries the HTML autofill
 * hint AND the existing aria-required attribute. This is a best-
 * effort hint — browsers won't autofill a custom Filament combobox
 * the way they do a native `<select>` — but the page no longer
 * crashes and there is no functional regression.
 */
class Ai186CheckoutSelectAutocompleteFixContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_ai_186_anchor(): void
    {
        $src = $this->read('Modules/Checkout/Livewire/CheckoutWizard.php');
        $this->assertStringContainsString('AI-186', $src,
            'CheckoutWizard.php MUST carry the AI-186 anchor inline.');
        $this->assertStringContainsString('cycle-160', $src,
            'CheckoutWizard.php MUST carry the cycle-160 anchor inline.');
    }

    #[Test]
    public function select_country_does_not_chain_autocomplete_method(): void
    {
        $src = $this->read('Modules/Checkout/Livewire/CheckoutWizard.php');

        // The crashing pattern was:
        //   Select::make('country')->...->autocomplete('country')
        // After cycle-160 the autocomplete() chain MUST be gone for the
        // Select::make('country') block. We slice the source from
        // Select::make('country') up to the next sibling component
        // (Grid::make follows immediately after the country Select)
        // so we don't pick up the city TextInput's valid autocomplete().
        $pos = strpos($src, "Select::make('country')");
        $this->assertNotFalse($pos, "Select::make('country') must be present in CheckoutWizard.php");

        $endPos = strpos($src, 'Grid::make(2)', $pos);
        $this->assertNotFalse($endPos, "Grid::make(2) must follow Select::make('country').");

        $window = substr($src, $pos, $endPos - $pos);
        $this->assertDoesNotMatchRegularExpression(
            '/->autocomplete\(/',
            $window,
            "Select::make('country') chain (between Select::make('country') "
            . "and the following Grid::make(2)) MUST NOT contain "
            . "`->autocomplete()` — Filament v5 Select has no such method "
            . "and the call crashed /checkout with BadMethodCallException."
        );
    }

    #[Test]
    public function autocomplete_hint_preserved_via_extra_attributes(): void
    {
        $src = $this->read('Modules/Checkout/Livewire/CheckoutWizard.php');

        // The autofill hint should be preserved via extraAttributes so
        // we don't lose the original cycle-N audit intent.
        $pos = strpos($src, "Select::make('country')");
        $window = substr($src, $pos, 800);
        $this->assertMatchesRegularExpression(
            '/extraAttributes\(\s*\[[\s\S]{0,400}[\'"]autocomplete[\'"]\s*=>\s*[\'"]country[\'"]/m',
            $window,
            "Select::make('country') MUST preserve the autofill hint via "
            . "extraAttributes(['autocomplete' => 'country']) so the "
            . "wrapper still tells browsers this is a country field."
        );
    }

    #[Test]
    public function aria_required_still_present_on_country_select(): void
    {
        $src = $this->read('Modules/Checkout/Livewire/CheckoutWizard.php');

        // The pre-existing aria-required: true must still be present on
        // the country Select — it's a separate audit fix that the
        // cycle-160 patch must not regress.
        $pos = strpos($src, "Select::make('country')");
        $window = substr($src, $pos, 800);
        $this->assertMatchesRegularExpression(
            '/extraAttributes\(\s*\[[\s\S]{0,400}[\'"]aria-required[\'"]\s*=>\s*[\'"]true[\'"]/m',
            $window,
            "Select::make('country') MUST keep aria-required => true so "
            . "the screen-reader audit fix from the cycle-N pass is not "
            . "regressed."
        );
    }

    #[Test]
    public function text_input_autocomplete_calls_left_intact(): void
    {
        $src = $this->read('Modules/Checkout/Livewire/CheckoutWizard.php');

        // TextInput::autocomplete() IS a real Filament method. The
        // cycle-160 patch must NOT touch the TextInput chains —
        // first_name / last_name / email / phone / city / state /
        // postal_code / address all chain ->autocomplete() and they
        // are correct.
        $expected = ['given-name', 'family-name', 'email', 'tel',
                     'address-level2', 'address-level1', 'postal-code',
                     'street-address'];
        foreach ($expected as $token) {
            $this->assertMatchesRegularExpression(
                '/->autocomplete\([\'"]' . preg_quote($token, '/') . '[\'"]\)/',
                $src,
                "CheckoutWizard.php MUST keep ->autocomplete('{$token}') "
                . "on its TextInput chain — the cycle-160 fix targets "
                . "only the Select::make('country') call, not the "
                . "TextInput autocomplete chains which are valid."
            );
        }
    }
}
