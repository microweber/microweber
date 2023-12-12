@php

    $type = $formItem['type'] ?? 'text';
    $placeholder = $formItem['placeholder'] ?? '';
    $label = $formItem['label'] ?? false;
    $help = $formItem['help'] ?? '';
    $required = $formItem['required'] ?? false;
    $autocomplete = $formItem['autocomplete'] ?? false;
    $options = $formItem['options'] ?? false;
    $select = $formItem['select'] ?? false;
    $settingsKey = 'settings.' . $formItemKey;

    $fieldType = $type;


    $element = $formBuilder->make($fieldType, $settingsKey,$this->moduleId);
     $element->setAttribute('wire:model.debounce.500ms', $settingsKey);
    $element->setAttribute('module', $this->moduleType);

    if ($label and method_exists($element, 'label')) {
        $element->label($label);
    }
    if ($placeholder and method_exists($element, 'placeholder')) {
        $element->placeholder($placeholder);
    }
    if ($autocomplete and method_exists($element, 'autocomplete')) {
        $element->autocomplete($autocomplete);
    }
    if ($required and method_exists($element, 'required')) {
        $element->required($required);
    }
    if ($options and method_exists($element, 'options')) {
        $element->options($options);
    }
    if ($select and method_exists($element, 'select')) {
        $element->select($select);
    }

    print $element->render();
@endphp
