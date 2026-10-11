<?php

namespace MicroweberPackages\Template\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MicroweberPackages\Template\TemplateManager;

class TemplatePreviewController
{
    /**
     * @var TemplateManager
     */
    protected $templateManager;

    public function __construct(TemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;
    }

    /**
     * Render template preview with customizations applied
     *
     * @param Request $request
     * @return Response
     */
    public function preview(Request $request): Response
    {
        $templateName = $request->get('preview_template');
        $layoutFile = $request->get('preview_layout', 'clean.blade.php');

        if (!$templateName) {
            return response('No template specified', 400);
        }

        // Store original template
        $originalTemplate = template_name();

        // Temporarily switch to preview template
        app()->template_manager->get_adapter('default')->setTemplateFolderName($templateName);

        // Generate preview CSS with customizations
        $customCss = $this->generateCustomCss($templateName);

        // Render the template
        $content = $this->renderTemplatePreview($templateName, $layoutFile);

        // Inject custom CSS
        if ($customCss) {
            $content = $this->injectCustomCss($content, $customCss);
        }

        // Restore original template
        app()->template_manager->get_adapter('default')->setTemplateFolderName($originalTemplate);

        return response($content)->header('X-Template-Preview', $templateName);
    }

    /**
     * Get template customization options
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomizations(Request $request): \Illuminate\Http\JsonResponse
    {
        $templateName = $request->get('template');

        if (!$templateName) {
            return response()->json(['error' => 'Template not specified'], 400);
        }

        $optionGroup = 'mw-template-' . $templateName;
        $styleOptionGroup = 'mw-template-' . $templateName . '-settings';

        $settings = [];

        // Get template settings
        $templateOptions = get_options(['option_group' => $optionGroup]);
        if ($templateOptions && is_array($templateOptions)) {
            foreach ($templateOptions as $option) {
                if (isset($option['option_key'])) {
                    $settings[$option['option_key']] = $option['option_value'];
                }
            }
        }

        // Get style settings
        $styleOptions = get_options(['option_group' => $styleOptionGroup]);
        if ($styleOptions && is_array($styleOptions)) {
            foreach ($styleOptions as $option) {
                if (isset($option['option_key'])) {
                    $settings[$option['option_key']] = $option['option_value'];
                }
            }
        }

        return response()->json([
            'template' => $templateName,
            'settings' => $settings,
        ]);
    }

    /**
     * Save template customization
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveCustomization(Request $request): \Illuminate\Http\JsonResponse
    {
        $templateName = $request->get('template');
        $key = $request->get('key');
        $value = $request->get('value');

        if (!$templateName || !$key) {
            return response()->json(['error' => 'Missing required parameters'], 400);
        }

        $optionGroup = 'mw-template-' . $templateName;

        $saveOption = [
            'option_key' => $key,
            'option_value' => $value,
            'option_group' => $optionGroup,
        ];

        save_option($saveOption);

        // Clear template cache
        \Cache::forget('template_settings_' . $templateName);

        // Regenerate CSS if needed
        if (in_array($key, ['primary_color', 'secondary_color', 'background_color', 'text_color'])) {
            $this->regenerateStylesheet($templateName);
        }

        return response()->json([
            'success' => true,
            'key' => $key,
            'value' => $value,
        ]);
    }

    /**
     * Generate custom CSS from template settings
     *
     * @param string $templateName
     * @return string
     */
    protected function generateCustomCss(string $templateName): string
    {
        $css = '';

        $optionGroup = 'mw-template-' . $templateName;
        $settings = get_options(['option_group' => $optionGroup]);

        $customizations = [];
        if ($settings && is_array($settings)) {
            foreach ($settings as $setting) {
                if (isset($setting['option_key']) && isset($setting['option_value'])) {
                    $customizations[$setting['option_key']] = $setting['option_value'];
                }
            }
        }

        // Generate CSS variables
        $css .= ':root {' . "\n";

        if (isset($customizations['primary_color'])) {
            $css .= '  --primary-color: ' . $customizations['primary_color'] . ';' . "\n";
        }

        if (isset($customizations['secondary_color'])) {
            $css .= '  --secondary-color: ' . $customizations['secondary_color'] . ';' . "\n";
        }

        if (isset($customizations['background_color'])) {
            $css .= '  --bg-color: ' . $customizations['background_color'] . ';' . "\n";
        }

        if (isset($customizations['text_color'])) {
            $css .= '  --text-color: ' . $customizations['text_color'] . ';' . "\n";
        }

        if (isset($customizations['heading_font'])) {
            $css .= '  --heading-font: ' . $customizations['heading_font'] . ', sans-serif;' . "\n";
        }

        if (isset($customizations['body_font'])) {
            $css .= '  --body-font: ' . $customizations['body_font'] . ', sans-serif;' . "\n";
        }

        if (isset($customizations['base_font_size'])) {
            $css .= '  --base-font-size: ' . $customizations['base_font_size'] . 'px;' . "\n";
        }

        $css .= '}' . "\n";

        // Apply CSS variables to body
        $css .= 'body {' . "\n";
        if (isset($customizations['background_color'])) {
            $css .= '  background-color: var(--bg-color);' . "\n";
        }
        if (isset($customizations['text_color'])) {
            $css .= '  color: var(--text-color);' . "\n";
        }
        if (isset($customizations['body_font'])) {
            $css .= '  font-family: var(--body-font);' . "\n";
        }
        if (isset($customizations['base_font_size'])) {
            $css .= '  font-size: var(--base-font-size);' . "\n";
        }
        $css .= '}' . "\n";

        // Headings
        if (isset($customizations['heading_font'])) {
            $css .= 'h1, h2, h3, h4, h5, h6 {' . "\n";
            $css .= '  font-family: var(--heading-font);' . "\n";
            $css .= '}' . "\n";
        }

        // Primary color applications
        if (isset($customizations['primary_color'])) {
            $css .= 'a, .btn-primary, .text-primary {' . "\n";
            $css .= '  color: var(--primary-color);' . "\n";
            $css .= '}' . "\n";

            $css .= '.btn-primary, .bg-primary {' . "\n";
            $css .= '  background-color: var(--primary-color);' . "\n";
            $css .= '}' . "\n";
        }

        return $css;
    }

