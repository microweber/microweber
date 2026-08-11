<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Support;

/**
 * Thin wrappers around optional CMS helpers so the package works in a
 * standalone Laravel app without Microweber global functions.
 *
 * Each method falls back safely when the helper or service is absent.
 */
final class CmsHelpers
{
    public static function templateName(): string
    {
        if (function_exists('template_name')) {
            /** @var string|false|null $name */
            $name = template_name();

            return is_string($name) && $name !== '' ? $name : 'default';
        }

        return 'default';
    }

    public static function templateParent(string $templateName): string
    {
        if (function_exists('template_parent')) {
            /** @var string|false|null $parent */
            $parent = template_parent($templateName);

            return is_string($parent) && $parent !== '' ? $parent : $templateName;
        }

        return $templateName;
    }

    /**
     * @return mixed
     */
    public static function getOption(string $key, ?string $group = null): mixed
    {
        if (function_exists('get_option')) {
            return get_option($key, $group);
        }

        return null;
    }

    /**
     * @return list<array<string, mixed>>|array<string, mixed>
     */
    public static function getModuleOptions(string $optionGroup, ?string $module = null): array
    {
        if (function_exists('get_module_options')) {
            /** @var list<array<string, mixed>>|array<string, mixed>|false|null $options */
            $options = get_module_options($optionGroup, $module);

            return is_array($options) ? $options : [];
        }

        return [];
    }

    public static function saveModuleOption(string $key, mixed $value, ?string $group = null, ?string $module = null): mixed
    {
        if (function_exists('save_module_option')) {
            return save_module_option($key, $value, $group, $module);
        }

        return null;
    }

    public static function normalizePath(string $path, bool $slashIt = true): string
    {
        if (function_exists('normalize_path')) {
            return normalize_path($path, $slashIt);
        }

        $path = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
        $path = preg_replace('#' . preg_quote(DIRECTORY_SEPARATOR, '#') . '+#', DIRECTORY_SEPARATOR, $path) ?? $path;

        if ($slashIt && $path !== '' && ! str_ends_with($path, DIRECTORY_SEPARATOR)) {
            $path .= DIRECTORY_SEPARATOR;
        }

        return $path;
    }

    /**
     * Resolve the active site template repository when available.
     */
    public static function templatesRepository(): ?object
    {
        if (! function_exists('app')) {
            return null;
        }

        try {
            $app = app();
            if (is_object($app) && isset($app->templates) && is_object($app->templates)) {
                return $app->templates;
            }
        } catch (\Throwable) {
            return null;
        }

        return null;
    }

    /**
     * @return list<object>
     */
    public static function allTemplates(): array
    {
        $repo = self::templatesRepository();
        if ($repo === null || ! method_exists($repo, 'all')) {
            return [];
        }

        /** @var mixed $all */
        $all = $repo->all();

        if (! is_array($all) && ! $all instanceof \Traversable) {
            return [];
        }

        $result = [];
        foreach ($all as $template) {
            if (is_object($template)) {
                $result[] = $template;
            }
        }

        return $result;
    }

    public static function findTemplate(string $name): ?object
    {
        $repo = self::templatesRepository();
        if ($repo === null || ! method_exists($repo, 'find')) {
            return null;
        }

        /** @var mixed $found */
        $found = $repo->find($name);

        return is_object($found) ? $found : null;
    }

    public static function templateLowerName(object $template): string
    {
        if (method_exists($template, 'getLowerName')) {
            /** @var mixed $name */
            $name = $template->getLowerName();

            return is_string($name) ? $name : '';
        }

        return '';
    }

    public static function templateDisplayName(object $template): string
    {
        if (method_exists($template, 'getName')) {
            /** @var mixed $name */
            $name = $template->getName();

            return is_string($name) ? $name : '';
        }

        return self::templateLowerName($template);
    }

    public static function templatePath(object $template): string
    {
        if (method_exists($template, 'get')) {
            /** @var mixed $path */
            $path = $template->get('path');

            return is_string($path) ? $path : '';
        }

        return '';
    }

    /**
     * Current option_manager "current_template" value when available.
     */
    public static function optionCurrentTemplate(): ?string
    {
        if (! function_exists('app')) {
            return null;
        }

        try {
            $app = app();
            if (! is_object($app) || ! isset($app->option_manager) || ! is_object($app->option_manager)) {
                return null;
            }
            if (! method_exists($app->option_manager, 'get')) {
                return null;
            }
            /** @var mixed $value */
            $value = $app->option_manager->get('current_template', 'template');

            return is_string($value) && $value !== '' ? $value : null;
        } catch (\Throwable) {
            return null;
        }
    }
}
