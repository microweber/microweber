<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit tool: add a field to a form module (e.g. a contact / reservation form).
 *
 * A form module's fields are CustomField definitions attached to the module
 * (rel_type = 'module', rel_id = module id). This creates one such field via the
 * fields manager so the agent can turn a plain contact form into, say, a
 * reservation form (Name, Email, Phone, Date, Time, Guests) straight from the box.
 * Call it once per field, targeting the id of the contact_form module you inserted.
 */
class AddFormFieldTool extends BaseTool
{
    protected string $domain = 'liveedit';

    /** Field types the contact-form/custom-fields UI supports. */
    private const TYPES = ['text', 'textarea', 'email', 'number', 'date', 'time', 'dropdown', 'checkbox'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'add_form_field',
            'Add a field to a form module (e.g. a contact_form) so it can collect '
            . 'more information — for example turning a contact form into a reservation '
            . 'form with Name, Email, Phone, Date, Time and Guests. Provide the module_id '
            . 'of the form, the field name (label), its type and whether it is required. '
            . 'Call once per field. Types: ' . implode(', ', self::TYPES) . '.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'module_id',
                type: PropertyType::STRING,
                description: 'The id of the form module (the contact_form you inserted) the field belongs to.',
                required: true,
            ),
            new ToolProperty(
                name: 'name',
                type: PropertyType::STRING,
                description: 'The field label, e.g. "Full name", "Phone", "Reservation date", "Number of guests".',
                required: true,
            ),
            new ToolProperty(
                name: 'type',
                type: PropertyType::STRING,
                description: 'The field type: ' . implode(', ', self::TYPES) . '. Use "email" for an email, '
                    . '"date" for a date, "number" for a count (guests), "text" for name/phone.',
                required: false,
            ),
            new ToolProperty(
                name: 'required',
                type: PropertyType::BOOLEAN,
                description: 'Whether the field must be filled in. Defaults to false.',
                required: false,
            ),
            new ToolProperty(
                name: 'options',
                type: PropertyType::STRING,
                description: 'For a "dropdown" field only: comma-separated choices, e.g. "1,2,3,4,5". Ignored otherwise.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $moduleId = trim((string) ($args['module_id'] ?? ''));
        $name = trim((string) ($args['name'] ?? ''));
        $type = strtolower(trim((string) ($args['type'] ?? 'text')));
        $required = filter_var($args['required'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $optionsRaw = trim((string) ($args['options'] ?? ''));

        if ($moduleId === '' || $name === '') {
            return $this->handleError('module_id and name are required.');
        }
        if (!in_array($type, self::TYPES, true)) {
            $type = 'text';
        }

        try {
            $fieldData = [
                'rel_type' => 'module',
                'rel_id' => $moduleId,
                'type' => $type,
                'name' => $name,
                'required' => $required ? 1 : 0,
                'is_active' => 1,
            ];

            // Dropdown/checkbox need value options.
            if (in_array($type, ['dropdown', 'checkbox'], true) && $optionsRaw !== '') {
                $fieldData['value'] = array_values(array_filter(array_map('trim', explode(',', $optionsRaw)), 'strlen'));
            }

            $saved = app('fields_manager')->save($fieldData);
            if (!$saved) {
                return $this->handleError('The field could not be saved (empty result from fields manager).');
            }

            $id = is_array($saved) ? ($saved['id'] ?? null) : $saved;

            return "OK — added \"{$name}\" ({$type}" . ($required ? ', required' : '')
                . ") to form module {$moduleId}" . ($id ? " (field #{$id})" : '') . '.';
        } catch (\Throwable $e) {
            return $this->handleError('Could not add the form field: ' . $e->getMessage());
        }
    }
}
