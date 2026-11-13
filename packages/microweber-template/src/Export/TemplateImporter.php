<?php

declare(strict_types=1);

namespace MicroweberPackages\Template\Export;

use ZipArchive;

/**
 * Imports an uploaded "export as template" ZIP (see {@see TemplateExporter}).
 *
 * SECURITY: an uploaded template is untrusted. It must be pure data + assets, so
 * the importer REFUSES any archive that contains an executable file
 * (.php/.phtml/.phar/…) — uploading such a file into a scanned template dir would
 * be remote code execution. It also refuses path-traversal / absolute entries
 * (zip-slip), oversized archives, and archives without a config.json manifest.
 * Nothing is written to disk until every entry has passed validation.
 *
 * The validated template is written to a scanned, gitignored templates root
 * (ExtraTemplates/) as <Name>/, and a minimal module.json / composer.json are
 * synthesised if the archive omitted them, so the modules scanner can discover it
 * (a template dir without composer.json makes createModule() return null).
 */
class TemplateImporter
{
    /** Files that would make the template executable — always rejected. */
    private const PHP_EXT = '/\.(php|phtml|phar|php[0-9]|phps|pht|inc|phtm)$/i';

    /** Hard cap on the unzipped total size (bytes) — zip-bomb guard. */
    private int $maxTotalBytes = 80 * 1024 * 1024;

    /** Hard cap on the number of entries. */
    private int $maxEntries = 4000;

    private ?string $destRoot = null;

    public function setDestRoot(?string $dir): self
    {
        $this->destRoot = $dir ? rtrim($dir, '/\\') : null;
        return $this;
    }

    public function setMaxTotalBytes(int $bytes): self
    {
        $this->maxTotalBytes = max(1, $bytes);
        return $this;
    }

    /**
     * Validate and install an uploaded template ZIP.
     *
     * @return array{name: string, dir: string, parent_template: string, files: int}
     *
     * @throws \RuntimeException on any validation failure (nothing is written).
     */
    public function importZip(string $zipPath): array
    {
        if (!is_file($zipPath)) {
            throw new \RuntimeException('Upload not found.');
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::RDONLY) !== true) {
            throw new \RuntimeException('The uploaded file is not a valid ZIP archive.');
        }

        try {
            // --- Pass 1: validate every entry before writing a single byte. ---
            if ($zip->numFiles > $this->maxEntries) {
                throw new \RuntimeException('The template archive has too many files.');
            }

            $entries = [];        // normalized relative path => entry index
            $topDirs = [];        // distinct first path segments
            $total = 0;
            $configRaw = null;

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $name = (string) ($stat['name'] ?? '');
                if ($name === '') {
                    continue;
                }
                $isDir = str_ends_with($name, '/');

                // Reject absolute paths, drive letters and traversal (zip-slip).
                if (preg_match('#^(/|[A-Za-z]:)#', $name) || str_contains($name, '..') || str_contains($name, "\0")) {
                    throw new \RuntimeException("Unsafe path in archive: {$name}");
                }

                // Reject executable files outright (the core safety rule).
                if (!$isDir && preg_match(self::PHP_EXT, $name)) {
                    throw new \RuntimeException("The template contains a PHP/executable file, which is not allowed: {$name}");
                }

                $total += (int) ($stat['size'] ?? 0);
                if ($total > $this->maxTotalBytes) {
                    throw new \RuntimeException('The template archive is too large.');
                }

                $norm = str_replace('\\', '/', trim($name, '/'));
                if ($norm === '') {
                    continue;
                }
                $segments = explode('/', $norm);
                $topDirs[$segments[0]] = true;

                if (!$isDir) {
                    $entries[$norm] = $i;
                    // Capture the manifest (root or one level down).
                    if (preg_match('#(^|/)config\.json$#', $norm) && count($segments) <= 2) {
                        $configRaw = $zip->getFromIndex($i);
                    }
                }
            }

            if ($configRaw === null) {
                throw new \RuntimeException('The archive is not a Microweber template (no config.json manifest).');
            }
            $config = json_decode((string) $configRaw, true);
            if (!is_array($config) || empty($config['name'])) {
                throw new \RuntimeException('The template config.json is missing or has no name.');
            }

