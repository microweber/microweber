<?php

declare(strict_types=1);

namespace Modules\Backup\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\Template\ContentJson\ContentJsonExporter;

/**
 * Export the current site (pages, posts, products and menus) to the simplified,
 * portable content.json format consumed by the template-apply flow and the
 * ContentJsonInstaller.
 *
 * Full-site by default; pass --template to export only content whose
 * active_site_template matches (e.g. export a Big-template site, then import it
 * under another template).
 *
 * Usage:
 *   php artisan content-json:export                         # full site -> stdout path
 *   php artisan content-json:export --template=Big
 *   php artisan content-json:export --output=/tmp/site.content.json
 *   php artisan content-json:export --template=Alfa --to-template=Alfa  # write into Templates/Alfa/content.json
 */
class ContentJsonExportCommand extends Command
{
    protected $signature = 'content-json:export
        {--template= : Only export content with this active_site_template (default: full site)}
        {--output= : File path to write (default: storage/app/content-export.json)}
        {--to-template= : Write into Templates/<name>/content.json (the template seed location)}
        {--no-products : Exclude products}
        {--no-posts : Exclude posts}
        {--no-menus : Exclude menus}';

    protected $description = 'Export the site to the simplified content.json format (full site or a single template).';

    public function handle(): int
    {
        $exporter = new ContentJsonExporter();
        $exporter->setTemplate($this->option('template') ?: null)
            ->setIncludeProducts(!$this->option('no-products'))
            ->setIncludePosts(!$this->option('no-posts'))
            ->setIncludeMenus(!$this->option('no-menus'));

        $payload = $exporter->export();

        if ($toTemplate = $this->option('to-template')) {
            $output = rtrim(templates_dir(), '/\\') . DIRECTORY_SEPARATOR . $toTemplate . DIRECTORY_SEPARATOR . 'content.json';
        } else {
            $output = $this->option('output') ?: storage_path('app/content-export.json');
        }

        $bytes = file_put_contents($output, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        if ($bytes === false) {
            $this->error("Could not write to {$output}");
            return self::FAILURE;
        }

        $this->info('Exported content.json:');
        $this->line('  file:     ' . $output . ' (' . $bytes . ' bytes)');
        $this->line('  pages:    ' . count($payload['pages'] ?? []));
        $this->line('  posts:    ' . count($payload['posts'] ?? []));
        $this->line('  products: ' . count($payload['products'] ?? []));
        $this->line('  menus:    ' . count($payload['menus'] ?? []));

        return self::SUCCESS;
    }
}
