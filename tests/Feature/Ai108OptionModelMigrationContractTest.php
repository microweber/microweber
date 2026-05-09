<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-133 / AI-108 / TICKET-BG — Settings API migration to the Option model.
 *
 * Pins the migration so a refactor cannot silently re-introduce raw
 * DB::table('options') calls in production code. Tests + migrations are
 * out of scope (Browser fixture cleanup intentionally bypasses model
 * events for speed; migration files are historical and must not change
 * after they have been applied to a real database).
 *
 * What this contract test asserts:
 *
 *   1. The Option Eloquent model's $fillable now includes 'option_key' and
 *      'module' so mass assignment via Option::create / ->update works
 *      without silently dropping fields. This is the unblock that made the
 *      whole migration possible.
 *
 *   2. SettingsApiController no longer uses raw DB::table('options') —
 *      every CRUD path goes through Option::query() / Option::create /
 *      $option->update / $option->delete so OptionWasCreated /
 *      OptionWasUpdated / OptionWasDeleted events fire and downstream
 *      listeners (TemplateClearCachedCssListener) run when settings are
 *      changed via /api/module/settings. This fixes a previously-silent
 *      stale-cache bug.
 *
 *   3. AdminTemplateCustomizerPage's "delete all template options" path
 *      now iterates Option models so OptionWasDeleted fires per row.
 *
 *   4. DefaultOptionsInstaller (setDefault, setCommentsEnabled,
 *      setShippingEnabled, setPaymentsEnabled) no longer uses raw
 *      DB::table('options') for option-table writes. The pgsql
 *      sequence-collision workaround is removed since Eloquent
 *      handles lastInsertId correctly across drivers.
 *
 *   5. TemplateInstaller::setDefaultTemplate uses the model.
 *
 *   6. The Option model's two internal read helpers
 *      (queryAllExistingOptionGroups + queryOptionsByGroup) intentionally
 *      retain raw DB::table('options') because they are invoked during
 *      early app boot via OptionRepository — at that stage Eloquent's
 *      connection resolver is not yet ready and self::query() throws
 *      "Call to a member function connection() on null". This bootstrap
 *      dependency is documented inline in the model. Both helpers are
 *      READ-only so the cache-invalidation events that the migration
 *      enables for write paths do not apply here.
 *
 * Style after Sec05SsrfAndStoredXssContractTest / Ai112*ContractTest —
 * source-grep assertions that catch regressions at refactor time without
 * needing app boot or DB seeding.
 */
