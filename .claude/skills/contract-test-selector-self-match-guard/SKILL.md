---
name: contract-test-selector-self-match-guard
description: >-
  Use this whenever writing PHPUnit contract tests that assert the ABSENCE of a
  pattern (assertStringNotContainsString / assertDoesNotMatchRegularExpression)
  against PHP, Blade, CSS, Vue, or JS source files. The test docblock/comment
  prose will legitimately mention the exact token you're testing for absence of —
  causing a false-positive failure. Apply the two-layer defence: (1) pre-strip
  language comments in setUp() before storing the executable source, and (2)
  rephrase docblock prose to word-form instead of literal tokens. Always use
  `strrpos` when locating the last occurrence of a task-marker in a file that
  mentions the marker in both a prior block's comment AND the new code block.
  Assert CSS global-scope via position comparison rather than slice + prose check.
---

# Contract Test Selector Self-Match Guard

## Problem

PHPUnit contract tests that grep source files for the ABSENCE of a legacy pattern
routinely self-match against the test file's own docblock prose, or against comment
blocks in the source file that describe the removed pattern. This causes false-positive
test failures that have nothing to do with the actual code under test.

22+ recurrences across this codebase: AI-518/522/531/691a/697/710/711/712/714/715/
716/717/718/719/692/790/788/809/803/804/805/816/817/818/840/841/865/877/5cbdee.

## Root Cause

- Source files contain CSS/PHP/Blade docblock comments describing what was removed
  (e.g. "removed the legacy `.btn-primary` rule") — the legacy token appears in prose.
- Test file docblocks describe the pattern being tested — the test itself contains the
  very string it's asserting is absent.
- `strpos`/regex finds the FIRST occurrence, which may be in a comment, not in live code.

## Solution Pattern

### Layer 1 (belt — implementer-side): Pre-strip language comments in setUp()

```php
protected function setUp(): void
{
    parent::setUp();
    $raw = (string) file_get_contents(base_path('path/to/file.css'));

    // Strip CSS block comments — keeps byte-positions intact for strpos use
    $this->src = $raw;
    $this->srcStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $raw) ?? $raw;
}
```

