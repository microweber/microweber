<?php

declare(strict_types=1);

namespace Modules\Backup\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\Install\TemplateInstaller;
use Modules\Content\Models\Content;

/**
 * Big2 full-content seeder (task-2026-05-13-3330a0 — human request).
 *
 * Restores the canonical `Templates/Big2/mw_default_content.zip` payload
 * via the existing `MicroweberPackages\Install\TemplateInstaller`. That
 * zip is the source-of-truth Big2 demo site that ships with the
 * template — it contains:
 *
 *   - backup_*.json: the full content tree (pages, posts, products,
 *     categories, menus, options, media references) Big2 was designed
 *     against
 *   - media/default/: the matching JPG/SVG/PNG assets the content
 *     references by filename
 *
 * Distinct from the existing demo commands in this module:
 *   - `mw:big2-demo-seed` creates ONE page that embeds every Big2
 *     layout template — used for mobile-audit touch-target sweeps
 *   - `mw:shop-demo-seed` creates a category + N synthetic products
 *     — used for shop layout audits
 *   - `mw:big2-install-content` (THIS COMMAND) restores the canonical
 *     full Big2 site so tester-agent-1 has a realistic content surface
 *     to evaluate (homepage / shop / blog / contact / about / etc.)
 *
 * Usage:
 *   php artisan mw:big2-install-content
 *   php artisan mw:big2-install-content --activate    # also set Big2 as current template
 *   php artisan mw:big2-install-content --replace     # wipe existing content tree first
 *   php artisan mw:big2-install-content --language=en # restore content in this language
 *
 * Safe to re-run: the underlying Restore manager uses overwrite-by-ID
 * so re-running on top of an existing install refreshes content rows
 * to their canonical values without creating duplicates.
 */
class Big2InstallContentCommand extends Command
{
    protected $signature = 'mw:big2-install-content
        {--activate : Also set Big2 as the current template (current_template option)}
        {--replace : Delete all existing content rows before restoring (dangerous — backup first)}
        {--language=en : Language code for the imported content (default: en)}';

    protected $description = 'Restore the canonical Big2 demo site content from Templates/Big2/mw_default_content.zip so tester-agent-1 has a realistic surface to evaluate.';

    private const TEMPLATE_NAME = 'Big2';

    public function handle(): int
    {
        $templateDir = base_path('Templates' . DIRECTORY_SEPARATOR . self::TEMPLATE_NAME);
        $zipPath = $templateDir . DIRECTORY_SEPARATOR . 'mw_default_content.zip';

        if (!is_dir($templateDir)) {
            $this->error("Big2 template directory not found: {$templateDir}");
            $this->line('The Big2 template ships gitignored — install it under Templates/Big2 first.');
            return self::FAILURE;
        }

        if (!is_file($zipPath) || !is_readable($zipPath)) {
            $this->error("Big2 default-content zip not found: {$zipPath}");
            $this->line('The zip ships inside the Big2 template package — verify it was extracted correctly.');
            return self::FAILURE;
        }

        $sizeBytes = filesize($zipPath);
        $this->info(sprintf(
            'Found Big2 default content: %s (%s)',
            $zipPath,
            $this->formatBytes($sizeBytes)
        ));

        if ($this->option('replace')) {
            $existing = Content::query()->count();
            if ($existing > 0) {
                $this->warn("About to delete {$existing} existing content rows.");
                if (!$this->confirm('Continue?', false)) {
                    $this->line('Aborted by operator.');
                    return self::FAILURE;
                }
                Content::query()->delete();
                $this->info("Deleted {$existing} existing content rows.");
            }
        }

        $installer = new TemplateInstaller();
        $installer->setSelectedTemplate(self::TEMPLATE_NAME);
        $installer->setInstallDefaultContent(true);

        $language = (string) $this->option('language');
        if ($language !== '') {
            $installer->setLanguage($language);
        }

        $installer->setLogger(function (string $line): void {
            $this->line($line);
        });

        $this->info('Starting content restore via TemplateInstaller…');

        try {
            $installer->run();
        } catch (\Throwable $e) {
            $this->error('Content restore threw: ' . $e->getMessage());
            $this->line('Trace head:');
            $this->line(implode("\n", array_slice(explode("\n", $e->getTraceAsString()), 0, 12)));
            return self::FAILURE;
        }

        if ($this->option('activate')) {
            // setSelectedTemplate has already been called; explicit
            // setDefaultTemplate call below makes the activation
            // intent visible in the command log + handles the case
            // where install_default_content config was set false in
            // app config.
            $installer->setDefaultTemplate(self::TEMPLATE_NAME);
            $this->info('Set ' . self::TEMPLATE_NAME . ' as the current template.');
        }

        $totalContent = Content::query()->count();
        $this->info("Restore complete. Total content rows now: {$totalContent}.");

        $this->newLine();
        $this->line('Verify in the browser:');
        $this->line('  /              — homepage');
        $this->line('  /shop          — shop grid');
        $this->line('  /admin         — admin panel');
        $this->line('');
        $this->line("Active template: " . get_option('current_template', 'template'));

        return self::SUCCESS;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }
        if ($bytes < 1048576) {
            return number_format($bytes / 1024, 1) . ' KiB';
        }
        return number_format($bytes / 1048576, 1) . ' MiB';
    }
}
