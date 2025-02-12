<?php

namespace MicroweberPackages\Filament\Forms\Components;


use Error;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Concerns\CanBeValidated;
use Filament\Forms\Components\Concerns\HasChildComponents;
use Filament\Forms\Components\Concerns\HasHelperText;
use Filament\Forms\Components\Concerns\HasHint;
use Filament\Forms\Components\Concerns\HasLabel;

class MwInputSliderGroup extends Component
{
    use CanBeValidated;
    use HasChildComponents;
    use HasHelperText;
    use HasHint;
    use HasLabel;

    protected string $view = 'filament-forms::components.mw-input-slider';

    protected int $max = 10;

    protected int $min = 0;

    protected int $step = 1;

    protected array $behaviour = ['drag', 'tap'];

    protected array $sliders = [];

    protected bool $snap = false;

    protected bool | array $connect = true;

    protected ?array $range = null;

    protected ?array $tooltips = null;

    protected bool $isEnableTooltips = false;

    public static function make(?string $label = null): static
    {
        $static = new static;

        return $static
            ->label($label);
    }

    /**
     * Get the value of max
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Set the value of max
     *
     * @return self
     */
    public function max($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get the value of min
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set the value of min
     *
     * @return self
     */
    public function min($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get the value of step
     */
    public function getStep(): int
    {
        return $this->step;
    }

    /**
     * Set the value of step
     *
     * @return self
     */
    public function step($step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Get the value of behaviour
     */
    public function getBehaviour(): string
    {
        return implode('-', $this->behaviour);
    }

    /**
     * Set the value of behaviour
     *
     * @return self
     */
    public function behaviour(array $behaviour)
    {
        $this->behaviour = $behaviour;

        return $this;
    }

    /**
     * Get the value of snap
     */
    public function getSnap(): bool
    {
        return $this->snap;
    }

    /**
     * Set the value of snap
     *
     * @return self
     */
    public function snap(bool $snap)
    {
        $this->snap = $snap;

        return $this;
    }

    /**
     * Get the value of sliders
     */
    public function getSliders(): array
    {
        return $this->sliders;
    }

    /**
     * Set the value of sliders
     *
     * @return self
     */
    public function sliders(array $sliders)
    {
        $this->sliders = $sliders;

        $this->childComponents($sliders);

        return $this;
    }

    public function getStates(): array
    {

        $states = [];

        $states = collect($this->getSliders())->map(function ($slider) {
            $statePath = $slider->getStatePath();
            // return '$wire.' . $this->applyStateBindingModifiers("entangle('{$slider->getStatePath()}', false)", isOptimisticallyLive: false);

            return $slider->getStatePath();
        })
            ->toArray();

        return $states;
    }

    public function getStart(): array
    {
        $start = [];

        $start = collect($this->getSliders())->map(function (MwInputSlider $slider) {
            return $slider->getDefaultState();
        })
            ->toArray();

        return $start;
    }

    /**
     * Get the value of connect
     */
    public function getConnect()
    {

        if ($this->connect && is_array($this->connect)) {
            if (! (count($this->connect) == count($this->getSliders()) + 1)) {
                throw new Error('connect property must be total sliders + 1 ');
            }

            return $this->connect;
        }

        return $this->connect;
    }

    /**
     * Set the value of connect
     *
     * @return self
     */
    public function connect(bool | array $connect = true)
    {
        $this->connect = $connect;

        return $this;
    }

    /**
     * Get the value of range
     */
    public function getRange()
    {
        if ($this->range) {
            return $this->range;
        }

        return [
            'min' => $this->getMin(),
            'max' => $this->getMax(),
        ];
    }

    /**
     * Set the value of range
     *
     * @return self
     */
    public function range(array $range)
    {
        $this->range = $range;

        return $this;
    }

    public function enableTooltips(bool $condition = true)
    {
        $this->isEnableTooltips = $condition;

        return $this;
    }

    /**
     * Get the value of tooltips
     */
    public function getTooltips()
    {

        if ($this->tooltips) {
            return $this->tooltips;
        }

        return array_fill(0, count($this->getSliders()), $this->isEnableTooltips);
    }

    /**
     * Set the value of tooltips
     *
     * @return self
     */
    public function tooltips(array $tooltips)
    {
        $this->tooltips = $tooltips;

        return $this;
    }
}
