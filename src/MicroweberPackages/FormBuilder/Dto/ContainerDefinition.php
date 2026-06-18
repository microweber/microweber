<?php

namespace MicroweberPackages\FormBuilder\Dto;

class ContainerDefinition
{
    public string $type;
    public ?string $label;
    public ?string $name;
    public array $schema;
    public ?int $columns;
    public ?array $tabs;
    public bool $collapsed;
    public array $extra;

    public function __construct(array $data)
    {
        $this->type = $data['type'];
        $this->label = $data['label'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->schema = $data['schema'] ?? [];
        $this->columns = isset($data['columns']) ? (int) $data['columns'] : null;
        $this->tabs = $data['tabs'] ?? null;
        $this->collapsed = (bool) ($data['collapsed'] ?? false);

        $knownKeys = ['type', 'label', 'name', 'schema', 'columns', 'tabs', 'collapsed'];
        $this->extra = array_diff_key($data, array_flip($knownKeys));
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function isContainer(): bool
    {
        return in_array($this->type, ['section', 'grid', 'tabs', 'repeater', 'group', 'fieldset']);
    }
}
