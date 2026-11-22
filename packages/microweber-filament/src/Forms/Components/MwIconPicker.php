<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\TextInput;
use Closure;
use Filament\Support\Contracts\HasLabel as LabelInterface;
use Illuminate\Contracts\Support\Arrayable;

class MwIconPicker extends TextInput
{
    protected string $view = 'mw-filament::components.mw-icon-picker';


    /**
     * @var array<string | array<string>> | Arrayable | string | Closure | null
     */
    protected array | Arrayable | string | Closure | null $iconSets = null;

    /**
     * @param  array<string | array<string>> | Arrayable | string | Closure | null  $iconSets
     */
    public function iconSets(array | Arrayable | string | Closure | null $iconSets): static
    {
        $this->iconSets = $iconSets;

        return $this;
    }

    /**
     * @return array<string | array<string>>
     */
    public function getIconSets(): array
    {
        $sets = $this->evaluate($this->iconSets) ?? [];

        // When a field declares no icon sets, fall back to the icon sets the
        // active template enables in its config (the same list that drives the
        // cached front-end icon-fonts CSS), so the picker reflects the
        // configured libraries instead of loading nothing.
        if (empty($sets) && function_exists('app') && app()->bound('template_manager')) {
            $adapter = app()->template_manager->iconFontsAdapter ?? null;
            if ($adapter && method_exists($adapter, 'getIconSetKeys')) {
                $sets = $adapter->getIconSetKeys();
            }
        }

        return $sets;
    }

    public function addIconSet(string $iconSet) {

        $this->iconSets[] = $iconSet;

        return $this;
    }
}
