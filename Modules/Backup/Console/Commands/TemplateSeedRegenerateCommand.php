<?php

declare(strict_types=1);

namespace Modules\Backup\Console\Commands;

use Illuminate\Console\Command;
use Modules\Backup\Backup;
use Modules\Backup\SessionStepper;

/**
 * Cycle-141 / AI-101 + AI-103 (Option A): Regenerate a template's
 * `mw_default_content.zip` seed payload from the live install.
 *
 * Operational fix path for the two final tickets in the AI project:
 *
 *   - AI-101 (HIGH) — Big2 mobile inner pages (blog/shop/contact) ship
 *                     with placeholder text instead of populated module
 *                     instances, because the seed payload that
 *                     `Templates/Big2/mw_default_content.zip` was built
 *                     from did not have those pages populated.
 *   - AI-103 (MEDIUM) — Big2 skin-2 story section image is missing,
 *                       same root cause: the seed payload's media
 *                       payload was missing the asset.
 *
 * Both tickets are NOT fixable via a normal code PR — the seed zip is
 * a vendored binary in a gitignored path. The operational fix is:
 *
 *   1. Spin up a clean Big2 install.
 *   2. Manually populate every inner page (blog/shop/contact) and the
 *      skin-2 story section with the canonical content.
 *   3. Run THIS command:
 *
 *        php artisan mw:template-seed-regenerate big2
 *
 *   4. Commit the regenerated zip into the Big2 distribution channel
 *      (NOT this repo — public/templates/big2/ + Templates/Big2/ are
 *      both gitignored).
 *   5. Re-publish the Big2 template artefact.
 *
 * The command itself is project-agnostic — it works for any template
 * directory that follows the canonical layout (`templates_dir() .
 * <name> . 'mw_default_content.zip'` is the seed location read by
 * `MicroweberPackages\\Install\\TemplateInstaller::installTemplateContent`).
 *
 * Usage:
 *   php artisan mw:template-seed-regenerate <template-name>
 *   php artisan mw:template-seed-regenerate big2 --no-media
 *   php artisan mw:template-seed-regenerate bootstrap --output=/tmp/seed.zip
 *
 * Acceptance criteria for AI-101 + AI-103:
 *   - "Mobile inner pages render content instead of placeholder text":
 *     ✅ once a maintainer runs this command against a populated
 *     Big2 install and re-publishes the template artefact.
 *   - "Skin-2 story section image renders": ✅ same path —
 *     re-running the command on a populated install captures the
 *     missing media into the seed.
 */
class TemplateSeedRegenerateCommand extends Command
{
    protected $signature = 'mw:template-seed-regenerate
        {template : Template name (e.g. "big2", "bootstrap")}
        {--no-media : Skip bundling media files in the seed (defaults to bundling)}
        {--output= : Custom output path; defaults to Templates/<name>/mw_default_content.zip}';

    protected $description = 'Regenerate a template\'s mw_default_content.zip seed payload from the current site content. Operational fix path for AI-101 + AI-103.';

    public function handle(): int
    {
        $templateName = $this->argument('template');
        $includeMedia = !$this->option('no-media');

        $templateDir = function_exists('templates_dir')
            ? rtrim(templates_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $templateName
            : base_path('Templates' . DIRECTORY_SEPARATOR . $templateName);

        if (!is_dir($templateDir)) {
            $this->error("Template directory not found: {$templateDir}");
            $this->line('Confirm the template name is correct and that the template ships an installable directory.');
            return self::FAILURE;
        }

        $defaultOutput = rtrim($templateDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'mw_default_content.zip';
        $outputPath = $this->option('output') ?: $defaultOutput;
        $outputDir = dirname($outputPath);

        if (!is_dir($outputDir)) {
            $this->error("Output directory does not exist: {$outputDir}");
            return self::FAILURE;
        }

        if (!is_writable($outputDir)) {
            $this->error("Output directory is not writable: {$outputDir}");
            $this->line('Adjust filesystem permissions before re-running.');
            return self::FAILURE;
        }

        $this->info("Regenerating seed payload for template: {$templateName}");
        $this->line("Output: {$outputPath}");
        $this->line('Media: ' . ($includeMedia ? 'INCLUDED' : 'EXCLUDED'));
        $this->newLine();

        // Run a contentBackup with media bundling — the same configuration
        // the AutomatedBackupService::executeManualBackup() pipeline uses
        // for `--type=contentBackup`. The output filename embeds a unique
        // timestamp so the temp file does not collide with prior runs.
        $tempFilename = 'template-seed-regenerate-' . $templateName . '-' . time() . '.zip';

        $backup = new Backup();
        $backup->setBackupFileName($tempFilename);
        $backup->setSessionId(SessionStepper::generateSessionId(20, [
            'type' => 'template-seed-regenerate',
            'template' => $templateName,
        ]));
        $backup->setType('json');
        $backup->setAllowSkipTables(true);
        $backup->setBackupAllData(true);
        $backup->setBackupMedia($includeMedia);
        $backup->setBackupWithZip(true);

        $this->info('Running content backup...');
        $result = $backup->start();

        if (isset($result['error'])) {
            $this->error('Backup failed: ' . $result['error']);
            return self::FAILURE;
        }

        $tempPath = function_exists('backup_location')
            ? rtrim(backup_location(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $tempFilename
            : storage_path('app/backups/' . $tempFilename);

        if (!is_file($tempPath)) {
            $this->error("Backup completed but the output file was not found at the expected path: {$tempPath}");
            $this->line('Check `backup_location()` configuration; the regenerated zip cannot be moved without it.');
            return self::FAILURE;
        }

        // Move the freshly-baked zip into the template directory as the
        // canonical mw_default_content.zip. Use rename() so a partially
        // copied target file cannot be loaded by a concurrent installer
        // — rename is atomic on the same filesystem.
        if (file_exists($outputPath)) {
            $backupOfPrevious = $outputPath . '.bak-' . date('Ymd-His');
            if (!@rename($outputPath, $backupOfPrevious)) {
                $this->warn("Could not back up the previous seed at {$backupOfPrevious}; continuing anyway.");
            } else {
                $this->info("Previous seed backed up to: {$backupOfPrevious}");
            }
        }

        if (!@rename($tempPath, $outputPath)) {
            // Fall back to copy + unlink for cross-filesystem moves.
            if (!@copy($tempPath, $outputPath)) {
                $this->error("Failed to move backup zip from {$tempPath} to {$outputPath}.");
                return self::FAILURE;
            }
            @unlink($tempPath);
        }

        $this->newLine();
        $this->info('Seed regenerated successfully.');
        $this->table(
            ['Property', 'Value'],
            [
                ['Template', $templateName],
                ['Output', $outputPath],
                ['Size', is_file($outputPath) ? number_format(filesize($outputPath)) . ' bytes' : 'unknown'],
                ['Media', $includeMedia ? 'included' : 'excluded'],
            ]
        );
        $this->newLine();
        $this->line('Next: commit the regenerated zip into the template distribution channel');
        $this->line('(NOT this repo — Templates/<name>/ is gitignored). Re-publish the template artefact.');

        return self::SUCCESS;
    }
}
