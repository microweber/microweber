# Microweber Filament v5 Migration & Cleanup TODO List
**Current date in project context:** March 2026  
**Goal:** Complete migration to Filament 5.x + PHPUnit attributes + modern testing practices + remove deprecated patterns

## 1. Core Filament v3 ? v5 Migration (highest priority)

- [x] 2026-03-06 Fix / Update all `Filament\Resources\Components\Tab` usages  
  ? most common error right now ? search: `use Filament\Resources\Components\Tab;` or `Tab::make(`
- [x] 2026-03-06 Convert **all** remaining `->tabs([...])` ? new `getTabs()` method pattern (replaced with `->schema([...])` in 35 files)
- [x] 2026-03-06 Audit every `ListRecords`, `ManageRecords`, `EditRecord`, `CreateRecord` page
  - Audited 86 record pages total (28 ListRecords, 26 CreateRecord, 26 EditRecord, 6 ManageRecords)
  - Found 1 file with getHeaderWidgets(): ListOrders.php
  - Added getHeaderWidgetsColumns() returning 4 columns for proper widget layout
  - All other pages using ExposesTableToWidgets, getTabs(), getFormActions() are v5 compatible
  - No old Form::schema or Table::columns patterns found
- [x] 2026-03-06 Replace all `Form::schema([...])` → new `form(Form $form)` method with `->schema()` - No files found using old pattern; all 35+ Resources already using `form(Schema $schema): Schema`
- [ ] Replace all `Table::schema([...])` / `->columns()` / `->filters()` ? new `table(Table $table)` pattern
- [ ] Convert custom `RelationManagers` to new relation manager syntax (if still using old style)
- [ ] Audit & update every custom Filament **Page** (`Dashboard.php`, `AiSettingsPage.php`, etc.)
- [ ] Replace `Filament::serving(...)` / `Filament::registerRenderHook(...)` ? new `panel()->renderHook()`
- [ ] Search & replace old icons: `HeroiconS*` ? `HeroiconO*` or Blade Heroicons package
- [ ] Remove any remaining `filament-forms::components.` Blade components ? use native Filament ones

Modules with known heavy Filament usage (prioritize these):

- [ ] Modules/Ai/Filament (AgentChatResource + pages)
- [ ] Modules/Billing/Filament (many resources: Subscription*, Plan*, User*, widgets)
- [ ] Modules/Backup/Filament/BackupResource
- [ ] Modules/AiWizard/Filament/AiWizardResource
- [ ] All `...ModuleSettings.php` files that extend `Page` or use forms heavily

## 2. Testing  Fix deprecations & modernize

- [ ] Replace **all `@test` doc-block annotations** with `#[Test]` attribute  
  ? files already partially migrated: BillingRegressionTest, FrontendCheckoutRegressionTest, AgentChatToolsTest
  ? search regex: `/\*\s+\* @test\s+\*/`
- [ ] Convert remaining `public function test_...()` to `#[Test] public function ...(): void`
- [ ] Replace `/** @test */` in **all** unit/feature tests (Billing, Ai, Cart, Order, etc.)
- [ ] Audit & fix all remaining `->assertSee()`, `->assertDontSee()` ? prefer `assertStringContainsString`
- [ ] Replace `Http::fake([...])` ? new `Http::response()->with...` style where possible (Laravel 11+)
- [ ] Add trait `Tests\CreatesApplication` if missing in some test classes
- [ ] Migrate old `TestCase` ? `Tests\TestCase` everywhere

## 3. Incomplete / suspicious migrations to Filament 5

- [ ] Modules/Shop (if exists)  check if product/category resources are migrated
- [ ] Modules/Order  any admin Filament resources?
- [ ] Modules/Coupons  admin list & edit pages
- [ ] Modules/CustomFields / Attributes  likely still old style
- [ ] Modules/Template  settings / preview pages
- [ ] Modules/User / Admin  user management in Filament

## 4. External / 3rd party libraries that should be extracted ? microweber-packages

External-looking or non-standard packages located inside `Modules/`:

- [ ] Evaluate `Modules/Ai/Services/Drivers/*` (OpenAI, Gemini, Ollama, Replicate, Fal.ai, OpenRouter)  
  ? Plan: extract ? `microweber-packages/ai-drivers` (multi-driver AI abstraction)
- [ ] `Modules/Ai/Tools/*` ? consider `microweber-packages/ai-tools` (tool calling ecosystem)
- [ ] `SupadataTool`, `AmazonScraperService`, `GoogleTrendsService` ? very external ? move out
- [ ] `Rdkit`, `PySCF` (from chemistry libs in code interpreter description)  not used ? remove if dead code
- [ ] Any remaining Livewire v2 ? v3 migration remnants (check `wire:model`, `wire:click`)

## 5. General code health & cleanup

- [ ] Remove / archive very old files: `xdebug_server.php`, `install.cmd`, `Web.config`, `delete_node_modules.sh`
- [ ] Delete obsolete reports: `phpunit-coverage.xml`, `tests-initial-report.txt`, `tests-unit-report.txt`
- [ ] Remove duplicate / confusing configs: multiple `config.php` in modules
- [ ] Audit `bootstrap/cache/` files committed to git ? add to `.gitignore`
- [ ] Run full static analysis (`phpstan`, `rector`, `insights`) after Filament upgrade
- [ ] Replace old `config('microweber.')` ? new module config system if exists
- [ ] Normalize license year in all LICENSE / composer.json files ? 2026
- [ ] Remove or update very outdated docs: `docs-for-ai.php`, `markdown-examples.md`

## 6. Nice to have  after main migration is stable

- [ ] Upgrade to Laravel 12 / 11 if still on 10
- [ ] Introduce module-level Pest test suites (`tests/Unit`, `tests/Feature`)
- [ ] Add GitHub Actions matrix testing (PHP 8.28.4, Laravel 1012)
- [ ] Create module upgrade guide (`docs/filament-migration.md` ? finish it)
- [ ] Add automated Filament -> v5 codemod / rector rules (custom if needed)

## Quick Wins  do these first (13 days)

- [ ] Fix failing test: `ModuleResourceTest` ? `Tab` class not found
- [ ] Convert 2030 most obvious `@test` ? `#[Test]`
- [ ] Replace `Tab::make()` in 23 most used resources (Ai, Billing)

Last updated: 2026-03  
