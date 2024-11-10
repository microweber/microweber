<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Alert extends Component
{
    /**
     * The alert type.
     *
     * @var string
     */
    public string $type;

    /**
     * Whether the alert is dismissible.
     *
     * @var bool
     */
    public bool $dismissible;

    /**
     * Create the component instance.
     *
     * @param string $type The alert type (success, danger, warning, info, primary, secondary, light, dark)
     * @param bool $dismissible Whether the alert should be dismissible
     */
    public function __construct(
        string $type = 'primary',
        bool $dismissible = false
    ) {
        $this->type = $type;
        $this->dismissible = $dismissible;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.alert');
    }
}
