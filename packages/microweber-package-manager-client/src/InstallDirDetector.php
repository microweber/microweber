<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient;

use MicroweberPackages\PackageManagerClient\Exceptions\PackageManagerException;
use MicroweberPackages\PackageManagerClient\Support\FilesystemHelper;

/**
 * Detects whether a package is a module, template, or nwidart Laravel module
 * and resolves the correct install directory.
 *
 * Supported types:
 *  - microweber-module  → {modules_path}/{TargetDir}/
 *  - microweber-template → {templates_path}/{TargetDir}/
 *  - laravel-module / nwidart-module / packages with module.json markers
 *                       → {modules_path}/{StudlyName}/
 *  - library            → {vendor_path}/{vendor}/{name}/  (optional)
 *
 * Directory name resolution order:
 *  1. package["target-dir"]
 *  2. package["extra"]["folder"]
 *  3. package["extra"]["laravel-module"]["name"]
 *  4. StudlyCase of the package name short part (after /)
 */
final class InstallDirDetector
{
    public const TYPE_MODULE = 'microweber-module';
    public const TYPE_TEMPLATE = 'microweber-template';
    public const TYPE_LARAVEL_MODULE = 'laravel-module';
    public const TYPE_NWIDART_MODULE = 'nwidart-module';
    public const TYPE_LIBRARY = 'library';

    private string $basePath;
    private string $modulesPath;
    private string $templatesPath;
    private string $vendorPath;

    /**
     * @param array{
     *     base_path?: string,
     *     modules_path?: string,
     *     templates_path?: string,
     *     vendor_path?: string
     * } $config
     */
    public function __construct(array $config = [])
    {
        $base = $config['base_path'] ?? null;
        if (!is_string($base) || $base === '') {
            $base = function_exists('base_path') ? base_path() : (getcwd() ?: '.');
        }
        $this->basePath = rtrim((string) $base, '/\\');
        $this->modulesPath = (string) ($config['modules_path'] ?? 'Modules');
        $this->templatesPath = (string) ($config['templates_path'] ?? 'Templates');
        $this->vendorPath = (string) ($config['vendor_path'] ?? 'vendor');
    }

    /**
     * @param array<string, mixed> $package Composer package metadata
     */
    public function detect(array $package): InstallTarget
    {
        $packageName = isset($package['name']) && is_string($package['name'])
            ? $package['name']
            : '';

        if ($packageName === '') {
            throw PackageManagerException::cannotDetermineInstallDir('(unknown)');
        }

        $type = $this->resolveType($package);
        $directory = $this->resolveDirectory($package, $type);

        if ($directory === '') {
            throw PackageManagerException::cannotDetermineInstallDir($packageName);
        }

        // Sanitize directory (no traversal)
        $directory = $this->sanitizeDirectoryName($directory);
        if ($directory === '') {
            throw PackageManagerException::cannotDetermineInstallDir($packageName);
        }

        $baseRelative = match ($type) {
            self::TYPE_TEMPLATE => $this->templatesPath,
            self::TYPE_LIBRARY => $this->vendorPath . '/' . $packageName,
            default => $this->modulesPath, // modules, nwidart, laravel-module
        };

        if ($type === self::TYPE_LIBRARY) {
            $relativePath = $baseRelative;
            $absolutePath = FilesystemHelper::resolvePath($relativePath, $this->basePath);
        } else {
            $relativePath = trim($baseRelative, '/\\') . '/' . $directory;
            $absolutePath = FilesystemHelper::resolvePath($baseRelative, $this->basePath)
                . DIRECTORY_SEPARATOR . $directory;
        }

        return new InstallTarget(
            type: $type,
            directory: $directory,
            relativePath: str_replace('\\', '/', $relativePath),
            absolutePath: $absolutePath,
            packageName: $packageName,
        );
    }

    /**
     * @param array<string, mixed> $package
     */
    public function resolveType(array $package): string
    {
        $type = isset($package['type']) && is_string($package['type'])
            ? strtolower($package['type'])
            : '';

        if (in_array($type, [
            self::TYPE_MODULE,
            self::TYPE_TEMPLATE,
            self::TYPE_LARAVEL_MODULE,
            self::TYPE_NWIDART_MODULE,
        ], true)) {
            return $type;
        }

        // Heuristics for nwidart / generic Laravel modules (even when type is library)
        if ($this->looksLikeNwidartModule($package)) {
            return self::TYPE_LARAVEL_MODULE;
        }

        if ($type === self::TYPE_LIBRARY) {
            return self::TYPE_LIBRARY;
        }

        $name = isset($package['name']) && is_string($package['name']) ? $package['name'] : '';
        if (str_contains($name, 'microweber-templates/') || str_contains($name, 'template')) {
            if ($type === '' || str_contains($type, 'template')) {
                return self::TYPE_TEMPLATE;
            }
        }
        if (str_contains($name, 'microweber-modules/') || str_contains($name, 'module')) {
            return self::TYPE_MODULE;
        }

        if ($type !== '') {
            // Unknown but declared type — treat as module if name suggests modules path
            return $type;
        }

        return self::TYPE_MODULE;
    }

