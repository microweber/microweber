<?php

namespace MicroweberPackages\Template\Services\DesignSystem;

/**
 * StylePackRegistry
 *
 * Manages style packs (colors, full-styles, button-styles, font-styles)
 * from the shared design-system assets and template-local assets.
 *
 * Style packs use the --mw-* CSS custom-property namespace as canonical
 * format.  Each pack is a JSON file whose "settings" array contains one
 * or more entries with a "fieldSettings.styleProperties" array.
 */
class StylePackRegistry
{
    /** @var array<string, array<int, array>> Packs grouped by category */
    protected array $packs = [];

    /** @var bool Whether the shared defaults have been loaded */
    protected bool $sharedLoaded = false;

    /**
     * Return the shared design assets directory.
     */
    public function sharedAssetsDir(): string
    {
        return dirname(__DIR__, 2) . '/resources/assets/shared-design/';
    }

    /**
     * Load the shared default style packs shipped with the package.
     */
    public function loadSharedPacks(): self
    {
        if ($this->sharedLoaded) {
            return $this;
        }

        $sharedDir = $this->sharedAssetsDir() . 'style-packs/';

        // Load each category folder
        if (is_dir($sharedDir)) {
            $categories = glob($sharedDir . '*', GLOB_ONLYDIR);
            if (is_array($categories)) {
                foreach ($categories as $catDir) {
                    $category = basename($catDir);
                    $this->loadPacksFromDirectory($catDir, $category);
                }
            }
        }

        $this->sharedLoaded = true;
        return $this;
    }

    /**
     * Load style packs from a JSON file into a category.
     */
    public function loadPacksFromFile(string $filePath, string $category = 'colors'): self
    {
        if (!is_file($filePath)) {
            return $this;
        }

        $content = @file_get_contents($filePath);
        if ($content === false) {
            return $this;
        }

        $decoded = @json_decode($content, true);
        if (!is_array($decoded)) {
            return $this;
        }

        // Support both formats: direct settings array and wrapper object
        $settings = [];
        if (isset($decoded['settings']) && is_array($decoded['settings'])) {
            $settings = $decoded['settings'];
        } elseif (isset($decoded[0])) {
            $settings = $decoded;
        }

        foreach ($settings as $setting) {
            if ($this->isValidPack($setting)) {
                $this->registerPack($setting, $category);
            }
        }

        return $this;
    }

    /**
     * Load all style pack JSON files from a directory into a category.
     */
    public function loadPacksFromDirectory(string $dirPath, string $category = 'colors'): self
    {
        $dirPath = rtrim($dirPath, '/\\');
        if (!is_dir($dirPath)) {
            return $this;
        }

        $files = glob($dirPath . DIRECTORY_SEPARATOR . '*.json');
        if (!is_array($files)) {
            return $this;
        }

        foreach ($files as $file) {
            $this->loadPacksFromFile($file, $category);
        }

        return $this;
    }

    /**
     * Register a single style pack into a category.
     */
    public function registerPack(array $pack, string $category = 'colors'): self
    {
        if (!$this->isValidPack($pack)) {
            return $this;
        }

        $title = $pack['title'] ?? '';

        if (!isset($this->packs[$category])) {
            $this->packs[$category] = [];
        }

        // De-duplicate by title within category
        foreach ($this->packs[$category] as $i => $existing) {
            if (($existing['title'] ?? '') === $title) {
                $this->packs[$category][$i] = $pack;
                return $this;
            }
        }

        $this->packs[$category][] = $pack;
        return $this;
    }

    /**
     * Get all packs for a given category.
     *
     * @return array<int, array>
     */
    public function getByCategory(string $category): array
    {
        return $this->packs[$category] ?? [];
    }

    /**
     * Get all registered packs across all categories.
     *
     * @return array<string, array<int, array>>
     */
    public function all(): array
    {
        return $this->packs;
    }

    /**
     * Get the list of registered categories.
     *
     * @return string[]
     */
    public function categories(): array
    {
        return array_keys($this->packs);
    }

    /**
     * Find a pack by title within a category.
     */
    public function findByTitle(string $title, string $category = 'colors'): ?array
    {
        foreach ($this->getByCategory($category) as $pack) {
            if (($pack['title'] ?? '') === $title) {
                return $pack;
            }
        }
        return null;
    }

    /**
     * Extract the flat properties map from a pack entry.
     * Returns [cssVar => value, ...] from the first styleProperty.
     *
     * @return array<string, string>
     */
    public function extractProperties(array $pack): array
    {
        if (isset($pack['fieldSettings']['styleProperties'][0]['properties'])) {
            return $pack['fieldSettings']['styleProperties'][0]['properties'];
        }
        return [];
    }

    /**
     * Reset all packs (useful for testing).
     */
    public function reset(): self
    {
        $this->packs = [];
        $this->sharedLoaded = false;
        return $this;
    }

    /**
     * Validate that a pack has the expected structure.
     */
    public function isValidPack(array $pack): bool
    {
        if (empty($pack['title'])) {
            return false;
        }

        // Must have fieldSettings.styleProperties with at least one entry
        if (!isset($pack['fieldSettings']['styleProperties'])
            || !is_array($pack['fieldSettings']['styleProperties'])
            || empty($pack['fieldSettings']['styleProperties'])) {
            return false;
        }

        // First styleProperty must have properties
        $first = $pack['fieldSettings']['styleProperties'][0];
        return isset($first['properties']) && is_array($first['properties']);
    }
}