<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1099 / task-2026-05-28-2f5a6c Batch 2.
 *
 * Scope (dispatch verbatim): "/admin/payments double-primary buttons +
 * empty-state generic icon + missing CTA."
 *
 * Three failure families pinned by this test:
 *
 *  1) Double-primary buttons. ListPayments::getHeaderActions() rendered
 *     TWO brand-blue primary CTAs simultaneously — the default
 *     CreateAction and a custom Action::make('settings') without an
 *     explicit color (Filament defaults custom Actions to primary).
 *     Fix: CreateAction now hides via ->visible(fn) when the table has
 *     zero rows, and the Payment Provider Settings shortcut is
 *     downgraded to ->color('gray') so it never competes with the
 *     primary CTA.
 *
 *  2) Empty-state generic icon. PaymentResource::table() had no
 *     ->emptyState() wiring, so Filament fell back to its built-in
 *     generic icon + "No records" copy. Fix: wire ->emptyState(...) to
 *     the shared modules.content::filament.admin.empty-state blade.
 *
 *  3) Missing CTA. Added a Payment branch to the shared empty-state
 *     blade carrying a text-only chrome (no SVG — payments are
 *     transaction records, not setup objects) with a CTA pointing at
 *     the Payment Provider Settings page (records are created by
 *     transactions, not manually).
 *
 * All three source files carry the task-id marker comment per the
 * per-task source-comment marker convention.
 */
class AdminPayments1099EmptyStateContractTest extends TestCase
{
    private string $listPayments;

    private string $paymentResource;

    private string $emptyStateBlade;

    private string $emptyStateBladeStripped;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->listPayments = (string) file_get_contents(
            self::projectRoot()
            . '/Modules/Payment/Filament/Admin/Resources/PaymentResource/Pages/ListPayments.php'
        );

        $this->paymentResource = (string) file_get_contents(
            self::projectRoot()
            . '/Modules/Payment/Filament/Admin/Resources/PaymentResource.php'
        );

        $this->emptyStateBlade = (string) file_get_contents(
            self::projectRoot()
            . '/Modules/Content/resources/views/filament/admin/empty-state.blade.php'
        );

        $stripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->emptyStateBlade);
        $stripped = (string) preg_replace('~<!--.*?-->~s', '', $stripped);
        $this->emptyStateBladeStripped = $stripped;
    }

    public function test_list_payments_create_action_has_visibility_gate(): void
    {
        $this->assertMatchesRegularExpression(
            '/Actions\\\\CreateAction::make\(\)\s*->visible\(\s*fn\s*\(\)\s*:\s*bool\s*=>\s*static::getResource\(\)::getEloquentQuery\(\)->exists\(\)\s*\)/s',
            $this->listPayments,
            'AI-1099: ListPayments::getHeaderActions() CreateAction must carry ->visible(fn (): bool => static::getResource()::getEloquentQuery()->exists())'
        );
    }

    public function test_list_payments_no_longer_has_bare_create_action(): void
    {
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->listPayments);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);

        $this->assertDoesNotMatchRegularExpression(
            '/Actions\\\\CreateAction::make\(\)\s*,\s*Action::make\(.settings.\)/s',
            $stripped,
            'AI-1099: legacy bare Actions\\CreateAction::make() (no visible() gate) must be gone from ListPayments'
        );
    }

    public function test_list_payments_settings_action_uses_gray_color(): void
    {
        $this->assertMatchesRegularExpression(
            '/Action::make\(.settings.\)[\s\S]*?->color\(.gray.\)/s',
            $this->listPayments,
            'AI-1099: Payment Provider Settings shortcut must carry ->color(\'gray\') so it does not render as a competing primary blue button'
        );
    }

    public function test_payment_resource_table_uses_shared_empty_state(): void
    {
        $this->assertMatchesRegularExpression(
            "/->emptyState\(function\s*\(Table\s+\\\$table\)\s*\{[\s\S]*?modules\.content::filament\.admin\.empty-state[\s\S]*?\}\)/s",
            $this->paymentResource,
            'AI-1099: PaymentResource::table() must wire ->emptyState() to the shared modules.content::filament.admin.empty-state blade'
        );
    }

    public function test_empty_state_blade_has_payment_branch(): void
    {
        $this->assertMatchesRegularExpression(
            '/@if\(\$modelName == Modules\\\\Payment\\\\Models\\\\Payment::class\)/',
            $this->emptyStateBlade,
            'AI-1099: shared empty-state.blade.php must carry an @if($modelName == Modules\\Payment\\Models\\Payment::class) branch'
        );
    }

    public function test_empty_state_blade_payment_branch_has_heading(): void
    {
        $this->assertMatchesRegularExpression(
            '/@if\(\$modelName == Modules\\\\Payment\\\\Models\\\\Payment::class\)[\s\S]*?You do not have any payments yet\./s',
            $this->emptyStateBlade,
            'AI-1099: Payment branch must carry the canonical "You do not have any payments yet." heading'
        );
    }

    public function test_empty_state_blade_payment_branch_has_cta(): void
    {
        $this->assertMatchesRegularExpression(
            '/@if\(\$modelName == Modules\\\\Payment\\\\Models\\\\Payment::class\)[\s\S]*?route\(.filament\.admin\.resources\.payment-providers\.index.\)[\s\S]*?Configure payment providers/s',
            $this->emptyStateBlade,
            'AI-1099: Payment branch must carry a CTA pointing at filament.admin.resources.payment-providers.index with label "+ Configure payment providers"'
        );
    }

    public function test_empty_state_blade_payment_branch_uses_mw_table_empty_cta_class(): void
    {
        $this->assertMatchesRegularExpression(
            '/@if\(\$modelName == Modules\\\\Payment\\\\Models\\\\Payment::class\)[\s\S]*?class="mw-table-empty-cta"/s',
            $this->emptyStateBlade,
            'AI-1099: Payment branch CTA must use the canonical .mw-table-empty-cta class (matches Order/Customer/Invoice branches)'
        );
    }

    public function test_ai_1099_task_id_marker_in_list_payments(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-2f5a6c / AI-1099',
            $this->listPayments,
            'AI-1099: task-id marker comment must be present adjacent to the visibility-gate change in ListPayments'
        );
    }

    public function test_ai_1099_task_id_marker_in_payment_resource(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-2f5a6c / AI-1099',
            $this->paymentResource,
            'AI-1099: task-id marker comment must be present adjacent to the emptyState wiring in PaymentResource'
        );
    }

    public function test_ai_1099_task_id_marker_in_empty_state_blade(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-2f5a6c / AI-1099',
            $this->emptyStateBlade,
            'AI-1099: task-id marker comment must be present adjacent to the Payment branch in empty-state.blade.php'
        );
    }
}
