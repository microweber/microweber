<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Form\Filament\Resources\FormEntryResource;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-AI758 — the form-submissions surface must use ONE canonical
 * name. It was called "Emails" (plural label → browser title / page heading /
 * breadcrumb) while the empty state + URL said "form submissions" /
 * "form-entries". Canonical name is "Form submissions"; the URL slug stays
 * "form-entries" so bookmarks keep working.
 *
 * Runtime-verified: /admin/form-entries browser title is now "Form Submissions"
 * (was "Emails").
 */
class FormEntryAI758NamingUnificationContractTest extends TestCase
{
    #[Test]
    public function plural_and_singular_labels_use_the_canonical_name(): void
    {
        $this->assertSame('Form submissions', FormEntryResource::getPluralModelLabel(),
            'The surface must be named "Form submissions" (not "Emails").');
        $this->assertSame('Form submission', FormEntryResource::getModelLabel(),
            'The singular label must be "Form submission".');
    }

    #[Test]
    public function url_slug_is_preserved_for_bookmarks(): void
    {
        $src = (string) file_get_contents(base_path('Modules/Form/Filament/Resources/FormEntryResource.php'));
        $this->assertMatchesRegularExpression(
            "/\\\$slug\s*=\s*'form-entries'/",
            $src,
            'The URL slug must stay form-entries (changing URLs breaks bookmarks).'
        );
        // Regression guard: the stale "Emails" plural label must be gone.
        $this->assertStringNotContainsString("\$pluralLabel = 'Emails'", $src,
            'The stale "Emails" plural label must be removed.');
    }
}
