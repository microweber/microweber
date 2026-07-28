<?php

declare(strict_types=1);

namespace MicroweberPackages\View;

use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * Render Twig template strings.
 */
class TwigView
{
    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $options  Twig Environment options
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(string $html, array $data = [], array $options = []): string
    {
        $key = md5($html);
        $loader = new ArrayLoader([
            $key => $html,
        ]);
        $twig = new Environment($loader, $options);

        return $twig->render($key, $data);
    }
}