**Strip-stack by file type:**
- PHP: `/\*…\*/` + `//[^\n]*` 
- Blade: add `\{\{--[\s\S]*?--\}\}`
- CSS/JS/Vue: `/\*…\*/` only (CSS doesn't have `//`)
- HTML: `~<!--[\s\S]*?-->~` with `~` delimiter (NOT `!` — `!<` parses as modifier)

Apply strips in `setUp()` BEFORE storing the executable source as a class property.

Use `$this->srcStripped` for ALL negative assertions. Use `$this->src` for positive
assertions that must match the raw source (including docblock markers).

### Layer 2 (suspenders — source-side): Rephrase docblock prose to word-form

In the actual source file being tested, rephrase comments to avoid the literal token:
- ❌ `/* removed the <?php print pattern */` → ✅ `/* removed the php-print pattern */`
- ❌ `/* the @else branch */` → ✅ `/* the else-branch */`
- ❌ `/* the */ block comment leaked */` → ✅ `/* the slash-star block comment leaked */`

## Technique: `strrpos` for multi-occurrence markers

When a task marker (e.g. `task-2026-05-22-5cbdee`) appears TWICE in a source file:
- Once in a PRIOR task's comment block that references the new task
- Once in the NEW task's own code block

Use `strrpos` (last occurrence), NOT `strpos` (first occurrence):

```php
// WRONG — finds the marker in a prior task's comment:
$pos = strpos($src, 'task-2026-05-22-5cbdee');

// CORRECT — finds the marker in the new code block:
$pos = strrpos($src, 'task-2026-05-22-5cbdee');
```

Document why `strrpos` is used in the test docblock:
> The marker appears twice: once in the task-77c486 comment block (which acknowledges
> this task), and once in the new task-5cbdee code block. `strrpos` finds the LAST
> occurrence — the new code block.

## Technique: CSS global-scope via position comparison

To assert a CSS rule is at GLOBAL scope (not inside any `@media` block):

```php
// WRONG — slice from task marker + check for @media in slice:
// Fails when docblock says "no @media guard" and slice starts mid-comment.

// CORRECT — position comparison after comment-strip:
$srcStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $src);
$rulePos  = strrpos($srcStripped, '.my-selector');
$mediaPos = strrpos($srcStripped, '@media');

// Rule must appear AFTER all @media blocks in the stripped source:
$this->assertGreaterThan(
    $mediaPos,
    $rulePos,
    '.my-selector must be at global scope, after all @media blocks.'
);
```

## Technique: Count-comparison for presence/absence of multiple instances

When a pattern should appear exactly N times in live code (excluding prose):

```php
$stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->src);
$count = preg_match_all('/pattern_to_count/', $stripped);
$this->assertSame(1, $count, 'Expect exactly 1 live occurrence, not N from comments.');
```

This is cleaner than slice + string-assert when the pattern has nested parens or
complex structure.

## Code Example

```php
protected function setUp(): void
{
    parent::setUp();
    $raw = (string) file_get_contents(
        base_path('Templates/Bootstrap/resources/assets/css/public-touch.css')
    );
    $this->src = $raw;
    $this->srcStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $raw) ?? $raw;
}

#[Test]
public function legacy_rule_is_absent(): void
{
    // Use stripped source for negative assertions — comments legitimately
    // describe removed patterns and would false-fail on raw source.
    $this->assertDoesNotMatchRegularExpression(
        '/\.old-class\s*\{[^}]*color:\s*#f4a261/',
        $this->srcStripped,
        'Legacy salmon colour must be removed from .old-class'
    );
}

#[Test]
public function new_rule_at_global_scope(): void
{
    // Position comparison: new rule must appear after all @media blocks.
    $rulePos  = strrpos($this->srcStripped, 'a:not(.btn)');
    $mediaPos = strrpos($this->srcStripped, '@media');
    $this->assertNotFalse($rulePos, 'Rule selector must be present.');
    $this->assertGreaterThan($mediaPos, $rulePos, 'Rule must be at global scope.');
}
```

## Technique: Multi-selector trailing comma (AI-766)

When a CSS rule uses a **comma-separated multi-selector**, the first selector ends with
`,` not `{`. A regex that expects `{` directly after the first selector will NEVER match:

```css
/* This is valid CSS — comma-separated multi-selector: */
body.fi-panel-admin .fi-page > .fi-form,
body.fi-panel-admin .fi-page-content > .fi-form {
    padding-bottom: 72px;
}
```

**Wrong regex (expects `{` after first selector — never matches):**
```php
'~body\.fi-panel-admin\s+\.fi-page\s*>\s*\.fi-form\s*\{[^}]*padding-bottom:\s*72px~s'
```

**Correct regex (trailing comma as discriminator, then `[\s\S]*?` to span to the value):**
```php
'~body\.fi-panel-admin\s+\.fi-page\s*>\s*\.fi-form\s*,[\s\S]*?padding-bottom:\s*72px~s'
```

**When to apply:** Any time a CSS selector appears in a comma-separated multi-selector
list and you're asserting a property value. Use `,` or `[,{]` after the first selector
in the regex instead of `\s*\{`.

**Additional trap**: If the first selector in the multi-selector (`A,\nB { }`) also
appears as a PREFIX in ANOTHER selector (`A > .child { ... }`), `strrpos` may find the
wrong block. Use the trailing-comma version to disambiguate — it will only match the
actual multi-selector block, not the prefix-only usage.

## Do NOT

- Do NOT perform `assertDoesNotMatch` on raw source without comment-stripping.
- Do NOT use `strpos` when a marker appears in both a prior block comment AND new code.
- Do NOT check global-scope by examining a slice starting at a task marker — the
  marker may be inside a docblock that itself mentions `@media`.
- Do NOT use `preg_replace('!<!--[\s\S]*?-->!', ...)` — `!<` parses as delimiter+flag.
  Use `~` or `#` delimiter.
- Do NOT write a CSS selector regex expecting `\{` directly after the selector when the
  selector might be the first element of a comma-separated multi-selector list.

## Applies To

- All PHPUnit contract tests under `tests/Feature/` that read source files
- CSS contract tests verifying selector scope / presence / absence
- Blade/PHP tests verifying removed legacy patterns
- Any test using: `assertStringNotContainsString`, `assertDoesNotMatchRegularExpression`,
  `assertMatchesRegularExpression` where the source is a file containing its own comments
