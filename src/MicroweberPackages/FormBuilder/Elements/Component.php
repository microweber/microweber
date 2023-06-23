<?php

namespace MicroweberPackages\FormBuilder\Elements;

class Component extends Element
{
    public function getType()
    {
        return 'component';
    }

    public function __toString()
    {
        return $this->render();
    }

    public function __call($method, $params)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $params);
        }

        return $this;
    }


    public function render()
    {
        $html = '';
        $html .= sprintf('<div%s>', $this->renderAttributes());
        $html .= $this->renderChildren();
        $html .= '</div>';
        return $html;
    }


}
