<?php

declare(strict_types=1);

namespace MicroweberPackages\View;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\View as IlluminateView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Lightweight PHP file-based view renderer.
 *
 * Extracts assigned properties into the template scope, includes the file,
 * and returns the captured output. Designed for standalone Laravel apps and
 * the Microweber CMS without depending on CMS globals.
 */
#[\AllowDynamicProperties]
class View implements Renderable
{
    /** Absolute path to the PHP template file. */
    public string $v;

    public mixed $app = null;

    /** @var array<string, mixed>|null */
    public ?array $config = null;

    /** @var array<string, mixed>|null */
    public ?array $params = null;

    /** @var array<string, mixed>|mixed */
    public mixed $data = null;

    /** @var array<string, mixed>|null */
    public ?array $settings = null;

    /** @var Response|null Last Response object returned by an included template. */
    protected ?Response $lastResponse = null;

    /**
     * @param  string  $v  Path to a PHP template file
     *
     * @throws \InvalidArgumentException When the file does not exist
     */
    public function __construct(string $v)
    {
        $resolved = realpath($v);

        if ($resolved === false || !is_file($resolved)) {
            throw new \InvalidArgumentException('The view file not found. ' . $v);
        }

        $this->v = $resolved;
    }

    /**
     * Bulk-assign variables as public properties.
     *
     * @param  array<string, mixed>  $a
     */
    public function set(array $a): self
    {
        foreach ($a as $k => $v) {
            $this->{$k} = $v;
        }

        return $this;
    }

    /**
     * Assign a single variable as a public property.
     */
    public function assign(string $var, mixed $val): self
    {
        $this->{$var} = $val;

        return $this;
    }

    /**
     * Include the template once and return all variables defined in its scope.
     *
     * @return array<string, mixed>
     */
    public function __get_vars(): array
    {
        ob_start();
        // Extract public properties into local scope for the template.
        // @phpstan-ignore-next-line argument.type (dynamic properties)
        extract(get_object_vars($this), EXTR_SKIP);

        $file_dir = dirname($this->v) . DIRECTORY_SEPARATOR;

        require $this->v;

        ob_end_clean();

        $defined_vars = [];
        $var_names = array_keys(get_defined_vars());

        foreach ($var_names as $var_name) {
            if ($var_name === 'defined_vars' || $var_name === 'this' || $var_name === 'file_dir') {
                continue;
            }
            $defined_vars[$var_name] = ${$var_name};
        }

        return $defined_vars;
    }

    /**
     * Render and optionally echo the template.
     */
    public function display(bool $return = false): string
    {
        $content = $this->__toString();

        if ($return) {
            return $content;
        }

        echo $content;

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        return $this->__toString();
    }

    /**
     * If the included template returned a Symfony/Illuminate Response, expose it.
     */
    public function getResponse(): ?Response
    {
        return $this->lastResponse;
    }

    public function __toString(): string
    {
        $this->lastResponse = null;

        // @phpstan-ignore-next-line argument.type (dynamic properties)
        extract(get_object_vars($this), EXTR_SKIP);

        ob_start();

        $res = null;
        if (is_file($this->v)) {
            $res = include $this->v;
        }

        if (is_object($res)) {
            // RedirectResponse extends Response in Symfony
            if ($res instanceof RedirectResponse) {
                $this->lastResponse = $res;
                ob_end_clean();

                return '';
            }

            if ($res instanceof Response) {
                $this->lastResponse = $res;
                $content = $res->getContent();
                ob_end_clean();

                return $content === false ? '' : $content;
            }

            if ($res instanceof IlluminateView || $res instanceof Renderable) {
                $rendered = $res->render();
                ob_end_clean();

                return $rendered;
            }
        }

        $content = ob_get_clean();

        return $content === false ? '' : $content;
    }
}
