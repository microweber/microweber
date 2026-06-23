<?php

namespace MicroweberPackages\Config;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;

class ConfigRepository extends Repository
{
    /**
     * Laravel App Instance
     * @var Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $beforeSave = [];

    /**
     * New keys for save
     * @var array
     */
    protected $changedKeys = [];

    /**
     * Static cache for config values
     * @var array
     */
    protected static $configCache = [];

    /**
     * Whether multi-site env directory was detected
     * @var bool
     */
    protected $isMultisite = false;

    public function __construct($app)
    {
        $this->app = $app;

        // Get the current config items from the existing config instance
        $items = [];
        if ($app->bound('config')) {
            $existingConfig = $app->make('config');
            if ($existingConfig instanceof Repository) {
                $items = $existingConfig->all();
            } else {
                $items = (array) $existingConfig;
                $items = end($items);
                if (!is_array($items)) {
                    $items = [];
                }
            }
        }

        parent::__construct($items);

        $this->init();
    }

    /**
     * Load config files from the default config directory and
     * any environment-specific subdirectory.
     *
     * This is the core feature: if a subdirectory matching the current
     * environment name exists under config/, those files are loaded on top
     * of the base config, enabling per-site configuration overrides.
     *
     * The base config items (already loaded by Laravel's bootstrapper)
     * are preserved and only overridden by environment-specific files.
     */
    private function init(): bool
    {
        // Keep whatever the framework already loaded (base config files)
        $existingItems = $this->items;
        $this->items = [];

        $defaultDir = $this->app->configPath();
        $envDir = $defaultDir . DIRECTORY_SEPARATOR . $this->app->environment();

        // Load base config files
        $dirs = [];
        $dirs[] = $defaultDir;

        if (is_dir($envDir)) {
            $this->isMultisite = true;
            $dirs[] = $envDir;
        }

        foreach ($dirs as $dir) {
            $this->loadConfigFilesFromDirectory($dir);
        }

        // Merge: items loaded from files take priority, but we keep
        // any keys from the existing config that we didn't re-load
        // (e.g. vendor-published configs that the framework merged in)
        $this->items = array_replace($existingItems, $this->items);

        return true;
    }

    /**
     * Load all PHP config files from a directory.
     */
    private function loadConfigFilesFromDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $parts = explode('.', $file);
            $extension = end($parts);
            $key = reset($parts);

