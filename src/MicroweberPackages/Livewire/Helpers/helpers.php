<?php


function livewire_component_exists($class): bool
{
    try {
        \Livewire\Livewire::getClass($class);
        return true;
    } catch (\Throwable $th) {
        return false;
    }
}
