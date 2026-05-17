<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-2b1020 / AI-816 — Save CTA on Create Post + sibling
 * content-create surfaces must render brand-blue (Color::Primary /
 * #0d6efd), NOT success-green (#2FB344).
 *
 * Designer's framing: green is the post-submit "saved successfully"
 * semantic state. Using it on the UNSUBMITTED primary CTA conflates
 * "click to save" with "saved". Project-wide primary-CTA convention
 * is brand-blue (#0d6efd / Color::Primary) — AI-737 / AI-704 / AI-731
 * / AI-794 lineage. AI-816 specifically named the Create Post Save
 * button at backgroundColor rgb(47, 179, 68); designer recommended
 * Slice B audit-the-base-class approach. Slice B identified 5
 * sibling consumers all sharing the same obsolete "match the green
 * toolbar SAVE pill" rationale — the toolbar SAVE became a BLACK
 * pill in AI-699 / task-2026-05-16-8b10a3 so the green-match argument
 * no longer applies.
 *
 * Class-inheritance coverage: CreateProduct and CreatePage both
 * extend CreateContent, so flipping CreateContent::getHeaderActions
 * fixes the Save CTA on Create Post + Create Page + Create Product
 * simultaneously.
 *
 * In-scope (5 changes):
 *   1. Modules/Content/.../Pages/CreateContent.php (saveContent action)
 *   2. Modules/Content/.../Pages/EditContent.php (EditAction save)
 *   3. src/MicroweberPackages/LiveEdit/.../AdminLiveEditPage.php
 *      (in-canvas compact Create modal)
 *   4. Modules/Content/Filament/ContentTableList.php (CreateAction)
 *   5. Modules/Content/Filament/ContentTableList.php (EditAction)
 *
 * Out-of-scope but flagged as AI-816a follow-up candidates: the
 * other primary saves surfaced by Slice B (CreateCategory, CreateOrder,
 * EditOrder, TranslationResource, ModuleConfigurationResource,
 * AdminShopAutoRespondEmailPage, WorkflowResource, MediaResource,
 * AiWizard, PermissionResource, RoleResource, InvoiceResource,
 * ProductPricingRuleResource, BackupScheduleResource,
 * ErrorTrackingResource, Billing/Settings). Each needs designer
 * confirmation that it's a primary submit (not a semantic-success
 * action like approve/publish/mark-paid) before flipping.
 *
 * Out-of-scope, intentionally stays green (semantic-success): Place
 * Order in CheckoutWizard:451 (pinned by Ai209PrimaryColorUnification
 * contract), Publish bulk action in ContentResource:2107, Mark-Paid
 * etc. in OrderResource:478, install/enable in MarketplaceResource:415,
 * widget chart color tokens.
 */
class Admin2b1020AI816SaveCtaPrimaryColorContractTest extends TestCase
{
    private function fileContents(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    public static function inScopeSaveCtaProvider(): array
    {
        return [
            'CreateContent saveContent action' => [
                'Modules/Content/Filament/Admin/ContentResource/Pages/CreateContent.php',
                "Actions\\Action::make('saveContent')",
                'primary',
            ],
            'EditContent EditAction save' => [
                'Modules/Content/Filament/Admin/ContentResource/Pages/EditContent.php',
                "->action('saveContent')",
                'primary',
            ],
            'AdminLiveEditPage in-canvas Create modal' => [
                'src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php',
                "->modalHeading('Create ' . \$contentType)",
                'primary',
            ],
            'ContentTableList CreateAction' => [
                'Modules/Content/Filament/ContentTableList.php',
                "CreateAction::make('create')",
                'primary',
            ],
            'ContentTableList EditAction' => [
                'Modules/Content/Filament/ContentTableList.php',
                "EditAction::make('edit')",
                'primary',
            ],
        ];
    }

    #[Test]
    #[DataProvider('inScopeSaveCtaProvider')]
    public function in_scope_save_cta_renders_brand_blue_not_success_green(string $relativePath, string $anchor, string $expectedColor): void
    {
        $source = $this->fileContents($relativePath);
        $this->assertNotSame('', $source, "Source file is empty or missing: {$relativePath}");

        $anchorPos = strpos($source, $anchor);
        $this->assertNotFalse($anchorPos, "Anchor not found in {$relativePath}: {$anchor}");

        // Slice a fixed lookahead window from the anchor. Earlier
        // attempt sliced to the next `;` but docblock prose can
        // contain `;` (e.g. "lineage; green is reserved...") which
        // cuts the slice before the ->color() call.
        $slice = substr($source, $anchorPos, 4000);

        $this->assertStringContainsString(
            "->color('{$expectedColor}')",
            $slice,
            "Expected ->color('{$expectedColor}') in action chain for {$anchor} in {$relativePath}"
        );

        // LESSONS selector-self-match guard family: pre-strip `//`
        // line comments + `/* … */` block comments before the negative
        // assertion so the test doesn't false-fail on commented-out
        // legacy `->color('success')` calls (e.g. CreateContent:160
        // has a vestigial `// Actions\CreateAction::make()... ->color('success')`
        // historical reference inside getFormActions()).
        $sliceWithoutComments = preg_replace('~//[^\n]*~', '', $slice);
        $sliceWithoutComments = preg_replace('~/\*.*?\*/~s', '', (string) $sliceWithoutComments);

        $this->assertStringNotContainsString(
            "->color('success')",
            (string) $sliceWithoutComments,
            "AI-816 regression: action chain for {$anchor} in {$relativePath} still uses ->color('success')"
        );
    }

    #[Test]
    public function class_inheritance_carries_fix_to_create_page_and_create_product(): void
    {
        $createPage = $this->fileContents('Modules/Page/Filament/Resources/PageResource/Pages/CreatePage.php');
        $createProduct = $this->fileContents('Modules/Product/Filament/Admin/Resources/ProductResource/Pages/CreateProduct.php');

        $this->assertMatchesRegularExpression(
            '/class\s+CreatePage\s+extends\s+CreateContent\b/',
            $createPage,
            'CreatePage must extend CreateContent so the AI-816 Save-CTA fix carries via inheritance'
        );
        $this->assertMatchesRegularExpression(
            '/class\s+CreateProduct\s+extends\s+CreateContent\b/',
            $createProduct,
            'CreateProduct must extend CreateContent so the AI-816 Save-CTA fix carries via inheritance'
        );
    }

    #[Test]
    public function obsolete_match_the_green_toolbar_docblock_replaced_with_ai816_rationale(): void
    {
        $adminLiveEdit = $this->fileContents('src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php');
        $contentTableList = $this->fileContents('Modules/Content/Filament/ContentTableList.php');

        // AdminLiveEditPage: assert AI-816 marker present near the in-canvas
        // compact Create modal action. Fixed-length lookahead so docblock
        // semicolons don't truncate the slice (see Edit history above).
        $anchor = "->modalHeading('Create ' . \$contentType)";
        $pos = strpos($adminLiveEdit, $anchor);
        $this->assertNotFalse($pos);
        $slice = substr($adminLiveEdit, $pos, 4000);
        $this->assertStringContainsString(
            'task-2026-05-17-2b1020',
            $slice,
            'AdminLiveEditPage action chain must carry the AI-816 task-id marker'
        );
        $this->assertStringContainsString(
            'AI-699',
            $slice,
            'AdminLiveEditPage AI-816 rationale should cite AI-699 (toolbar SAVE became BLACK pill, obsoletes the green-match argument)'
        );

        // ContentTableList: AI-816 marker on both CreateAction + EditAction.
        $createAnchor = "CreateAction::make('create')";
        $createPos = strpos($contentTableList, $createAnchor);
        $this->assertNotFalse($createPos);
        $createSlice = substr($contentTableList, $createPos, 4000);
        $this->assertStringContainsString('task-2026-05-17-2b1020', $createSlice);

        $editAnchor = "EditAction::make('edit')";
        $editPos = strpos($contentTableList, $editAnchor);
        $this->assertNotFalse($editPos);
        $editSlice = substr($contentTableList, $editPos, 4000);
        $this->assertStringContainsString('task-2026-05-17-2b1020', $editSlice);
    }

    #[Test]
    public function publish_bulk_action_stays_green_semantic_success_unaffected(): void
    {
        // ContentResource:2107 — Publish bulk action is genuinely a
        // success-semantic state (publishing moves content from draft
        // to active/positive). MUST stay green per project semantics.
        $source = $this->fileContents('Modules/Content/Filament/Admin/ContentResource.php');
        $anchor = "BulkAction::make('publish')";
        $pos = strpos($source, $anchor);
        $this->assertNotFalse($pos, 'Publish bulk action must exist for the semantic-stays-green regression guard');
        $slice = substr($source, $pos, 1500);
        $this->assertStringContainsString(
            "->color('success')",
            $slice,
            'AI-816 must NOT regress the Publish bulk action: it is genuinely success-semantic and stays green'
        );
    }

    #[Test]
    public function place_order_checkout_cta_stays_green_per_ai209_contract(): void
    {
        // CheckoutWizard:451 — Place Order is the final-step CTA on a
        // submitted form (semantic-success-by-design) and is explicitly
        // pinned green by Ai209PrimaryColorUnificationContractTest.
        // AI-816 must NOT touch this.
        $source = $this->fileContents('Modules/Checkout/Livewire/CheckoutWizard.php');
        // Search for ->color('success') near Place Order to confirm
        // it's still there.
        $this->assertStringContainsString(
            "->color('success')",
            $source,
            'AI-816 must NOT regress the Checkout Place Order CTA (semantic-success-by-design per AI-209 pin)'
        );
    }

    #[Test]
    public function ai816a_followup_candidates_documented_in_test_docblock(): void
    {
        $self = (string) file_get_contents(__FILE__);
        // The docblock above must mention "AI-816a follow-up candidates"
        // and enumerate at least 3 of the broader Slice B audit hits so
        // a future audit can find them.
        $this->assertStringContainsString('AI-816a follow-up candidates', $self);
        $this->assertStringContainsString('CreateCategory', $self);
        $this->assertStringContainsString('CreateOrder', $self);
        $this->assertStringContainsString('TranslationResource', $self);
    }
}
