<?php

namespace MicroweberPackages\View;

class TwigView
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
