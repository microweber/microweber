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
    public static int $position = 57;
    public static string $settingsComponent = CustomFieldsModuleSettings::class;
    public static string $templatesNamespace = 'modules.custom_fields::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        // Get parameters from request
        $for = $this->params['for'] ?? 'module';
        $forId = $this->getForId();
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

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

    protected function getForId(): string
    {
        return $this->params['content-id']
            ?? $this->params['content_id']
            ?? $this->params['for_id']
            ?? $this->params['for-id']
            ?? $this->params['rel_id']
            ?? $this->params['module-id']
            ?? $this->params['parent-module-id']
            ?? $this->params['data-id']
            ?? 0;
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
