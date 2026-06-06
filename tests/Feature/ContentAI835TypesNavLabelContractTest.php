<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Content\Filament\Admin\Pages\ContentTypesPage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-AI835 — the "Content Types" sidebar label wrapped to two
 * lines in its nav group. Shorten the SIDEBAR label to "Types" while keeping
 * the page title + heading the full "Content Types" so the surface itself
 * stays unambiguous.
 *
 * This pins the split: short nav label, full title/heading.
 */
class ContentAI835TypesNavLabelContractTest extends TestCase
{
    #[Test]
    public function sidebar_nav_label_is_the_short_form(): void
    {
        $this->assertSame('Types', ContentTypesPage::getNavigationLabel(),
            'The sidebar nav label must be the short "Types" to avoid a 2-line wrap.');
    }

    #[Test]
    public function page_title_and_heading_stay_the_full_name(): void
    {
        $page = new ContentTypesPage();
        $this->assertSame('Content Types', $page->getTitle(),
            'The page title must stay the full "Content Types".');
        $this->assertSame('Content Types', $page->getHeading(),
            'The page heading must stay the full "Content Types".');
    }

    #[Test]
    public function nav_label_property_is_declared_in_source(): void
    {
        $src = (string) file_get_contents(base_path(
            'Modules/Content/Filament/Admin/Pages/ContentTypesPage.php'
        ));
        $this->assertMatchesRegularExpression(
            "/\\\$navigationLabel\s*=\s*'Types'/",
            $src,
            'The short nav label must be declared as a static property.'
        );
    }
}
