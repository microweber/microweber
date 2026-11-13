<?php

declare(strict_types=1);

namespace MicroweberPackages\Template\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\Template\Export\TemplateImporter;

/**
 * Import (upload) a template ZIP produced by mw:template-export. Rejects any
 * archive that contains PHP/executable files or unsafe paths. See
 * {@see TemplateImporter}.
 *
 *   php artisan mw:template-import /path/to/MyTheme.zip
 *   php artisan mw:template-import MyTheme.zip --dest=/abs/path/to/ExtraTemplates
 */
class TemplateImportCommand extends Command
{
    protected $signature = 'mw:template-import
        {zip : Path to the template ZIP to import}
        {--dest= : Destination templates root (default: ExtraTemplates/)}';

    protected $description = 'Import an uploaded template ZIP (data + assets only; PHP is rejected).';

    public function handle(): int
    {
        $importer = (new TemplateImporter())->setDestRoot($this->option('dest') ? (string) $this->option('dest') : null);

        try {
            $info = $importer->importZip((string) $this->argument('zip'));
        } catch (\Throwable $e) {
            $this->error('Import rejected: ' . $e->getMessage());
            return self::FAILURE;
        }

        // The new template dir is auto-discovered on the next scan; clear caches so
        // it shows up immediately.
        try {
            $this->call('cache:clear');
        } catch (\Throwable) {
        }

        $this->info("Imported template \"{$info['name']}\" (child of {$info['parent_template']}).");
        $this->line('  files: ' . $info['files']);
        $this->line('  folder: ' . $info['dir']);
        $this->line('  Activate it from Admin → Templates, or set it as the current template.');

        return self::SUCCESS;
    }
}
