<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\TextInput;
use Closure;
use Filament\Support\Contracts\HasLabel as LabelInterface;
use Illuminate\Contracts\Support\Arrayable;

class MwIconPicker extends TextInput
{
    protected string $view = 'filament-forms::components.mw-icon-picker';


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
        return $this->evaluate($this->iconSets) ?? [];
    }

    public function addIconSet(string $iconSet) {

        $this->iconSets[] = $iconSet;

        return $this;
    }
}