    /**
     * @param array<string, mixed> $package
     */
    public function resolveDirectory(array $package, ?string $type = null): string
    {
        $type ??= $this->resolveType($package);

        // 1. target-dir (Composer / Microweber convention)
        if (isset($package['target-dir']) && is_string($package['target-dir']) && $package['target-dir'] !== '') {
            return $package['target-dir'];
        }

        // 2. extra.folder
        $extra = isset($package['extra']) && is_array($package['extra']) ? $package['extra'] : [];
        if (isset($extra['folder']) && is_string($extra['folder']) && $extra['folder'] !== '') {
            return $extra['folder'];
        }

        // 3. extra.laravel-module.name / extra.nwidart.name
        foreach (['laravel-module', 'nwidart', 'module'] as $key) {
            if (isset($extra[$key]) && is_array($extra[$key])
                && isset($extra[$key]['name']) && is_string($extra[$key]['name'])
                && $extra[$key]['name'] !== '') {
                return $this->studly($extra[$key]['name']);
            }
        }

        // 4. Derive from package name
        $name = isset($package['name']) && is_string($package['name']) ? $package['name'] : '';
        $short = $name;
        if (str_contains($name, '/')) {
            $parts = explode('/', $name, 2);
            $short = $parts[1] ?? $parts[0];
        }

        // Strip conventional suffixes
        if ($type === self::TYPE_TEMPLATE) {
            $short = (string) preg_replace('/-template$/i', '', $short);
        } elseif (in_array($type, [self::TYPE_MODULE, self::TYPE_LARAVEL_MODULE, self::TYPE_NWIDART_MODULE], true)) {
            $short = (string) preg_replace('/-module$/i', '', $short);
        }

        return $this->studly($short);
    }

    /**
     * Whether the package looks like a nwidart/laravel-modules package.
     *
     * @param array<string, mixed> $package
     */
    public function looksLikeNwidartModule(array $package): bool
    {
        $extra = isset($package['extra']) && is_array($package['extra']) ? $package['extra'] : [];

        if (isset($extra['laravel-module']) || isset($extra['nwidart'])) {
            return true;
        }

        // Autoload PSR-4 under Modules\
        $autoload = isset($package['autoload']) && is_array($package['autoload']) ? $package['autoload'] : [];
        $psr4 = isset($autoload['psr-4']) && is_array($autoload['psr-4']) ? $autoload['psr-4'] : [];
        foreach (array_keys($psr4) as $ns) {
            if (is_string($ns) && str_starts_with($ns, 'Modules\\')) {
                return true;
            }
        }

        // Keywords
        $keywords = isset($package['keywords']) && is_array($package['keywords']) ? $package['keywords'] : [];
        foreach ($keywords as $kw) {
            if (is_string($kw) && in_array(strtolower($kw), ['nwidart', 'laravel-module', 'laravel-modules'], true)) {
                return true;
            }
        }

        return false;
    }

    public function isModuleType(string $type): bool
    {
        return in_array($type, [
            self::TYPE_MODULE,
            self::TYPE_LARAVEL_MODULE,
            self::TYPE_NWIDART_MODULE,
        ], true);
    }

    public function isTemplateType(string $type): bool
    {
        return $type === self::TYPE_TEMPLATE;
    }

    private function studly(string $value): string
    {
        $value = str_replace(['-', '_', '/'], ' ', $value);
        $value = ucwords($value);

        return str_replace(' ', '', $value);
    }

    private function sanitizeDirectoryName(string $directory): string
    {
        $directory = str_replace(['\\', "\0"], ['/', ''], $directory);
        $directory = trim($directory, '/');

        // Reject traversal
        if ($directory === '' || str_contains($directory, '..')) {
            return '';
        }

        // Only allow a single path segment for safety (module/template folder name)
        $parts = explode('/', $directory);
        $safe = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '' || $part === '.' || $part === '..') {
                continue;
            }
            // Allow alnum, dash, underscore, dots
            $clean = preg_replace('/[^A-Za-z0-9._-]/', '', $part);
            if (is_string($clean) && $clean !== '') {
                $safe[] = $clean;
            }
        }

        // For modules/templates we only want the final folder name
        if ($safe === []) {
            return '';
        }

        return $safe[count($safe) - 1];
    }
}
