<?php

namespace MicroweberPackages\Template\Concerns;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait InjectsHeadFoot
{
    /**
     * @internal
     * @deprecated Use scriptStyleManager->admin_head() instead
     */
    public function admin_head($script_src)
    {
        return $this->scriptStyleManager->admin_head($script_src);
    }

    /**
     * @internal
     * @deprecated Use scriptStyleManager->head() instead
     */
    public function head($script_src)
    {
        // Maintain backward compatibility with public properties
        $result = $this->scriptStyleManager->head($script_src);

        if (is_string($script_src)) {
            if (!in_array($script_src, $this->head)) {
                $this->head[] = $script_src;
            }
            if (!in_array($script_src, $this->head_callable)) {
                $this->head_callable = $this->scriptStyleManager->getHeadCallable();
            }
        }

        return $result;
    }

    /**
     * @internal
     * @deprecated Use scriptStyleManager->head_callback() instead
     */
    public function head_callback($data = false)
    {
        return $this->scriptStyleManager->head_callback($data);
    }

    /**
     * @internal
     * @deprecated Use scriptStyleManager->foot() instead
     */
    public function foot($script_src)
    {
        // Maintain backward compatibility with public properties
        $result = $this->scriptStyleManager->foot($script_src);

        if (is_string($script_src)) {
            if (!in_array($script_src, $this->foot)) {
                $this->foot[] = $script_src;
            }
            if (!in_array($script_src, $this->foot_callable)) {
                $this->foot_callable = $this->scriptStyleManager->getFootCallable();
            }
        }

        return $result;
    }

    /**
     * @internal
     * @deprecated Use scriptStyleManager->foot_callback() instead
     */
    public function foot_callback($data = false)
    {
        return $this->scriptStyleManager->foot_callback($data);
    }
}
