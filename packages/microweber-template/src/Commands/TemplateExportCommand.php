<?php

declare(strict_types=1);

namespace MicroweberPackages\Template\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\Template\Export\TemplateExporter;

/**
 * Export a site built in Live Edit as a reusable, uploadable child template ZIP
 * (data + assets only, no PHP). See {@see TemplateExporter}.
 *
 *   php artisan mw:template-export "My Theme"
 *   php artisan mw:template-export "My Theme" --source=Base --parent=Base
 *   php artisan mw:template-export "My Theme" --full-site --output=/tmp/mytheme.zip
 */
class TemplateExportCommand extends Command
{
    protected $signature = 'mw:template-export
        {name : The name of the new template}
        {--source= : Source template whose site/design to export (default: current template)}
        {--parent= : parent_template for the child (default: the source template)}
        {--full-site : Export the whole site content, not just the source template pages}
        {--author= : Author name to record in config.json}
        {--output= : Write the ZIP to this path (default: storage/app/template-exports/<Name>.zip)}
        {--dir : Leave the unzipped folder only; do not build a ZIP}';

    protected $description = 'Export the current Live-Edit site as a reusable child template (ZIP, no PHP).';

    public function handle(): int
    {
        $exporter = (new TemplateExporter())
            ->setName((string) $this->argument('name'))
            ->setSource((string) ($this->option('source') ?? ''))
            ->setParent($this->option('parent') ? (string) $this->option('parent') : null)
            ->setFullSite((bool) $this->option('full-site'))
            ->setAuthor($this->option('author') ? (string) $this->option('author') : null);

        try {
            $info = $this->option('dir')
                ? $exporter->export()
                : $exporter->exportToZip($this->option('output') ? (string) $this->option('output') : null);
        } catch (\Throwable $e) {
            $this->error('Export failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info("Exported template \"{$info['name']}\" (child of {$info['parent_template']}).");
        $this->line("  pages: {$info['pages']}  posts: {$info['posts']}  products: {$info['products']}  fonts: {$info['fonts']}");
        $this->line('  design CSS: ' . $info['css_bytes'] . ' bytes');
        $this->line('  folder: ' . $info['dir']);
        if (isset($info['zip'])) {
            $this->line('  zip:    ' . $info['zip']);
        }

        return self::SUCCESS;
    }
}
