<?php

namespace MicroweberPackages\Filament\Concerns\Actions;

//from https://raw.githubusercontent.com/Maggomann/filament-only-icon-display/main/src/Domain/Tables/Traits/HasOnlyIcon.php
use Closure;
use Illuminate\Contracts\Support\Htmlable;

trait HasOnlyIcon
{
    protected ?string $hiddenLabel = null;

    protected bool $isOnlyIcon = false;

    protected bool $isOnlyIconAndTooltip = false;

    protected string|Closure|null $originTooltip = null;

    protected string|Closure|null $originLabel = null;

    public function tooltip(string|Closure|null $tooltip): static
    {
        $this->tooltip = $tooltip;
        $this->originTooltip = $tooltip;

        return $this;
    }

    public function onlyIcon(): static
    {
        return $this->onlyIconIsTrue()
            ->markLabelWithEmptyString()
            ->markTooltipWithNull();
    }

    public function onlyIconAndTooltip(): static
    {
        return $this->resetEntries()
            ->onlyIconAndTooltipAreTrue()
            ->markHiddenLabelWithLabelIfNotYetDone()
            ->markTooltipWithHiddenLabel()
            ->markTooltipWithName()
            ->markLabelWithEmptyString();
    }

    public function getHiddenLabel(): ?string
    {
        return $this->hiddenLabel;
    }

    private function onlyIconIsTrue(): static
    {
        $this->isOnlyIcon = true;

        return $this;
    }

    private function onlyIconAndTooltipAreTrue(): static
    {
        $this->isOnlyIconAndTooltip = true;

        return $this;
    }

    private function markTooltipWithHiddenLabel(): static
    {
        if (blank($this->tooltip)) {
            $this->tooltip = $this->hiddenLabel;
        }

        return $this;
    }

    private function markTooltipWithName(): static
    {
        if (blank($this->tooltip)) {
            $this->tooltip = $this->name;
        }

        return $this;
    }

    private function markTooltipWithNull(): static
    {
        $this->tooltip = null;

        return $this;
    }

    private function markHiddenLabelWithLabelIfNotYetDone(): static
    {
        if (blank($this->hiddenLabel)) {
            $this->hiddenLabel = $this->label;
        }

        return $this;
    }

    private function markLabelWithEmptyString(): static
    {
        $this->label = '';

        return $this;
    }

    public function label(string|Htmlable|Closure|null $label): static
    {
        $this->label = $label;
        $this->originLabel = $label;

        if ($this->isOnlyIconAndTooltip) {
            $this->onlyIconAndTooltip();
        }

        if ($this->isOnlyIcon) {
            $this->markLabelWithEmptyString()
                ->markTooltipWithNull();
        }

        return $this;
    }

    private function resetEntries(): static
    {
        $this->label = $this->originLabel;
        $this->tooltip = $this->originTooltip;

        return $this;
    }
}
