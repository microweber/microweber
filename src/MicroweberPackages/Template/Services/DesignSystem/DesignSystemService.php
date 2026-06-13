<?php

namespace MicroweberPackages\Template\Services\DesignSystem;

use MicroweberPackages\Template\Services\DesignSystem\Adapters\BigTemplateVarsAdapter;
use MicroweberPackages\Template\Services\DesignSystem\Adapters\BootstrapTemplateVarsAdapter;
use MicroweberPackages\Template\Services\DesignSystem\Adapters\TemplateVarsAdapter;

/**
 * DesignSystemService
 *
 * Facade-level service that wires together the ColorSchemesRegistry,
 * StylePackRegistry and the per-template VarsAdapters.
 *
 * Usage:
 *   $ds = app('design_system');
 *   $palettes = $ds->colorSchemes()->all();
 *   $adapter  = $ds->adapterFor('bootstrap');
 *   $css      = $adapter->renderPaletteCss($palettes[0]['properties']);
 */
class DesignSystemService
{
    protected ColorSchemesRegistry $colorSchemes;
    protected StylePackRegistry $stylePacks;

    /** @var array<string, TemplateVarsAdapter> */
    protected array $adapters = [];

    public function __construct(ColorSchemesRegistry $colorSchemes, StylePackRegistry $stylePacks)
    {
        $this->colorSchemes = $colorSchemes;
        $this->stylePacks = $stylePacks;

        // Register built-in adapters
        $this->registerAdapter(new BigTemplateVarsAdapter());
        $this->registerAdapter(new BootstrapTemplateVarsAdapter());
    }

    /**
     * Access the color-schemes registry.
     */
    public function colorSchemes(): ColorSchemesRegistry
    {
        return $this->colorSchemes;
    }

    /**
     * Access the style-pack registry.
     */
    public function stylePacks(): StylePackRegistry
    {
        return $this->stylePacks;
    }

    /**
     * Register a template vars adapter.
     */
    public function registerAdapter(TemplateVarsAdapter $adapter): self
    {
        $this->adapters[strtolower($adapter->templateName())] = $adapter;
        return $this;
    }

    /**
     * Get the adapter for a template by name.
     * Falls back to the Big adapter if the template is unknown.
     */
    public function adapterFor(string $templateName): TemplateVarsAdapter
    {
        $key = strtolower($templateName);
        return $this->adapters[$key] ?? $this->adapters['big'];
    }

    /**
     * Get all registered adapter names.
     *
     * @return string[]
     */
    public function registeredAdapters(): array
    {
        return array_keys($this->adapters);
    }

    /**
     * Resolve the adapter for the currently-active template.
     */
    public function activeAdapter(): TemplateVarsAdapter
    {
        $templateName = $this->resolveActiveTemplateName();
        return $this->adapterFor($templateName);
    }

    /**
     * Get color palettes formatted for the blade template's
     * fieldSettings.colors array, with properties already mapped
     * to the given template's CSS var namespace.
     *
     * @return array<int, array{name: string, mainColors: array, properties: array}>
     */
    public function getPalettesForTemplate(string $templateName): array
    {
        $adapter = $this->adapterFor($templateName);

        // Base catalog: the shared, template-agnostic palettes (available everywhere).
        $palettes = $this->colorSchemes->all();

        // Plus the active template's OWN folder palettes (back-compat: e.g. Big ships
        // 134 in resources/assets/color-palettes.json). Merge + de-dupe by name so a
        // template never loses its bespoke palettes when the shared catalog loads.
        $palettes = $this->withTemplateFolderPalettes($palettes, $templateName);

        $result = [];
        foreach ($palettes as $palette) {
            if (!isset($palette['properties']) || !is_array($palette['properties'])) {
                continue;
            }
            $mappedProps = $adapter->mapPaletteToVars($palette['properties']);
            $result[] = [
                'name'       => $palette['name'] ?? '',
                'mainColors' => $palette['mainColors'] ?? [],
                'properties' => $mappedProps,
            ];
        }

        return $result;
    }

    /**
     * Merge a template's own folder palettes into the given base list,
     * de-duplicated by name (base/shared catalog wins on a name clash).
     *
     * Loads from:
     *   Templates/<Name>/resources/assets/color-palettes.json
     *
     * @param  array<int, array> $base
     * @return array<int, array>
     */
    protected function withTemplateFolderPalettes(array $base, string $templateName): array
    {
        $baseDir = $this->templatesBaseDir();
        if ($templateName === '' || $baseDir === null) {
            return $base;
        }

        $file = rtrim($baseDir, '/\\') . DIRECTORY_SEPARATOR . $templateName
            . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'assets'
            . DIRECTORY_SEPARATOR . 'color-palettes.json';

        if (!is_file($file) || !is_readable($file)) {
            return $base;
        }

        $registry = new ColorSchemesRegistry();
        $registry->loadPalettesFromFile($file);

        $seen = [];
        foreach ($base as $p) {
            if (isset($p['name'])) {
                $seen[$p['name']] = true;
            }
        }
        foreach ($registry->all() as $p) {
            if (isset($p['name']) && !isset($seen[$p['name']])) {
                $base[] = $p;
                $seen[$p['name']] = true;
            }
        }

        return $base;
    }

    /**
     * Get style packs for a category, with properties mapped to the
     * given template's CSS var namespace.
     *
     * @return array<int, array>
     */
    public function getStylePacksForTemplate(string $templateName, string $category = 'colors'): array
    {
        $adapter = $this->adapterFor($templateName);
        $packs = $this->stylePacks->getByCategory($category);
        $result = [];

        foreach ($packs as $pack) {
            $mapped = $pack;
            if (isset($pack['fieldSettings']['styleProperties'])) {
                foreach ($pack['fieldSettings']['styleProperties'] as $i => $sp) {
                    if (isset($sp['properties'])) {
                        $mapped['fieldSettings']['styleProperties'][$i]['properties'] =
                            $adapter->mapStylePackToVars($sp['properties']);
                    }
                }
            }
            $result[] = $mapped;
        }

        return $result;
    }

    /**
     * Resolve the templates base directory (the dir that holds the per-template
     * folders). Returns null when the framework helper is unavailable
     * (e.g. no-boot unit tests) so the folder-merge is simply skipped.
     *
     * Overridable so unit tests can point the merge at a real templates dir
     * without booting the framework.
     */
    protected function templatesBaseDir(): ?string
    {
        if (function_exists('templates_dir')) {
            return templates_dir();
        }
        return null;
    }

    /**
     * Resolve the active template dir name from the framework.
     */
    protected function resolveActiveTemplateName(): string
    {
        if (function_exists('app') && app()->bound('template_manager')) {
            $config = app()->template_manager->get_config();
            if (is_array($config) && !empty($config['dir_name'])) {
                return strtolower($config['dir_name']);
            }
        }
        return 'big';
    }
}