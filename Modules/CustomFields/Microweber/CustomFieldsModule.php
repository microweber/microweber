<?php

namespace Modules\CustomFields\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\CustomFields\Filament\CustomFieldsModuleSettings;
use Modules\CustomFields\Models\CustomField;

class CustomFieldsModule extends BaseModule
{
    public static string $name = 'Custom Fields';
    public static string $module = 'custom_fields';
    public static string $icon = 'modules.custom-fields-icon';
    public static string $categories = 'forms';
    public static int $position = 100;
    public static string $settingsComponent = CustomFieldsModuleSettings::class;
    public static string $templatesNamespace = 'modules.custom_fields::templates';
    protected static bool $shouldRegisterNavigation = false;
    public function render()
    {
        $viewData = $this->getViewData();

        // Get parameters from request.
        // task-2026-06-06-cfrel — resolve the rel_type ($for) and rel_id
        // ($forId) with auto-detection so a bare <module type="custom_fields"/>
        // on a product/post/page detail page renders THAT content's fields,
        // while form-builder modules (which signal module context via
        // data-for="module" / for-id / default-fields) keep working unchanged.
        [$for, $forId] = $this->resolveRelTypeAndId();
        $skipTypes = $this->getSkipTypes();


        // Get custom fields data
        $data = app()->fields_manager->get([
            'rel_type' => $for,
            'rel_id' => $forId,
            'return_full' => true
        ]);
        if (empty($data)) {
            if (isset($this->params['default-fields'])) {
                app()->fields_manager->makeDefault($for, $forId, $this->params['default-fields']);
            }
            $data = app()->fields_manager->get([
                'rel_type' => $for,
                'rel_id' => $forId,
                'return_full' => true
            ]);

        }
        // Process fields into groups
        $fieldsGroups = $this->processFieldsIntoGroups($data);

        // Add data to view
        $viewData['for'] = $for;
        $viewData['for_id'] = $forId;
        $viewData['skip_types'] = $skipTypes;
        $viewData['fields_groups'] = $fieldsGroups;
        $viewData['form_has_upload'] = $this->checkFormHasUpload($data);



        // Get template
        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }
         if(empty($data)){
            return  view(static::$templatesNamespace.'.no-field-data', $viewData);
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

    /**
     * First non-empty value among the given param keys (null if none).
     */
    protected function firstParam(array $keys)
    {
        foreach ($keys as $key) {
            if (isset($this->params[$key]) && $this->params[$key] !== '' && $this->params[$key] !== null) {
                return $this->params[$key];
            }
        }
        return null;
    }

    /**
     * task-2026-06-06-cfrel — resolve [rel_type, rel_id] for this module.
     *
     * Order of precedence:
     *   1. Explicit rel_type via for / data-for (form-builder modules pass
     *      data-for="module") — honoured as-is.
     *   2. An injected content id (the cart-add form passes data-content-id) →
     *      content fields for that product/post/page.
     *   3. No explicit ids AND no form-builder signal (default-fields / for-id)
     *      → AUTO-DETECT the current content via content_id() so a bare
     *      <module type="custom_fields"/> dropped on a product page just works.
     *   4. Otherwise fall back to the module instance (form fields).
     *
     * Both the data- and non-data- attribute spellings are accepted so the
     * resolution is robust regardless of how the module tag was authored.
     */
    protected function resolveRelTypeAndId(): array
    {
        $for = $this->firstParam(['for', 'data-for']);
        $contentId = $this->firstParam(['content-id', 'data-content-id', 'content_id', 'data-content_id']);
        $hasFormSignal = $this->firstParam(['default-fields', 'data-default-fields', 'for-id', 'for_id']) !== null;

        if (! $for) {
            if ($contentId) {
                $for = 'content';
            } elseif (! $hasFormSignal && function_exists('content_id') && content_id()) {
                $for = 'content';
                $contentId = content_id();
            } else {
                $for = 'module';
            }
        }

        if (in_array(strtolower((string) $for), ['content', 'product', 'page', 'post'], true)) {
            $forId = $contentId
                ?? $this->firstParam(['for-id', 'for_id', 'rel_id'])
                ?? (function_exists('content_id') ? content_id() : null)
                ?? 0;
        } else {
            $forId = $this->firstParam([
                'for-id', 'for_id', 'rel_id', 'module-id', 'parent-module-id', 'data-id',
            ]) ?? 0;
        }

        return [$for, (string) $forId];
    }

    protected function getForId(): string
    {
        return (string) ($this->resolveRelTypeAndId()[1]);
    }

    protected function getSkipTypes(): array
    {
        if (!isset($this->params['data-skip-type'])) {
            return [];
        }

        $skipTypes = explode(',', $this->params['data-skip-type']);
        return array_map('trim', $skipTypes);
    }

    protected function processFieldsIntoGroups(array $data): array
    {
        $fieldsGroup = [];
        $groupI = 0;

        foreach ($data as $field) {
            if ($field['type'] == 'breakline') {
                $groupI++;
                continue;
            }

            $field['options']['field_size_class'] = template_default_field_size_option($field);

            if (isset($this->params['input_class'])) {
                $field['input_class'] = $this->params['input_class'];
            }

            $this->processFieldSize($field);

            $field['options']['field_size_mobile'] = $field['options']['field_size_mobile'] ?? 12;
            $field['options']['field_size_tablet'] = $field['options']['field_size_tablet'] ?? 12;
            $field['options']['field_size_desktop'] = $field['options']['field_size_desktop'] ?? 12;

            $fieldsGroup[$groupI][] = $field;
        }

        return $this->prepareFieldsForRendering($fieldsGroup);
    }

    protected function processFieldSize(&$field): void
    {
        if (isset($field['options']['field_size']) && is_array($field['options']['field_size'])) {
            $field['options']['field_size'] = $field['options']['field_size'][0];
            $field['options']['field_size_class'] = template_field_size_class($field['options']['field_size'][0]);
        }

        if (isset($field['options']['field_size']) && is_string($field['options']['field_size'])) {
            $field['options']['field_size_class'] = template_field_size_class($field['options']['field_size']);
        }
    }

    protected function prepareFieldsForRendering(array $fieldsGroup): array
    {
        $readyFieldsGroup = [];

        foreach ($fieldsGroup as $fieldGroupKey => $fields) {
            $readyFields = [];

            foreach ($fields as $field) {
                if (!in_array($field['type'], $this->getSkipTypes())) {
                    $field['params'] = $this->params;
                    $readyFields[] = ['html' => app()->fields_manager->make($field)];
                }
            }


            $readyFieldsGroup[$fieldGroupKey] = $readyFields;
        }

        return $readyFieldsGroup;
    }


    protected function checkFormHasUpload(array $data): bool
    {
        foreach ($data as $field) {
            if ($field['type'] == 'upload') {
                return true;
            }
        }
        return false;
    }
}
