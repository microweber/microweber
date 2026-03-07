<?php

/**
 * Blade template migrator for Filament v5.
 * 
 * This script migrates Blade templates from Filament v3 patterns to v5 patterns.
 * 
 * Usage:
 *   php dev/rector-rules/blade-migrator.php [path]
 * 
 * Examples:
 *   php dev/rector-rules/blade-migrator.php Modules/Ai/resources/views
 *   php dev/rector-rules/blade-migrator.php src/MicroweberPackages
 */

declare(strict_types=1);

class BladeMigrator
{
    /**
     * Migration patterns: [pattern, replacement, description]
     */
    private const PATTERNS = [
        // Blade components migration
        [
            '/<x-filament-forms::components\.placeholder-image-cropped\s*\/>/',
            '<x-mw-filament::components.placeholder-image-cropped />',
            'filament-forms placeholder component'
        ],
        [
            '/<x-filament-forms::admin\.mw-tree/',
            '<x-mw-filament::admin.mw-tree',
            'filament-forms mw-tree component'
        ],
        [
            '/<x-filament-forms::sections\.section/',
            '<x-mw-filament::sections.section',
            'filament-forms section component'
        ],
        [
            '/<\/x-filament-forms::sections\.section>/',
            '</x-mw-filament::sections.section>',
            'filament-forms section closing tag'
        ],
        [
            '/<x-filament-forms::field-wrapper\.index/',
            '<x-filament::field-wrapper',
            'filament-forms field wrapper'
        ],
        [
            '/<\/x-filament-forms::field-wrapper\.index>/',
            '</x-filament::field-wrapper>',
            'filament-forms field wrapper closing'
        ],
        
        // Livewire event dispatching
        [
            '/wire:click="\$emit\s*\(\s*[\'"]([^\'"]*)[\'"]\s*\)"/',
            'wire:click="$dispatch(\'$1\')"',
            '$emit event dispatching'
        ],
        [
            '/\$emit\s*\(\s*[\'"]([^\'"]*)[\'"]\s*,\s*/',
            '$dispatch(\'$1\', ',
            '$emit with params'
        ],
        
        // Wire model defer (v3) -> model (v5, deferred by default)
        [
            '/wire:model\.defer="([^"]*)"/',
            'wire:model="$1"',
            'wire:model.defer'
        ],
    ];

    private int $filesProcessed = 0;
    private int $filesModified = 0;
    private int $totalReplacements = 0;
    private array $errors = [];

    public function migrate(string $path): void
    {
        if (!is_dir($path)) {
            $this->error("Path does not exist: {$path}");
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $this->processFile($file->getPathname());
            }
        }

        $this->report();
    }

    private function processFile(string $filepath): void
    {
        $this->filesProcessed++;

        $content = file_get_contents($filepath);
        if ($content === false) {
            $this->error("Cannot read file: {$filepath}");
            return;
        }

        $originalContent = $content;
        $fileReplacements = 0;

        foreach (self::PATTERNS as [$pattern, $replacement, $description]) {
            $count = 0;
            $content = preg_replace($pattern, $replacement, $content, -1, $count);
            
            if ($count > 0) {
                $fileReplacements += $count;
                $this->totalReplacements += $count;
                echo "  [{$description}] {$count} replacements in {$filepath}\n";
            }
        }

        if ($content !== $originalContent) {
            if (file_put_contents($filepath, $content) === false) {
                $this->error("Cannot write file: {$filepath}");
                return;
            }
            $this->filesModified++;
            echo "  ✓ Modified: {$filepath}\n";
        }
    }

    private function error(string $message): void
    {
        $this->errors[] = $message;
        echo "  ✗ Error: {$message}\n";
    }

    private function report(): void
    {
        echo "\n";
        echo "========================================\n";
        echo "Blade Migration Report\n";
        echo "========================================\n";
        echo "Files processed: {$this->filesProcessed}\n";
        echo "Files modified:  {$this->filesModified}\n";
        echo "Total replacements: {$this->totalReplacements}\n";
        
        if (!empty($this->errors)) {
            echo "\nErrors (" . count($this->errors) . "):\n";
            foreach ($this->errors as $error) {
                echo "  - {$error}\n";
            }
        }
        
        echo "\n";
    }
}

// CLI
if ($argc < 2) {
    echo "Usage: php blade-migrator.php <path>\n";
    echo "Examples:\n";
    echo "  php blade-migrator.php Modules/Ai/resources/views\n";
    echo "  php blade-migrator.php src/MicroweberPackages\n";
    exit(1);
}

$path = $argv[1];
if (!str_starts_with($path, '/')) {
    $path = getcwd() . '/' . $path;
}

$migrator = new BladeMigrator();
$migrator->migrate($path);
