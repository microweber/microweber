<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1097 / task-2026-05-28-2f5a6c Batch 2.
 *
 * Scope (dispatch verbatim): "/admin/customers 12 columns cause
 * horizontal scroll at 1440px (column bloat reduction)."
 *
 * Failure family pinned by this test:
 *
 *  CustomerResource default-rendered 11 columns at desktop which
 *  forces horizontal scroll at 1440px. Six low-signal columns are
 *  re-classified as toggleable-hidden-by-default so power users
 *  retain data access via the column-toggle menu but the default
 *  rendered set is reduced to the five highest-signal columns
 *  (name, phone, email, active, tags).
 *
 *  Toggleable-hidden-by-default (6): id, first_name, last_name,
 *  user.username, currency.name, company.name.
 *
 *  Default-visible (5): name, phone, email, active, tags.
 *
 * The source file carries the task-id marker comment per the
 * per-task source-comment marker convention.
 */
class AdminCustomers1097ColumnBloatContractTest extends TestCase
{
    private string $customerResource;

    private string $customerResourceStripped;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->customerResource = (string) file_get_contents(
            self::projectRoot()
            . '/Modules/Customer/Filament/CustomerResource.php'
        );

        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->customerResource);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);
        $this->customerResourceStripped = $stripped;
    }

    /**
     * @dataProvider toggleableHiddenColumnsProvider
     */
    public function test_low_signal_columns_are_toggleable_hidden_by_default(string $columnName): void
    {
        $pattern = '/TextColumn::make\(\'' . preg_quote($columnName, '/') . '\'\)(?:(?!::make\()[\s\S])*?->toggleable\(isToggledHiddenByDefault:\s*true\)/';

        $this->assertMatchesRegularExpression(
            $pattern,
            $this->customerResourceStripped,
            'AI-1097: column "' . $columnName . '" must carry ->toggleable(isToggledHiddenByDefault: true) so it does not render by default and avoids the horizontal-scroll bloat'
        );
    }

    public static function toggleableHiddenColumnsProvider(): array
    {
        return [
            'id' => ['id'],
            'first_name' => ['first_name'],
            'last_name' => ['last_name'],
            'user.username' => ['user.username'],
            'currency.name' => ['currency.name'],
            'company.name' => ['company.name'],
        ];
    }

    /**
     * @dataProvider defaultVisibleColumnsProvider
     */
    public function test_high_signal_columns_remain_default_visible(string $columnName): void
    {
        $pattern = '/(?:TextColumn|IconColumn)::make\(\'' . preg_quote($columnName, '/') . '\'\)(?:(?!::make\()[\s\S])*?->toggleable\(isToggledHiddenByDefault:\s*true\)/';

        $this->assertDoesNotMatchRegularExpression(
            $pattern,
            $this->customerResourceStripped,
            'AI-1097: column "' . $columnName . '" must NOT carry ->toggleable(isToggledHiddenByDefault: true) — it is one of the 5 highest-signal columns the user expects to see at first paint'
        );
    }

    public static function defaultVisibleColumnsProvider(): array
    {
        return [
            'name' => ['name'],
            'phone' => ['phone'],
            'email' => ['email'],
            'active' => ['active'],
            'tags.name' => ['tags.name'],
        ];
    }

    public function test_ai_1097_task_id_marker_in_customer_resource(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-2f5a6c / AI-1097',
            $this->customerResource,
            'AI-1097: task-id marker comment must be present adjacent to the column-bloat reduction in CustomerResource'
        );
    }
}
