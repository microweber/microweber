---
name: contract-test-bundle-pin
description: >-
  Pin a load-bearing CSS / blade / config rule against future
  regression with a PHPUnit contract test that asserts both (a) the
  exact selector + property appears in the source file and (b) the
  same rule appears in the built bundle. Use this every time you
  ship a load-bearing CSS rule, blade change, or PHP scaffold —
  ESPECIALLY in `mobile-touch.css`, `live-edit-mobile.css`, or any
  file whose output is bundled / cached. The "cycle-142 lesson"
  taught that source edits without bundle pins regress silently
  whenever someone re-runs npm build with stale node_modules or
  forgets to bust the asset cache.
---

# Contract Test + Built-Bundle Pin

> **Canonical location:** `.claude/skills/contract-test-bundle-pin/SKILL.md`.
> Placed under `.autodev/skills/` here because the harness sandbox
> blocked writes under `.claude/skills/` during cycle-176e.

## Problem

A CSS / blade / PHP rule can pass code review and even pass a
direct browser probe but quietly disappear from production builds
when:

- `npm run build` is skipped because the dev-server cache looks fresh.
- The asset bundle isn't copied to `public/vendor/...` after a build.
- Webpack or Vite chunking moves the rule into a different chunk
  the runtime never loads.
- A merge collapses two `@media` blocks and accidentally drops one.

The runtime LOOKS correct in dev but ships broken. This is the
"cycle-142 lesson" — verified twice in cycles 142, 154, 161.

## Root Cause

Source-only assertions are not enough. The bundle is what runs in
prod. Tests must read the actual compiled file from
`public/vendor/microweber-packages/.../build/` and assert the
rule is present there too.

## Solution Pattern

Every cycle-NNN contract test has THREE parts:

1. **Source anchor check** — `cycle-NNN` and `AI-XXX` strings
   appear in the source file (so the fix is discoverable by
   future grep).
2. **Source regex** — the exact selector + property + value
   matches in the source CSS / blade / PHP.
3. **Built-bundle functional pin** — same rule present in the
   compiled output at the known public path, skipped only if the
   file is genuinely absent (e.g. a hard-mocked CI environment).

### Test template (PHPUnit)

`tests/Feature/Ai###<Description>ContractTest.php`:

```php
<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-NNN / AI-XXX (YYYY-MM-DD) — <problem statement>.
 *
 * <Why this rule exists; what regression it guards against.>
 */
class Ai###<Description>ContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_NNN_anchor(): void
    {
        $src = $this->read('packages/.../source-file.css');
        $this->assertMatchesRegularExpression('/[Cc]ycle-NNN/', $src,
            'source-file.css MUST carry the cycle-NNN anchor.');
        $this->assertStringContainsString('AI-XXX', $src,
            'source-file.css MUST carry the AI-XXX anchor.');
    }

    #[Test]
    public function ai_XXX_<rule_name>(): void
    {
        $src = $this->read('packages/.../source-file.css');
        $this->assertMatchesRegularExpression(
            '/<scoped selector>[\\s\\S]{0,400}<property>:\\s*<value>\\s*!important/m',
            $src,
            '<source-file>.css MUST set <property>:<value> !important '
            . 'on <scoped selector> so <ux outcome>.'
        );
    }

    #[Test]
    public function built_bundle_carries_rule(): void
    {
        $rel = 'public/vendor/microweber-packages/.../build/<bundle>.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built bundle missing.");
        }
        $built = file_get_contents($path);

        $this->assertStringContainsString(
            '<distinctive selector substring>',
            $built,
            'Built bundle MUST contain the AI-XXX rule.'
        );
        $this->assertMatchesRegularExpression(
            '/<selector>[\\s\\S]{0,400}<property>:\\s*<value>\\s*!important/m',
            $built,
            'Built bundle MUST contain <property>:<value> on <selector>.'
        );
    }
}
```

## What to assert

- **Source side:** the `cycle-NNN` + `AI-XXX` text anchors AND the
  full selector + property + value regex with `\s*!important` if
  applicable.
- **Bundle side:** at minimum the distinctive selector substring
  (e.g. `.fi-panel-admin .fi-fo-rich-editor-tool`). When the
  rule is critical, also assert the property:value via regex.

## How to find the compiled bundle path

For Filament theme: `public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css`

For frontend-assets: `public/vendor/microweber-packages/frontend-assets/build/<bundle>.css`

For Bootstrap public template (no build step): `public/templates/bootstrap/css/<file>.css` — same file is both source and "build", so the bundle pin reduces to a duplicate source-side check (still keep it; it documents intent).

Verify by checking the `<link rel="stylesheet">` href in the
rendered page; that's the prod path.

## Do NOT

- Skip the bundle assertion because "the source change is enough"
  — cycle-142 proved otherwise.
- Use `$this->markTestSkipped` for the bundle check on every
  environment — `markTestSkipped` should only fire when the file
  is *legitimately absent* (e.g. fresh checkout before first npm
  build), not as a way to silence test failures.
- Forget to run `npm run build` BEFORE running the test — the
  bundle assertion will fail on first commit otherwise.
- Pin every irrelevant detail. Only pin the load-bearing parts:
  selectors that future cycles might restructure, properties
  that competing Filament base rules might override.

## Test naming convention

`Ai<NUMBER><PascalCaseDescription>ContractTest`:

- `Ai228AdminRichEditorToolbar44ContractTest` ✓
- `Ai229Ai230AdminTabsAndCategoryRadiosContractTest` ✓ (batch)
- `Ai227RowActionAnchorsCardLayoutContractTest` ✓ (follow-up)

## Applies To

- Every cycle-NNN that ships a load-bearing CSS rule.
- Any blade template change where the rule must survive future merges.
- Any built / bundled / cached asset (admin CSS, live-edit CSS,
  webpack output, vite output).
- See cycles 167-176 for canonical examples.
