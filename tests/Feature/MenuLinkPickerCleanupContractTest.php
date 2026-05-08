<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-85 / AI-72 / TICKET-AC — menu link picker form cleanup
 * regression coverage.
 *
 * Pins the three deliverables on
 * `Modules/Menu/Livewire/Admin/MenusList.php::menuItemEditFormArray()`:
 *
 *   1. DEBOUNCE — title TextInput uses ->live(debounce: 500); the
 *      mw_link_picker uses ->live(debounce: 400). Both stop the
 *      per-keystroke Livewire roundtrip storm.
 *   2. DEAD FIELDS — three commented-out form blocks are gone:
 *      display_title TextInput, use_custom_title Checkbox, the
 *      legacy Select::make('url_target') with 4 options. The
 *      only `url_target` field that remains is the live Toggle
 *      below.
 *   3. A11Y — title + url_target Toggle each carry aria-describedby
 *      tying the helperText to the input so SR users hear the help
 *      string when they focus the field.
 *
 * Style after the cycle-52..84 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class MenuLinkPickerCleanupContractTest extends TestCase
{
    private string $menusListSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->menusListSrc = file_get_contents(base_path(
            'Modules/Menu/Livewire/Admin/MenusList.php'
        ));
    }

    #[Test]
    public function title_text_input_uses_live_debounce_500ms(): void
    {
        // The pre-85 shape was a bare ->live() on title — every
        // keystroke fired a Livewire roundtrip. Pin the new
        // ->live(debounce: 500) shape AND that the bare ->live()
        // on the title field is gone.
        $this->assertMatchesRegularExpression(
            "/TextInput::make\\('title'\\)[\\s\\S]*?->live\\(debounce:\\s*500\\)/s",
            $this->menusListSrc,
            'MenusList: title TextInput must use ->live(debounce: 500)'
        );
        // Negative: bare ->live() (no args) immediately followed by
        // ->reactive() under the title field is gone.
        $this->assertDoesNotMatchRegularExpression(
            "/TextInput::make\\('title'\\)[\\s\\S]*?->required\\(\\)\\s*\\n\\s*->live\\(\\)\\s*\\n\\s*->reactive\\(\\)/s",
            $this->menusListSrc,
            'MenusList: title TextInput must NOT have bare ->live() (no debounce arg)'
        );
    }

    #[Test]
    public function mw_link_picker_uses_live_debounce_400ms(): void
    {
        // The link picker's afterStateUpdated callback persists
        // structural fields + clears half-typed URLs via the
        // protocol allow-list. Without debouncing, intermediate
        // typing states (`https`, `https:`, `https:/`) all fail the
        // regex and clear the field — UX is broken.
        $this->assertMatchesRegularExpression(
            "/MwLinkPicker::make\\('mw_link_picker'\\)[\\s\\S]*?->live\\(debounce:\\s*400\\)/s",
            $this->menusListSrc,
            'MenusList: mw_link_picker must use ->live(debounce: 400) so the protocol allow-list does not clear half-typed URLs'
        );
    }

    #[Test]
    public function dead_form_fields_are_removed(): void
    {
        // Three commented-out blocks deleted — pin via plain
        // substring negatives. Strip PHP // line comments AND
        // /* ... */ blocks first so the audit-trail doc-comment
        // documenting the removal doesn't trigger a false positive.
        $stripped = preg_replace('!//.*$!m', '', $this->menusListSrc);
        $stripped = preg_replace('!/\*[\s\S]*?\*/!', '', $stripped);

        $this->assertStringNotContainsString(
            "TextInput::make('display_title')",
            $stripped,
            'MenusList: dead TextInput::make(\'display_title\') must be removed'
        );
        $this->assertStringNotContainsString(
            "Checkbox::make('use_custom_title')",
            $stripped,
            'MenusList: dead Checkbox::make(\'use_custom_title\') must be removed'
        );
        // The legacy Select::make('url_target') with 4 options.
        // The Toggle('url_target') is the canonical field.
        $this->assertStringNotContainsString(
            "Select::make('url_target')",
            $stripped,
            'MenusList: dead Select::make(\'url_target\') must be removed (Toggle is canonical)'
        );
        // Toggle('url_target') must remain.
        $this->assertStringContainsString(
            "Toggle::make('url_target')",
            $this->menusListSrc,
            'MenusList: Toggle::make(\'url_target\') must remain (canonical)'
        );

        // Also pin the explicit comment block that documents the
        // dead-fields removal so future reviewers see the rationale.
        $this->assertStringContainsString(
            'AI-72 / TICKET-AC (cycle-85 2026-05-09): dead-fields',
            $this->menusListSrc,
            'MenusList: dead-fields removal must be documented inline'
        );
    }

    #[Test]
    public function title_input_carries_aria_describedby(): void
    {
        // a11y — aria-describedby on the title input ties the
        // helperText to the input so SR users hear the help string
        // when they focus the field.
        $this->assertMatchesRegularExpression(
            "/TextInput::make\\('title'\\)[\\s\\S]*?'aria-describedby'\\s*=>\\s*'menu-item-title-help'/s",
            $this->menusListSrc,
            'MenusList: title TextInput must carry aria-describedby="menu-item-title-help"'
        );
        // Same on the url_target Toggle.
        $this->assertMatchesRegularExpression(
            "/Toggle::make\\('url_target'\\)[\\s\\S]*?'aria-describedby'\\s*=>\\s*'menu-item-target-help'/s",
            $this->menusListSrc,
            'MenusList: url_target Toggle must carry aria-describedby="menu-item-target-help"'
        );
    }

    #[Test]
    public function helper_text_explanations_remain_for_a11y_pairing(): void
    {
        // aria-describedby is only useful if the helperText still
        // exists — pin both so a future "let's drop the helperText"
        // refactor doesn't break the AT pairing.
        $this->assertStringContainsString(
            "->helperText('Set the title of the menu item.')",
            $this->menusListSrc,
            'MenusList: title helperText must remain (aria-describedby pairs with it)'
        );
        $this->assertStringContainsString(
            "->helperText('Enable to open the link in a new window.')",
            $this->menusListSrc,
            'MenusList: url_target helperText must remain'
        );
    }
}