    /**
     * Inject custom CSS into HTML content
     *
     * @param string $content
     * @param string $css
     * @return string
     */
    protected function injectCustomCss(string $content, string $css): string
    {
        $styleTag = '<style id="template-preview-customizations">' . "\n" . $css . '</style>';

        // Try to insert before </head>
        if (strpos($content, '</head>') !== false) {
            $content = str_replace('</head>', $styleTag . "\n" . '</head>', $content);
        } else {
            // Insert at the beginning if no </head> found
            $content = $styleTag . "\n" . $content;
        }

        return $content;
    }

    /**
     * Render template preview
     *
     * @param string $templateName
     * @param string $layoutFile
     * @return string
     */
    protected function renderTemplatePreview(string $templateName, string $layoutFile): string
    {
        $templateDir = templates_dir() . $templateName . DIRECTORY_SEPARATOR;

        if (!is_dir($templateDir)) {
            return '<p>Template not found: ' . htmlspecialchars($templateName) . '</p>';
        }

        // Check if it's a Laravel-style template
        $viewPath = $templateDir . 'resources/views/' . $layoutFile;

        if (file_exists($viewPath)) {
            try {
                return view('templates.' . strtolower($templateName) . '::' . str_replace('.blade.php', '', $layoutFile))
                    ->render();
            } catch (\Exception $e) {
                return '<p>Error rendering template: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        }

        // Fallback to legacy rendering
        $legacyPath = $templateDir . $layoutFile;
        if (file_exists($legacyPath)) {
            return file_get_contents($legacyPath);
        }

        return '<p>Layout file not found: ' . htmlspecialchars($layoutFile) . '</p>';
    }

    /**
     * Regenerate template stylesheet
     *
     * @param string $templateName
     * @return void
     */
    protected function regenerateStylesheet(string $templateName): void
    {
        $templateConfig = app()->template_manager->get_config();

        if (isset($templateConfig['stylesheet_compiler'])) {
            try {
                app()->template_manager->compile_css([
                    'path' => template_dir(),
                    'template_config' => $templateConfig,
                ]);
            } catch (\Exception $e) {
                // Log error but don't fail the request
                \Log::error('Failed to regenerate stylesheet: ' . $e->getMessage());
            }
        }
    }
}
