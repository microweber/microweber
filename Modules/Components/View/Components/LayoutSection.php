<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;

class LayoutSection extends Component
{
    public array $params;
    public array $classes;
    public string $layoutClasses;
    public string $defaultPaddingTop;
    public string $defaultPaddingBottom;
    public string $sectionClass;
    public string $fieldName;
    public string $editableClass;
    public bool $noDrop;
    public bool $hasBackground;
    public string $backgroundAttrs;
    public bool $hasSpacers;
    public string $containerClass;
    public bool $useContainer;

    public function __construct(
        array $params = [],
        array $classes = [],
        string $layoutClasses = '',
        string $defaultPaddingTop = '',
        string $defaultPaddingBottom = '',
        string $sectionClass = 'section',
        string $fieldName = '',
        string $editableClass = 'edit safe-mode',
        bool $noDrop = false,
        bool $hasBackground = true,
        string $backgroundAttrs = '',
        bool $hasSpacers = true,
        string $containerClass = 'mw-layout-container',
        bool $useContainer = true
    ) {
        $this->params = $params;
        $this->classes = $classes;
        $this->layoutClasses = $layoutClasses;
        $this->defaultPaddingTop = $defaultPaddingTop;
        $this->defaultPaddingBottom = $defaultPaddingBottom;
        $this->sectionClass = $sectionClass;
        $this->fieldName = $fieldName;
        $this->editableClass = $editableClass;
        $this->noDrop = $noDrop;
        $this->hasBackground = $hasBackground;
        $this->backgroundAttrs = $backgroundAttrs;
        $this->hasSpacers = $hasSpacers;
        $this->containerClass = $containerClass;
        $this->useContainer = $useContainer;
    }

    public function render()
    {
        return view('modules.components::components.layout-section');
    }
}
