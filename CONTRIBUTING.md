# Contribution Guidelines

This document contains a set of guidelines to help you during the contribution process.
We are happy to welcome all the contributions from anyone willing to **improve/add** new **features** to this project.
Thank you for helping out and remember, **no contribution is too small.**

## Requirements

- PHP 8.3+
- Laravel 11
- Composer 2
- Node.js 18+ (for frontend assets)

## Submitting a Change

Below you will find the process and workflow used to review and merge your changes.

### Step 1: Find existing issues

Take a look at existing issues in [GitHub Issues](https://github.com/microweber/microweber/issues) and [Pull Requests](https://github.com/microweber/microweber/pulls) to avoid duplicate work.

### Step 2: Fork the project

Fork this repository. This will create a local copy on your GitHub profile. Keep a reference to the original project in `upstream` remote.

```sh
git clone https://github.com/<your-username>/microweber
cd microweber
git remote add upstream https://github.com/microweber/microweber
```

If you have already forked the project, update your copy before working:

```sh
git remote update
git checkout master
git rebase upstream/master
```

### Step 3: Create your branch

Create a new branch. Use its name to identify the issue you're addressing.

```sh
git checkout -b my_branch_name
```

### Step 4: Implement, test, and commit

- Make your changes.
- Write or update tests for any code you change (see [Testing Standards](#testing-standards) below).
- Run the test suite to confirm everything passes.
- Commit with a [Conventional Commit](https://www.conventionalcommits.org/) message:

```sh
git add .
git commit -m "feat: add pagination to product list"
```

Common prefixes: `feat:`, `fix:`, `refactor:`, `test:`, `docs:`, `chore:`, `style:`, `perf:`

**NOTE**:

- A Pull Request should have only one logical change to make it simple for review.
- Multiple fixes for the same issue can be grouped into a single Pull Request.

### Step 5: Push your changes

```sh
git push -u origin my_branch_name
```

### Step 6: Pull Request

Navigate to your GitHub repository, then click **New pull request** within the Pull requests tab. Provide a meaningful title and description. Your PR will be reviewed and merged if it complies with project standards; otherwise, feedback will be provided.

---

## Testing Standards

All contributions that touch PHP code must include tests. The canonical runner in this repository is **PHPUnit 11** via `phpunit.xml`, with grouped suite execution available through `./run-tests.sh`.

### Running tests

```sh
# Install dependencies
composer install --dev

# Run the full test suite
composer test

# Run the memory-safe grouped suites (recommended for broad local validation)
./run-tests.sh

# List the grouped suites available to run
./run-tests.sh --list

# Run a single module's tests
php vendor/bin/phpunit Modules/Backup/Tests --no-progress --display-errors

# Run a single test file
php vendor/bin/phpunit Modules/Backup/Tests/Unit/Filament/BackupResourceTest.php --no-progress --display-errors

# Run just the Unit or Feature suite from phpunit.xml
php vendor/bin/phpunit --testsuite=Unit --no-progress --display-errors
php vendor/bin/phpunit --testsuite=Feature --no-progress --display-errors

# Composer shortcuts
composer test           # Full suite via artisan test
composer test-coverage  # Coverage run via phpunit.xml
composer test-dusk      # Browser suite via Laravel Dusk
```

### Writing tests

This repository currently ships PHPUnit-style tests. When writing or updating tests, follow these conventions:

**Use `#[Test]` attributes** — not `@test` docblocks and not `test_` method prefixes:

```php
// CORRECT
use PHPUnit\Framework\Attributes\Test;

#[Test]
public function it_creates_a_user(): void
{
    // ...
}

// WRONG — do not use these patterns
/** @test */
public function it_creates_a_user() { }

public function test_creates_a_user() { }

public function testCreatesAUser() { }
```

**Name methods descriptively with `it_` prefix and `void` return type:**

```php
#[Test]
public function it_returns_404_for_missing_product(): void { }

#[Test]
public function it_validates_required_fields_on_create(): void { }
```

**Extend `Tests\TestCase`** — not `MicroweberPackages\Core\tests\TestCase` or any other base:

```php
use Tests\TestCase;

class MyFeatureTest extends TestCase
{
    // ...
}
```

### Testing Filament resources

Filament admin panel tests require the panel to be registered as the current panel. Use the `InteractsWithFilamentPanel` trait:

```php
<?php

namespace Modules\YourModule\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\YourModule\Filament\Resources\YourResource\Pages\ListRecords;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class YourResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_index_page_loads(): void
    {
        Livewire::test(ListRecords::class)->assertSuccessful();
    }
}
```

For standard CRUD resources, extend `FilamentResourceTestCase` instead:

```php
<?php

namespace Modules\YourModule\Tests\Unit\Filament;

use Tests\Feature\Filament\FilamentResourceTestCase;
use PHPUnit\Framework\Attributes\Test;

class YourResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return \Modules\YourModule\Filament\Resources\YourResource::class;
    }

    #[Test]
    public function it_can_list_records(): void
    {
        $this->assertCanAccessIndex();
    }
}
```

`FilamentResourceTestCase` provides: `assertCanAccessIndex()`, `assertGuestCannotAccess($route)`, `createModel($attributes)`, `actingAsAdmin()`, and `actingAsUser()`.

### Module test directory structure

Each module should organize tests like this:

```
Modules/
  YourModule/
    Tests/
      Pest.php              # Module-level Pest config
      Unit/
        Filament/
          YourResourceTest.php
        YourModelTest.php
      Feature/
        YourFeatureTest.php
```

### Test checklist for pull requests

Before submitting a PR, confirm:

- [ ] All new/changed code has corresponding tests
- [ ] Tests use `#[Test]` attributes (not `@test` or `test_` prefix)
- [ ] Test methods use `it_` prefix and `void` return type
- [ ] Test classes extend `Tests\TestCase`
- [ ] Filament resource tests use `InteractsWithFilamentPanel` or extend `FilamentResourceTestCase`
- [ ] `composer test` or the relevant targeted PHPUnit command passes with zero failures
- [ ] No leftover `var_dump()`, `dd()`, `dump()`, or `ray()` calls

### Further reading

See [docs/testing/module-testing-guide.md](docs/testing/module-testing-guide.md) for a comprehensive guide including module test structure, CI integration, and troubleshooting.

---

## Commit Messages

Use [Conventional Commits](https://www.conventionalcommits.org/) format:

```
feat: add OAuth2 login flow
fix: prevent null dereference in user resolver
refactor: extract validation into standalone module
test: add edge-case coverage for pagination logic
docs: document environment variables in README
chore: upgrade dependencies to latest patch versions
```

- Subject line: imperative mood, 72 characters max, no period.
- One logical change per commit.

---

## Code Style

- Follow the existing conventions in the file you're editing.
- PHP code follows PSR-12.
- Use typed method signatures — avoid `mixed` or `any` where possible.
- No dead code: remove unused variables, imports, and functions.
- No magic values: extract literals to named constants.

---

## CHANGELOG cadence (AI-122 / TICKET-CA, cycle-120 2026-05-09)

The repo carries a `CHANGELOG.md` at the root. Two cadences exist
— pick the one that matches the size of your change:

### 1. Release-tag-driven (canonical)

For changes that ship in a numbered release (`v2.x.y`):

- One section per release tag, in reverse-chronological order.
- Heading format: `## [2.4.0] — 2026-05-09`.
- Subsections (in this order, omit empty ones): `Added`,
  `Changed`, `Fixed`, `Security`, `Deprecated`, `Removed`.
- Bullets reference the JIRA ticket id in square brackets where
  applicable: `[AI-128] Add CSP frame-ancestors header`.
- The release commit bumps the version in `composer.json` +
  `package.json`, edits `CHANGELOG.md`, and tags `v2.4.0`.

### 2. Per-cycle (during active development)

For incremental work between releases (the current
agent-driven cycles):

- Append a single line under the in-progress release section:
  `- cycle-N (AI-NNN / TICKET-XX): one-line summary` with the
  commit SHA in parens at the end.
- When the release is cut, those lines roll up into the
  Added / Changed / Fixed buckets in the release section.

### What does NOT belong in CHANGELOG.md

- Test-only changes (e.g. cycle-X: contract test for AI-NNN).
- Pure refactors with no observable behaviour change.
- Doc-only edits that don't change the public API or runtime.
- `chore(deps)` minor bumps.

If a cycle is doc-only / test-only, mark it `[skip changelog]` in
the commit body and skip the CHANGELOG.md edit.

---

## Need more help?

- [Forking a Repo](https://help.github.com/en/github/getting-started-with-github/fork-a-repo)
- [Cloning a Repo](https://help.github.com/en/desktop/contributing-to-projects/creating-an-issue-or-pull-request)
- [How to create a Pull Request](https://opensource.com/article/19/7/create-pull-request-github)
- [Getting started with Git and GitHub](https://towardsdatascience.com/getting-started-with-git-and-github-6fcd0f2d4ac6)
- [Learn GitHub from Scratch](https://lab.github.com/githubtraining/introduction-to-github)
