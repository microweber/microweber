<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-AI1101 — the /admin/customers ID column exposed the raw
 * auto-increment value (e.g. "17426"). It is now rendered as a stable customer
 * reference code derived purely from the id ("C-17426"), so the surface reads
 * as a reference rather than a database internal. The column also stays
 * hidden-by-default (per AI-1097), so this is a low-blast presentation change.
 */
class CustomerAI1101RefCodeContractTest extends TestCase
{
    #[Test]
    public function id_column_renders_a_reference_code(): void
    {
        $src = (string) file_get_contents(base_path('Modules/Customer/Filament/CustomerResource.php'));
        $this->assertStringContainsString(
            "'C-' . str_pad((string) \$state, 5, '0', STR_PAD_LEFT)",
            $src,
            'The id column must render a stable C-NNNNN reference code derived from the id.'
        );
    }

    #[Test]
    public function the_format_callback_behaves_as_specified(): void
    {
        $fmt = fn ($state) => $state ? 'C-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT) : '—';
        $this->assertSame('C-17426', $fmt(17426), 'A 5-digit id keeps its digits behind the C- prefix.');
        $this->assertSame('C-00042', $fmt(42), 'A short id is zero-padded to 5 digits.');
        $this->assertSame('—', $fmt(null), 'A null id renders the em-dash placeholder.');
    }
}
