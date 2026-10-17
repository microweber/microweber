<?php

namespace MicroweberPackages\Template\Concerns;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait ManagesStacks
{
    /**
     * @deprecated
     */
    public function stack_add($src, $group = 'default')
    {
        return $this->stack_compiler_adapter->add($src, $group);
    }

    /**
     * @deprecated
     */
    public function stack_display($group = 'default', $to_return = false)
    {
        return $this->stack_compiler_adapter->display($group, $to_return);
    }

    /**
     * @deprecated
     */
    public function process_stacks($layout)
    {
        return $this->stack_compiler_adapter->render($layout);

    }
}
