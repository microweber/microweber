<?php

namespace MicroweberPackages\FormBuilder\Dto;

class FieldDefinition
{
    public string $name;
    public string $type;
    public ?string $label;
    public ?string $placeholder;
    public mixed $default;
    public ?string $help;
    public bool $required;
    public array $options;
    public array $validation;
    public ?array $visibleWhen;
    public ?int $columns;
    public bool $live;
    public bool $translatable;
    public ?string $columnSpan;
    public array $extra;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->type = $data['type'] ?? 'text';
        $this->label = $data['label'] ?? null;
        $this->placeholder = $data['placeholder'] ?? null;
        $this->default = array_key_exists('default', $data) ? $data['default'] : null;
        $this->help = $data['help'] ?? null;
        $this->required = (bool) ($data['required'] ?? false);
        $this->options = $data['options'] ?? [];
        $this->validation = $data['validation'] ?? [];
        $this->visibleWhen = $data['visible_when'] ?? null;
        $this->columns = isset($data['columns']) ? (int) $data['columns'] : null;
        $this->live = (bool) ($data['live'] ?? true);
        $this->translatable = (bool) ($data['translatable'] ?? false);
        $this->columnSpan = $data['column_span'] ?? null;

        // Store any extra keys not explicitly handled
        $knownKeys = [
            'name', 'type', 'label', 'placeholder', 'default', 'help',
            'required', 'options', 'validation', 'visible_when', 'columns',
            'live', 'translatable', 'column_span', 'schema', 'tabs',
        ];
        $this->extra = array_diff_key($data, array_flip($knownKeys));
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'label' => $this->label,
            'placeholder' => $this->placeholder,
            'default' => $this->default,
            'help' => $this->help,
            'required' => $this->required,
            'options' => $this->options,
            'validation' => $this->validation,
            'visible_when' => $this->visibleWhen,
            'columns' => $this->columns,
            'live' => $this->live,
            'translatable' => $this->translatable,
            'column_span' => $this->columnSpan,
        ];
    }
}