class Ai108OptionModelMigrationContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    private function assertFileHasNoRawOptionsTableCall(string $rel): void
    {
        $src = $this->read($rel);
        $this->assertDoesNotMatchRegularExpression(
            '/(?<!\*\s)DB::table\(\s*[\'"]options[\'"]\s*\)/',
            $src,
            "{$rel} MUST NOT contain raw DB::table('options') calls — "
            . 'route through MicroweberPackages\\Option\\Models\\Option instead.'
        );
    }

    #[Test]
    public function option_model_fillable_now_includes_option_key_and_module(): void
    {
        $src = $this->read('src/MicroweberPackages/Option/Models/Option.php');

        // Every field that the Settings API + installers want to mass-assign
        // MUST be in $fillable; without these, Option::create([...]) silently
        // drops the value and the migration breaks.
        $required = ['option_key', 'option_group', 'option_value', 'module', 'is_system'];
        foreach ($required as $field) {
            $this->assertMatchesRegularExpression(
                '/\$fillable\s*=\s*\[[^\]]*[\'"]' . preg_quote($field, '/') . '[\'"]/s',
                $src,
                "Option::\$fillable MUST contain '{$field}' so mass "
                . 'assignment from the Settings API + installers does not '
                . 'silently drop the value.'
            );
        }
    }

    #[Test]
    public function settings_api_controller_uses_option_model_instead_of_raw_db_table(): void
    {
        $rel = 'Modules/Settings/Http/Controllers/Api/SettingsApiController.php';
        $this->assertFileHasNoRawOptionsTableCall($rel);

        $src = $this->read($rel);

        $this->assertMatchesRegularExpression(
            '/use\s+MicroweberPackages\\\\Option\\\\Models\\\\Option;/',
            $src,
            'SettingsApiController MUST import the Option model.'
        );

        // Each CRUD verb routes through the model.
        $this->assertStringContainsString(
            'Option::query()',
            $src,
            'SettingsApiController index/show/update/destroy MUST use '
            . 'Option::query() to build read queries.'
        );

        $this->assertStringContainsString(
            'Option::create',
            $src,
            'SettingsApiController store MUST use Option::create for inserts '
            . 'so OptionWasCreated fires + cache listeners run.'
        );

        $this->assertMatchesRegularExpression(
            '/->update\(\s*\[[\s\S]*?[\'"]option_value[\'"]/',
            $src,
            'SettingsApiController update MUST use the Eloquent model '
            . 'instance ->update() so OptionWasUpdated fires.'
        );

        $this->assertMatchesRegularExpression(
            '/\$option->delete\(\)/',
            $src,
            'SettingsApiController destroy MUST use the Eloquent model '
            . 'instance ->delete() so OptionWasDeleted fires.'
        );
    }

    #[Test]
    public function admin_template_customizer_uses_option_model_for_delete_all(): void
    {
        $rel = 'Modules/Settings/Filament/Pages/AdminTemplateCustomizerPage.php';
        $this->assertFileHasNoRawOptionsTableCall($rel);

        $src = $this->read($rel);

        // "Delete all template options" MUST iterate Option models so
        // OptionWasDeleted fires per row.
        $this->assertStringContainsString(
            '\\MicroweberPackages\\Option\\Models\\Option::query()',
            $src,
            'AdminTemplateCustomizerPage MUST use the Option model query '
            . 'when deleting template-scoped options so cache listeners fire.'
        );
        $this->assertMatchesRegularExpression(
            '/->each\(\s*fn\s*\(\s*\$option\s*\)\s*=>\s*\$option->delete\(\)\s*\)/',
            $src,
            'AdminTemplateCustomizerPage MUST call ->delete() on each Option '
            . 'model instance so OptionWasDeleted fires per row.'
        );
    }

    #[Test]
    public function default_options_installer_uses_option_model_for_writes(): void
    {
        $rel = 'src/MicroweberPackages/Install/DefaultOptionsInstaller.php';
        $this->assertFileHasNoRawOptionsTableCall($rel);

        $src = $this->read($rel);

        // Every existence check MUST be Option::query()->...->exists().
        $this->assertGreaterThanOrEqual(
            4,
            preg_match_all(
                '/Option::query\(\)\s*->where\(\s*[\'"]option_key[\'"]/m',
                $src
            ),
            'DefaultOptionsInstaller MUST use Option::query()->where(\'option_key\') '
            . 'for at least 4 existence checks (setDefault, setCommentsEnabled, '
            . 'setShippingEnabled paypal+currency=2 in setPaymentsEnabled).'
        );

        // Every insert MUST go through `new Option(); ...; ->save()`.
        $this->assertGreaterThanOrEqual(
            4,
            preg_match_all('/\(new\s+Option\(\)\)|new\s+Option\(\);\s*\n\s*\$option->/', $src)
                + preg_match_all('/new\s+Option\(\)/', $src),
            'DefaultOptionsInstaller MUST use `new Option()` for at least 4 '
            . 'insert paths so OptionWasCreated fires.'
        );

        // pgsql MAX(id)+1 sequence-collision workaround MUST be removed —
        // Eloquent handles lastInsertId correctly across drivers.
        $this->assertDoesNotMatchRegularExpression(
            '/highestId\s*=\s*DB::table\([\'"]options[\'"]\)\s*->select\(/',
            $src,
            'DefaultOptionsInstaller MUST NOT carry the legacy pgsql '
            . 'MAX(id)+1 workaround for the options table — Eloquent '
            . 'handles lastInsertId correctly across drivers.'
        );
    }

    #[Test]
    public function template_installer_uses_option_model(): void
    {
        $rel = 'src/MicroweberPackages/Install/TemplateInstaller.php';
        $this->assertFileHasNoRawOptionsTableCall($rel);

        $src = $this->read($rel);

        $this->assertMatchesRegularExpression(
            '/Option::query\(\)\s*\n?\s*->where\(\s*[\'"]option_key[\'"]\s*,\s*[\'"]current_template[\'"]\s*\)/m',
            $src,
            'TemplateInstaller::setDefaultTemplate MUST resolve the existing '
            . "current_template option via Option::query()->where(...) "
            . 'rather than raw DB::table.'
        );
    }

    #[Test]
    public function option_model_internal_helpers_keep_raw_db_table_for_boot_safety(): void
    {
        $src = $this->read('src/MicroweberPackages/Option/Models/Option.php');

        // The two internal READ helpers intentionally retain DB::table('options')
        // because they are invoked during early boot via OptionRepository, before
        // Eloquent's connection resolver is ready (self::query() would throw
        // "Call to a member function connection() on null" there).
        // The bootstrap-dependency rationale MUST be documented inline so a
        // future refactor does not silently break boot.
        $this->assertMatchesRegularExpression(
            '/queryAllExistingOptionGroups[\s\S]*?early app boot[\s\S]*?DB::table\(\s*[\'"]options[\'"]\s*\)/m',
            $src,
            'Option::queryAllExistingOptionGroups MUST keep raw DB::table '
            . 'with an inline comment explaining the boot-time constraint.'
        );

        $this->assertMatchesRegularExpression(
            '/queryOptionsByGroup[\s\S]*?early-boot[\s\S]*?DB::table\(\s*[\'"]options[\'"]\s*\)/m',
            $src,
            'Option::queryOptionsByGroup MUST keep raw DB::table with an '
            . 'inline comment referencing the same early-boot constraint.'
        );
    }
}
