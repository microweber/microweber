<?php


use Livewire\Exceptions\ComponentNotFoundException;

function livewire_component_exists($alias): bool
{

    $registry = app(\Livewire\Mechanisms\ComponentRegistry::class);
    try {
        if ($registry->getName($alias)) {
            return true;
        }
    } catch (ComponentNotFoundException $e) {
        return false;
    }


    return false;
}


//from https://laracasts.com/discuss/channels/eloquent/getting-models-morph-class-without-an-instance
function morph_name($morphableType){
    return (new $morphableType)->getMorphClass();
}
