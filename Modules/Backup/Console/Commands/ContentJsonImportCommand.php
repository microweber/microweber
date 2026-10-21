<?php

declare(strict_types=1);

namespace Modules\Backup\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\Template\ContentJson\ContentJsonInstaller;

/**
 * Import a simplified content.json file (pages, posts, products and menus) into
 * the site. The inverse of content-json:export, and the same engine the
 * template-apply flow uses for templates that ship a content.json seed.
 *
 * Usage:
 *   php artisan content-json:import /path/to/content.json
 *   php artisan content-json:import /path/to/site.json --template=Bootstrap
 *   php artisan content-json:import /path/to/site.json --type=delete   # replace existing content first
 */
class ContentJsonImportCommand extends Command
{
    protected $signature = 'content-json:import
        {file : Path to the content.json file}
        {--template= : Apply the content under this active_site_template (default: the file\'s own values / current template)}
        {--type=full : full | delete | only_media}';

    protected $description = 'Import a simplified content.json file (pages/posts/products/menus) into the site.';

    public function handle(): int
    {
        $file = $this->argument('file');
        if (!is_file($file)) {
            $this->error("File not found: {$file}");
            return self::FAILURE;
        }

        $type = (string) $this->option('type');
        if (!in_array($type, ['full', 'delete', 'only_media'], true)) {
            $this->error("Invalid --type '{$type}' (expected full|delete|only_media)");
            return self::FAILURE;
        }

        if ($type === 'delete' && !$this->confirm('type=delete removes existing page/post/product content first. Continue?', false)) {
            $this->warn('Aborted.');
            return self::FAILURE;
        }

        $installer = new ContentJsonInstaller();
        $installer->setFile($file)
            ->setTemplate($this->option('template') ?: null)
            ->setImportType($type);

        if (!$installer->hasContent()) {
            $this->error('content.json has no pages/posts/products/menus.');
            return self::FAILURE;
        }

        $result = $installer->install();

        foreach (($result['log'] ?? []) as $line) {
            $this->line('  ' . $line);
        }
        $this->info($result['done'] ? 'Import complete.' : 'Import finished with nothing applied.');

        return ($result['done'] ?? false) ? self::SUCCESS : self::FAILURE;
    }
}
