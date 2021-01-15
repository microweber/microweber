<?php

namespace MicroweberPackages\Template\Adapters\RenderHelpers;

class TwigRenderHelper
{
    public function render($html, array $data = [],$options = [])
    {

        $key = md5($html);
        $loader = new \Twig\Loader\ArrayLoader([
            $key => $html,
        ]);
        $twig = new \Twig\Environment($loader,$options);

        return $twig->render($key, $data);


    }
}