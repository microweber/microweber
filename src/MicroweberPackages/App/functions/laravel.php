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

if (!function_exists('morph_name')) {
//from https://laracasts.com/discuss/channels/eloquent/getting-models-morph-class-without-an-instance
    function morph_name($morphableType)
    {
        return (new $morphableType)->getMorphClass();
    }

}
if (!function_exists('is_collection')) {

    function is_collection($var)
    {
        return is_object($var) && $var instanceof \Illuminate\Support\Collection;
    }
}
