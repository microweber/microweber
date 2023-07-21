<?php


use Livewire\Mechanisms\ComponentRegistry;

function livewire_component_exists($class): bool
{

    try {
        $name = app(ComponentRegistry::class)->getName($class);
        return true;
    } catch (\Livewire\Exceptions\ComponentNotFoundException $th) {
        return false;
    }
}
