<?php

namespace MicroweberPackages\Template\Services\DesignSystem;

/**
 * ColorSchemesRegistry
 *
 * Manages color palettes from the shared design-system assets and
 * any template-local overrides. Palettes use the canonical property
 * names (--primaryColor, --links, etc.) from the legacy Big-template
 * color-palettes.json format.
 */
class ColorSchemesRegistry
{
    /** @var array<int, array> Registered palettes */
    protected array $palettes = [];

    /** @var bool Whether the shared defaults have been loaded */
    protected bool $sharedLoaded = false;

    /**
     * Return the directory that contains the shared design-system assets
     * shipped with the Template package itself.
     */
    public function sharedAssetsDir(): string
    {
        return dirname(__DIR__, 2) . '/resources/assets/shared-design/';
    }

    /**
     * Load the shared default palettes shipped with the package.
     */
    public function loadSharedPalettes(): self
    {
        if ($this->sharedLoaded) {
            return $this;
        }

        $file = $this->sharedAssetsDir() . 'color-schemes/default-palettes.json';
        $this->loadPalettesFromFile($file);
        $this->sharedLoaded = true;

        return $this;
    }

    /**
     * Load palettes from a JSON file. The file must contain a JSON
     * array of palette objects, each with "name", "mainColors" and
     * "properties" keys.
     */
    public function loadPalettesFromFile(string $filePath): self
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

        foreach ($decoded as $palette) {
            if ($this->isValidPalette($palette)) {
                $this->registerPalette($palette);
            }
        }

        return $this;
    }

    /**
     * Load all palette JSON files from a directory.
     */
    public function loadPalettesFromDirectory(string $dirPath): self
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
            $this->loadPalettesFromFile($file);
        }

        return $this;
    }

    /**
     * Register a single palette.
     */
    public function registerPalette(array $palette): self
    {
        if ($this->isValidPalette($palette)) {
            // De-duplicate by name
            foreach ($this->palettes as $i => $existing) {
                if ($existing['name'] === $palette['name']) {
                    $this->palettes[$i] = $palette;
                    return $this;
                }
            }
            $this->palettes[] = $palette;
        }

        return $this;
    }

    /**
     * Get all registered palettes.
     *
     * @return array<int, array>
     */
    public function all(): array
    {
        return $this->palettes;
    }

    /**
     * Find a palette by name.
     */
    public function findByName(string $name): ?array
    {
        foreach ($this->palettes as $palette) {
            if ($palette['name'] === $name) {
                return $palette;
            }
        }
        return null;
    }

    /**
     * Return just the property names used across all palettes.
     *
     * @return string[]
     */
    public function getPropertyNames(): array
    {
        $names = [];
        foreach ($this->palettes as $palette) {
            if (isset($palette['properties']) && is_array($palette['properties'])) {
                $names = array_merge($names, array_keys($palette['properties']));
            }
        }
        return array_values(array_unique($names));
    }

    /**
     * Reset all palettes (useful for testing).
     */
    public function reset(): self
    {
        $this->palettes = [];
        $this->sharedLoaded = false;
        return $this;
    }

    /**
     * Validate that a palette array has the required keys.
     */
    public function isValidPalette(array $palette): bool
    {
        return isset($palette['name'])
            && isset($palette['mainColors'])
            && is_array($palette['mainColors'])
            && isset($palette['properties'])
            && is_array($palette['properties']);
    }
}