<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Card extends Component
{

    public string $theme;
    public string $headerClass;
    public string $bodyClass;
    public string $footerClass;
    public function __construct(
        ?string $theme = null,
        ?string $headerClass = null,
        ?string $bodyClass = null,
        ?string $footerClass = null,
    ) {

        $this->theme = $theme ?? 'light';
        $this->headerClass = $headerClass ?? '';
        $this->bodyClass = $bodyClass ?? '';
        $this->footerClass = $footerClass ?? '';

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.card');
    }
}