            // Archives are usually wrapped in a single top-level folder; strip it.
            $strip = (count($topDirs) === 1) ? (array_key_first($topDirs) . '/') : '';

            $name = self::studly((string) $config['name']);
            if ($name === '') {
                throw new \RuntimeException('The template name is invalid.');
            }
            $parent = (string) ($config['parent_template'] ?? 'Base') ?: 'Base';

            $root = ($this->destRoot ?: base_path('ExtraTemplates')) . '/' . $name;

            // --- Pass 2: extract (validated) entries into the dest. ---
            $this->rmrf($root);
            @mkdir($root, 0755, true);
            $written = 0;
            foreach ($entries as $rel => $idx) {
                $target = $strip !== '' && str_starts_with($rel, $strip)
                    ? substr($rel, strlen($strip))
                    : $rel;
                if ($target === '') {
                    continue;
                }
                $dest = $root . '/' . $target;

                // Final containment check — the resolved path must stay under $root.
                $destReal = $this->normalize($dest);
                $rootReal = $this->normalize($root) . '/';
                if (!str_starts_with($destReal . '/', $rootReal) && $destReal . '/' !== $rootReal) {
                    throw new \RuntimeException("Refusing to write outside the template folder: {$target}");
                }

                @mkdir(dirname($dest), 0755, true);
                $contents = $zip->getFromIndex($idx);
                if ($contents === false) {
                    continue;
                }
                file_put_contents($dest, $contents);
                $written++;
            }

            // Ensure the scanner can discover it (needs module.json + composer.json).
            $this->ensureManifests($root, $name, $parent, $config);

            // Belt-and-braces: a final on-disk sweep for any PHP file.
            foreach ($this->filesUnder($root) as $f) {
                if (preg_match(self::PHP_EXT, basename($f))) {
                    $this->rmrf($root);
                    throw new \RuntimeException('A PHP file was found after extraction; the import was rolled back.');
                }
            }

            return [
                'name' => $name,
                'dir' => $root,
                'parent_template' => $parent,
                'files' => $written,
            ];
        } finally {
            $zip->close();
        }
    }

    private function ensureManifests(string $root, string $name, string $parent, array $config): void
    {
        $alias = strtolower($name);
        if (!is_file($root . '/module.json')) {
            file_put_contents($root . '/module.json', $this->json([
                'name' => $name,
                'alias' => $alias,
                'description' => $config['description'] ?? ($name . ' — uploaded child of ' . $parent . '.'),
                'keywords' => ['template', 'child', strtolower($parent)],
                'priority' => 100,
                'providers' => [],
                'files' => [],
            ]));
        }
        if (!is_file($root . '/composer.json')) {
            file_put_contents($root . '/composer.json', $this->json([
                'name' => 'microweber-template/' . $alias,
                'description' => $config['description'] ?? ($name . ' — uploaded child of ' . $parent . '.'),
                'type' => 'microweber-template',
                'extra' => ['laravel' => ['providers' => []]],
                'target-dir' => $name,
            ]));
        }
    }

    /** @return list<string> */
    private function filesUnder(string $root): array
    {
        if (!is_dir($root)) {
            return [];
        }
        $out = [];
        foreach (new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
        ) as $f) {
            if ($f->isFile()) {
                $out[] = $f->getPathname();
            }
        }
        return $out;
    }

    private function normalize(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $parts = [];
        foreach (explode('/', $path) as $p) {
            if ($p === '' || $p === '.') {
                continue;
            }
            if ($p === '..') {
                array_pop($parts);
            } else {
                $parts[] = $p;
            }
        }
        return '/' . implode('/', $parts);
    }

    private function json(array $data): string
    {
        return (string) json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function rmrf(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $f) {
            $f->isDir() ? @rmdir($f->getPathname()) : @unlink($f->getPathname());
        }
        @rmdir($dir);
    }

    private static function studly(string $name): string
    {
        $normalized = preg_replace('/[^A-Za-z0-9]+/', '_', trim($name)) ?? $name;
        return \Illuminate\Support\Str::studly($normalized);
    }
}
