<?php


function livewire_component_exists($alias): bool
{

    $registry = app(\Livewire\Mechanisms\ComponentRegistry::class);

    if ($registry->getName($alias)) {
        return true;
    }

    return false;
}