            if ($key !== '' && $extension === 'php') {
                $this->set($key, require $dir . DIRECTORY_SEPARATOR . $file);
            }
        }
    }

    /**
     * Set a given configuration value and track the change.
     *
     * @param array|string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value = null)
    {
        // Track changes for save(). Mirror the parent's array/string handling so
        // the array form Config::set(['a.b' => 'c']) is tracked too (not just the
        // string form) — otherwise a later save() would silently drop those keys.
        $changes = is_array($key) ? $key : [$key => $value];
        foreach ($changes as $changedKey => $changedValue) {
            if ($changedKey !== '' && $changedKey !== null && $changedValue !== null) {
                $this->changedKeys[$changedKey] = $changedValue;
            }
        }
        self::$configCache = [];
        parent::set($key, $value);
    }

    /**
     * Get a configuration value with caching.
     *
     * @param array|string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        // Only cache scalar (string-key) lookups, and only when the key actually
        // exists — caching a returned $default under a missing key would pollute
        // later get($key) (no default) calls into returning the stale default
        // instead of null. array_key_exists (not isset) so cached null hits too.
        if (is_string($key) && array_key_exists($key, self::$configCache)) {
            return self::$configCache[$key];
        }
        $value = parent::get($key, $default);
        if (is_string($key) && parent::has($key)) {
            self::$configCache[$key] = $value;
        }
        return $value;
    }

    /**
     * Save changed config values back to PHP files.
     *
     * Writes to the environment-specific subdirectory if it exists,
     * otherwise writes to the base config directory.
     *
     * All absolute paths for storage_path() and database_path() are
     * automatically converted to relative helper calls so the config
     * files remain portable across different installations.
     *
     * @param array|string $allowed List of config file keys to save (empty = save all)
     */
    public function save($allowed = [])
    {
        self::$configCache = [];

        // Aggregate changed keys into per-file groups
        $aggregated = [];
        foreach ($this->changedKeys as $key => $value) {
            $this->dotSet($aggregated, $key, $value);
        }

        // Prepare allowed list
        if (is_string($allowed)) {
            $allowed = array_filter(explode(',', $allowed));
        }

        foreach ($aggregated as $file => $items) {
            // Skip files not in the allowed list (when a filter is provided)
            if (!empty($allowed) && !in_array($file, $allowed, true)) {
                continue;
            }

            // Determine target directory: env-specific if it exists, else base config
            $path = $this->getSaveDirectory();

            if (!is_dir($path)) {
                @mkdir($path, 0755, true);
            }

            $filePath = $path . $file . '.php';

            if (!isset($this->items[$file])) {
                continue;
            }

            $exported = var_export($this->items[$file], true);

            // Convert absolute paths to relative helper calls
            $exported = $this->convertAbsolutePathsToRelative($exported);

            $code = '<?php return ' . $exported . ';' . PHP_EOL;

            // Normalize directory separators
            $filePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filePath);
            $filePath = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $filePath);

            file_put_contents($filePath, $code);
        }
    }

    /**
     * Get the directory where config files should be saved.
     * Uses env-specific subdirectory if it exists.
     */
    protected function getSaveDirectory(): string
    {
        $envPath = $this->app->configPath() . DIRECTORY_SEPARATOR . $this->app->environment() . DIRECTORY_SEPARATOR;

        if (is_dir($envPath)) {
            return $envPath;
        }

        return $this->app->configPath() . DIRECTORY_SEPARATOR;
    }

    /**
     * Convert absolute storage_path and database_path references in an
     * exported config string into portable helper function calls.
     */
    protected function convertAbsolutePathsToRelative(string $exported): string
    {
        // Replace storage_path references
        $storagePath = $this->getStoragePath();
        if ($storagePath) {
            $exported = $this->replaceAbsolutePath($exported, $storagePath, 'storage_path');
        }

        // Replace database_path references
        $databasePath = $this->getDatabasePath();
        if ($databasePath) {
            $exported = $this->replaceAbsolutePath($exported, $databasePath, 'database_path');
        }

        // Replace base_path references
        $basePath = $this->getBasePath();
        if ($basePath) {
            $exported = $this->replaceAbsolutePath($exported, $basePath, 'base_path');
        }

        return $exported;
    }

    /**
     * Replace absolute path occurrences with a helper function call.
     */
    protected function replaceAbsolutePath(string $exported, string $absolutePath, string $helper): string
    {
        // Normalize with forward slashes and backslashes for cross-platform support
        $variants = [
            $absolutePath,
            str_replace('/', '\\', $absolutePath),
            str_replace('\\', '/', $absolutePath),
            str_replace('\\', '\\\\', $absolutePath),
        ];

        $variants = array_unique($variants);

        foreach ($variants as $variant) {
            // Match 'absolute_path/rest' → helper().DIRECTORY_SEPARATOR.'rest'
            // Match 'absolute_path\rest' → helper().DIRECTORY_SEPARATOR.'rest'
            // Match 'absolute_path' alone → helper()

            // With trailing separator and content after
            $exported = str_replace(
                "'" . $variant . DIRECTORY_SEPARATOR,
                $helper . "().DIRECTORY_SEPARATOR.'",
                $exported
            );
            $exported = str_replace(
                "'" . $variant . '/',
                $helper . "().DIRECTORY_SEPARATOR.'",
                $exported
            );
            $exported = str_replace(
                "'" . $variant . '\\',
                $helper . "().DIRECTORY_SEPARATOR.'",
                $exported
            );
            $exported = str_replace(
                "'" . $variant . '\\\\',
                $helper . "().DIRECTORY_SEPARATOR.'",
                $exported
            );

            // Standalone path
            $exported = str_replace(
                "'" . $variant . "'",
                $helper . "()",
                $exported
            );
        }

        return $exported;
    }

    /**
     * Get storage path, safely handling cases where the function doesn't exist.
     */
    protected function getStoragePath(): string
    {
        if (function_exists('storage_path')) {
            return storage_path();
        }

        if (method_exists($this->app, 'storagePath')) {
            return $this->app->storagePath();
        }

        return '';
    }

    /**
     * Get database path safely.
     */
    protected function getDatabasePath(): string
    {
        if (function_exists('database_path')) {
            return database_path();
        }

        if (method_exists($this->app, 'databasePath')) {
            return $this->app->databasePath();
        }

        return '';
    }

    /**
     * Get base path safely.
     */
    protected function getBasePath(): string
    {
        if (function_exists('base_path')) {
            return base_path();
        }

        if (method_exists($this->app, 'basePath')) {
            return $this->app->basePath();
        }

        return '';
    }

    /**
     * Whether a multi-site environment directory was detected.
     */
    public function isMultisite(): bool
    {
        return $this->isMultisite;
    }

    /**
     * Get the list of changed keys.
     */
    public function getChangedKeys(): array
    {
        return $this->changedKeys;
    }

    /**
     * Clear the static config cache.
     */
    public static function clearCache(): void
    {
        self::$configCache = [];
    }

    /**
     * Register a callback to run before saving a namespace.
     */
    public function beforeSaving(string $namespace, \Closure $callback): void
    {
        $this->beforeSave[$namespace] = $callback;
    }

    /**
     * Get all registered before-save callbacks.
     */
    public function getBeforeSaveCallbacks(): array
    {
        return $this->beforeSave;
    }

    /**
     * Set a value in an array using dot notation.
     * Replacement for the deprecated array_set helper.
     */
    protected function dotSet(array &$array, string $key, $value): array
    {
        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $segment = array_shift($keys);
            if (!isset($array[$segment]) || !is_array($array[$segment])) {
                $array[$segment] = [];
            }
            $array = &$array[$segment];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}