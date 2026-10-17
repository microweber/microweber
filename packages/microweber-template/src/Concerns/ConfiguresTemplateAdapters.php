<?php

namespace MicroweberPackages\Template\Concerns;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait ConfiguresTemplateAdapters
{
    public function setIconFontsAdapter($adapter)
    {
        $this->iconFontsAdapter = $adapter;
    }

    public function setTemplateAdapter($adapter)
    {
        $this->templateAdapter = $adapter;
    }

    public function setFontsAdapter($adapter)
    {
        $this->fontsAdapter = $adapter;
    }

    public function setCustomCssAdapter($adapter)
    {
        $this->customCssAdapter = $adapter;
    }

    public function setLiveEditCssAdapter($adapter)
    {
        $this->liveEditCssAdapter = $adapter;
    }
}
