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

All contributions that touch PHP code must include tests. The project uses **Pest** (built on PHPUnit) as the test runner. Both Pest closure-style tests and PHPUnit class-style tests are supported.

### Running tests

```sh
# Install dependencies (includes Pest)
composer install --dev

# Run the full test suite
vendor/bin/pest

# Run only Unit + Feature suites
vendor/bin/pest --configuration pest.xml

# Run a single module's tests
vendor/bin/pest Modules/Backup/Tests

# Run a single test file
vendor/bin/pest Modules/Backup/Tests/Unit/Filament/BackupResourceTest.php

# Run tests matching a name pattern
vendor/bin/pest --filter="backup"

# Composer shortcuts
composer test           # Full suite via phpunit.xml
composer test-pest      # Unit + Feature via pest.xml
```

### Writing new tests (Pest style — recommended)

New tests should use Pest closure syntax:

```php
<?php

use Modules\Billing\Models\Subscription;

test('subscription can be created with factory', function () {
    $subscription = Subscription::factory()->make();

    expect($subscription)->toBeInstanceOf(Subscription::class)
        ->and($subscription->status)->toBeIn(['active', 'inactive', 'cancelled']);
});
```

### Existing tests (PHPUnit class style)

Existing PHPUnit class-based tests are fully supported. When writing or updating PHPUnit-style tests, follow these conventions:

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
- [ ] `vendor/bin/pest` passes with zero failures
- [ ] No leftover `var_dump()`, `dd()`, `dump()`, or `ray()` calls

### Further reading

See [docs/testing/module-testing-guide.md](docs/testing/module-testing-guide.md) for a comprehensive guide including Pest expectations, lifecycle hooks, CI integration, and troubleshooting.

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

## Need more help?

- [Forking a Repo](https://help.github.com/en/github/getting-started-with-github/fork-a-repo)
- [Cloning a Repo](https://help.github.com/en/desktop/contributing-to-projects/creating-an-issue-or-pull-request)
- [How to create a Pull Request](https://opensource.com/article/19/7/create-pull-request-github)
- [Getting started with Git and GitHub](https://towardsdatascience.com/getting-started-with-git-and-github-6fcd0f2d4ac6)
- [Learn GitHub from Scratch](https://lab.github.com/githubtraining/introduction-to-github)
