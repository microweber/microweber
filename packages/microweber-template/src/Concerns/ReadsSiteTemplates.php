<?php

namespace MicroweberPackages\Template\Concerns;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait ReadsSiteTemplates
{
    /**
     * @desc      Get the template layouts info under the layouts subdir on your active template
     *
     * @param $options
     * $options ['type'] - 'layout' is the default type if you dont define any. You can define your own types as post/form, etc in the layout.txt file
     *
     * @return array|false
     *
     * @author    Microweber Dev Team
     *
     * @since     Version 1.0
     */
    public function site_templates($options = [])
    {
        // An explicit path keeps the original single-root behaviour; otherwise
        // we scan the primary Templates/ root plus any extra drop-in roots.
        if (isset($options['path'])) {
            $roots = [$options['path']];
        } else {
            $roots = $this->getSiteTemplatesRoots();
        }

        $remove_hidden = $options['remove_hidden_from_install_screen'] ?? false;
        $templates = [];

        foreach ($roots as $path) {
            if (!is_dir($path)) {
                continue;
            }

            $map = directory_map($path, true, true);

            if (!is_array($map) || empty($map)) {
                continue;
            }

            foreach ($map as $dir) {
                $template_path = normalize_path($path . DIRECTORY_SEPARATOR . $dir);
                $template_path = rtrim(ltrim($template_path, '.'), '\\');

                if (is_file($template_path) || !is_dir($template_path)) {
                    continue;
                }

                $config = $this->get_template_config($template_path);

                if ($config && (!$remove_hidden || empty($config['is_hidden_from_install_screen']))) {
                    if (isset($config['name'])) {
                        $config['screenshot'] = $this->get_template_screenshot($config['name']);

                    }
                    $templates[] = $config;
                }
            }
        }

        if (empty($templates)) {
            return false;
        }

        return $templates;
    }

    /**
     * Resolve the list of roots scanned for site templates: the primary
     * Templates/ directory followed by any configured extra drop-in roots
     * (config('microweber.extra_templates_paths'), already filtered to existing
     * directories). Empty extra folders simply contribute nothing.
     *
     * @return array
     */
    protected function getSiteTemplatesRoots()
    {
        $roots = [templates_dir()];

        $extra = function_exists('config') ? config('microweber.extra_templates_paths', []) : [];

        if (is_array($extra)) {
            foreach ($extra as $extra_path) {
                if (is_string($extra_path) && $extra_path !== '') {
                    $roots[] = rtrim($extra_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                }
            }
        }

        return $roots;
    }

    /**
     * Get the template configuration file.
     *
     * @param string $template_path
     * @param string $dir
     * @return array|false
     */
    public function get_template_config($template_path)
    {

        $legacy_config = normalize_path($template_path, true) . 'config.php';
        $new_config = normalize_path($template_path, true) . 'config' . DS . 'config.php';
        $dir = basename($template_path);
        if (is_file($new_config)) {
            $config = include $new_config;
            $config['dir_name'] = $dir;
            $config['is_legacy'] = false;

            $config['template_type'] = 'laravel';
        } elseif (is_file($legacy_config)) {
            include $legacy_config;
            $config['dir_name'] = $dir;
            $config['is_legacy'] = true;
            $config['template_type'] = 'legacy';

            $config['is_symlink'] = is_link(normalize_path($template_path, false));
        } else {
            return false;
        }

        return $config;
    }

    /**
     * Get the screenshot file for the template.
     *
     * @param string $name
     * @return string|false
     */
    public function get_template_screenshot($activeTemplate)
    {

        $checkIfActiveSiteTemplate = app()->templates->find($activeTemplate);
        if ($checkIfActiveSiteTemplate) {
            $checkIfActiveSiteTemplateLowerName = $checkIfActiveSiteTemplate->getLowerName();

            $basePath = normalize_path(public_path());

            $jpg_screenshot = normalize_path($basePath . 'templates' . DS . $checkIfActiveSiteTemplateLowerName . DS . 'screenshot.jpg', false);
            $png_screenshot = normalize_path($basePath . 'templates' . DS . $checkIfActiveSiteTemplateLowerName . DS . 'screenshot.png', false);

            if (is_file($png_screenshot)) {
                return asset('templates/' . $checkIfActiveSiteTemplateLowerName . '/screenshot.png');
            } elseif (is_file($jpg_screenshot)) {
                return asset('templates/' . $checkIfActiveSiteTemplateLowerName . '/screenshot.jpg');
            } else {
                return false;
            }

        }

        return false;
    }
}
