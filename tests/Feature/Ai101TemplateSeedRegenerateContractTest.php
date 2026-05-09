<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Backup\Console\Commands\TemplateSeedRegenerateCommand;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-141 / AI-101 + AI-103 (Option A) — operational fix-path contract.
 *
 * Pins the cycle-141 deliverable: a project-agnostic artisan command
 * `mw:template-seed-regenerate <template>` that automates step (a)+(b)
 * of the operational fix path for the two final tickets in the AI
 * project. Both tickets are content-seed issues in a vendored binary
 * artefact (Templates/<X>/mw_default_content.zip) that cannot ship
 * via a normal code PR; the command is the closest equivalent of a
 * code-side fix that this repo can offer.
 *
 * Acceptance criteria:
 *   - The command class exists at the canonical path.
 *   - The command signature exposes the brief-required surface
 *     (template arg, --no-media, --output).
 *   - The BackupServiceProvider registers the command alongside the
 *     pre-existing BackupCommand.
 *   - TROUBLESHOOTING.md documents the operational fix path with
 *     the AI-101 + AI-103 anchor + cycle-141 anchor.
 *
 * Style after Sec05SsrfAndStoredXssContractTest / Ai* — source-grep
 * assertions that catch regressions at refactor time without needing
 * app boot or DB seeding.
 */
class Ai101TemplateSeedRegenerateContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function command_class_exists_at_canonical_path(): void
    {
        $this->assertFileExists(
            base_path('Modules/Backup/Console/Commands/TemplateSeedRegenerateCommand.php'),
            'TemplateSeedRegenerateCommand MUST live under '
            . 'Modules/Backup/Console/Commands/ alongside the pre-existing '
            . 'BackupCommand.'
        );

        $this->assertTrue(
            class_exists(TemplateSeedRegenerateCommand::class),
            'Modules\\Backup\\Console\\Commands\\TemplateSeedRegenerateCommand '
            . 'class MUST be autoloadable.'
        );
    }

    #[Test]
    public function command_signature_exposes_required_surface(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/TemplateSeedRegenerateCommand.php');

        // Signature MUST start with mw:template-seed-regenerate so the
        // operational runbook stays callable verbatim.
        $this->assertMatchesRegularExpression(
            '/\$signature\s*=\s*[\'"]mw:template-seed-regenerate/',
            $src,
            'TemplateSeedRegenerateCommand::$signature MUST start with '
            . 'mw:template-seed-regenerate so TROUBLESHOOTING.md and '
            . 'release notes can reference the canonical CLI invocation.'
        );

        // Required positional arg + flags from the brief.
        foreach ([
            '{template :',
            '--no-media :',
            '--output= :',
        ] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $src,
                "TemplateSeedRegenerateCommand::\$signature MUST expose '{$needle}'."
            );
        }
    }

    #[Test]
    public function command_is_registered_in_backup_service_provider(): void
    {
        $src = $this->read('Modules/Backup/Providers/BackupServiceProvider.php');

        $this->assertStringContainsString(
            'use Modules\\Backup\\Console\\Commands\\TemplateSeedRegenerateCommand;',
            $src,
            'BackupServiceProvider MUST `use` the new command class.'
        );

        $this->assertStringContainsString(
            'TemplateSeedRegenerateCommand::class',
            $src,
            'BackupServiceProvider::register() MUST include '
            . 'TemplateSeedRegenerateCommand::class in the $this->commands(...) '
            . 'array so `php artisan mw:template-seed-regenerate ...` resolves.'
        );
    }

    #[Test]
    public function command_runs_a_content_backup_with_media_bundling(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/TemplateSeedRegenerateCommand.php');

        // The handle() body MUST drive a content backup using the same
        // configuration the AutomatedBackupService uses for
        // --type=contentBackup. Pin every load-bearing setter so a
        // future refactor cannot silently swap in a different backup
        // shape (which would produce a seed zip that does not match
        // what the TemplateInstaller / Restore pipeline expects).
        foreach ([
            'new Backup()',
            '->setBackupFileName',
            '->setSessionId',
            "->setType('json')",
            '->setAllowSkipTables(true)',
            '->setBackupAllData(true)',
            '->setBackupMedia($includeMedia)',
            '->setBackupWithZip(true)',
            '->start()',
        ] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $src,
                "TemplateSeedRegenerateCommand::handle() MUST call '{$needle}' "
                . 'so the regenerated seed matches the contentBackup shape '
                . 'TemplateInstaller::installTemplateContent expects.'
            );
        }
    }

    #[Test]
    public function command_writes_to_canonical_template_path(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/TemplateSeedRegenerateCommand.php');

        // Default output is Templates/<name>/mw_default_content.zip
        // — the same path TemplateInstaller::installTemplateContent reads.
        // A regression here would silently break the install flow.
        $this->assertStringContainsString(
            "'mw_default_content.zip'",
            $src,
            'TemplateSeedRegenerateCommand MUST default the output filename '
            . 'to mw_default_content.zip — the canonical name read by '
            . 'TemplateInstaller::installTemplateContent.'
        );

        // Atomic rename pattern (with rename() preferred over copy+unlink
        // because rename is atomic on the same filesystem so a partially
        // written file cannot be picked up by a concurrent installer).
        $this->assertStringContainsString(
            'rename(',
            $src,
            'TemplateSeedRegenerateCommand MUST use rename() to move the '
            . 'fresh zip into place atomically. Cross-filesystem fallback '
            . 'via copy()+unlink() is acceptable.'
        );
    }

    #[Test]
    public function command_backs_up_previous_seed_before_overwriting(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/TemplateSeedRegenerateCommand.php');

        // Safety rail: never destroy the previous mw_default_content.zip
        // without a timestamped backup. The operational runbook depends
        // on this so a botched regen does not orphan a working seed.
        $this->assertMatchesRegularExpression(
            '/\.bak-[\'"]\s*\.\s*date\(/',
            $src,
            'TemplateSeedRegenerateCommand MUST timestamp-back-up the '
            . 'previous mw_default_content.zip (e.g. .bak-YYYYMMDD-HHMMSS) '
            . 'before overwriting it.'
        );
    }

    #[Test]
    public function troubleshooting_doc_explains_ai_101_and_ai_103_root_cause(): void
    {
        $src = $this->read('TROUBLESHOOTING.md');

        // The TROUBLESHOOTING entry MUST carry the cycle-141 anchor with
        // both ticket IDs + the runbook command.
        $this->assertStringContainsString(
            'AI-101',
            $src,
            'TROUBLESHOOTING.md MUST carry an AI-101 anchor so a future '
            . 'maintainer hitting the same symptom finds the operational '
            . 'fix path immediately.'
        );
        $this->assertStringContainsString(
            'AI-103',
            $src,
            'TROUBLESHOOTING.md MUST carry an AI-103 anchor.'
        );
        $this->assertStringContainsString(
            'mw:template-seed-regenerate big2',
            $src,
            'TROUBLESHOOTING.md MUST cite the canonical CLI invocation '
            . '`php artisan mw:template-seed-regenerate big2` so the '
            . 'runbook is copy-paste-runnable.'
        );
        $this->assertStringContainsString(
            'mw_default_content.zip',
            $src,
            'TROUBLESHOOTING.md MUST name the seed-payload file so the '
            . 'maintainer knows what artefact to re-publish after running '
            . 'the regen command.'
        );
    }
}
