<?php

namespace MicroweberPackages\Template\Managers;

/**
 * Manages scripts and stylesheets for head and foot sections.
 *
 * This class handles the storage and rendering of CSS and JS assets,
 * extracted from TemplateManager to follow Single Responsibility Principle.
 */
class ScriptStyleManager
{
    /**
     * @var array Scripts/styles for head section
     */
    protected array $head = [];

    /**
     * @var array Callable functions for head section
     */
    protected array $headCallable = [];

    /**
     * @var array Scripts/styles for foot section
     */
    protected array $foot = [];

    /**
     * @var array Callable functions for foot section
     */
    protected array $footCallable = [];

    /**
     * @var array Admin header scripts/styles
     */
    protected static ?array $adminHeaders = null;

    /**
     * Add a script or style to the head section.
     *
     * @param string|callable $scriptSrc The script source URL or a callable
     * @return string The script source if string, empty string otherwise
     */
    public function head($scriptSrc): string
    {
        if (is_string($scriptSrc)) {
            if (!in_array($scriptSrc, $this->head)) {
                $this->head[] = $scriptSrc;
            }
            return $scriptSrc;
        }

        if (is_bool($scriptSrc)) {
            return $this->renderHead();
        }

        if (is_callable($scriptSrc)) {
            if (!in_array($scriptSrc, $this->headCallable)) {
                $this->headCallable[] = $scriptSrc;
            }
            return '';
        }

        return '';
    }

    /**
     * Add a script or style to the foot section.
     *
     * @param string|callable $scriptSrc The script source URL or a callable
     * @return string The script source if string, empty string otherwise
     */
    public function foot($scriptSrc): string
    {
        if (is_string($scriptSrc)) {
            if (!in_array($scriptSrc, $this->foot)) {
                $this->foot[] = $scriptSrc;
            }
            return $scriptSrc;
        }

        if (is_bool($scriptSrc)) {
            return $this->renderFoot();
        }

        if (is_callable($scriptSrc)) {
            if (!in_array($scriptSrc, $this->footCallable)) {
                $this->footCallable[] = $scriptSrc;
            }
            return '';
        }

        return '';
    }

    /**
     * Execute callable functions registered for head section.
     *
     * @param mixed $data Optional data to pass to callbacks
     * @return array Array of callback results
     */
    public function head_callback($data = false): array
    {
        $results = [];
        if (!empty($this->headCallable)) {
            foreach ($this->headCallable as $callback) {
                $results[] = call_user_func($callback, $data);
            }
        }
        return $results;
    }

    /**
     * Execute callable functions registered for foot section.
     *
     * @param mixed $data Optional data to pass to callbacks
     * @return array Array of callback results
     */
    public function foot_callback($data = false): array
    {
        $results = [];
        if (!empty($this->footCallable)) {
            foreach ($this->footCallable as $callback) {
                $results[] = call_user_func($callback, $data);
            }
        }
        return $results;
    }

    /**
     * Add a script to the admin head section.
     *
     * @param string|bool $scriptSrc The script source URL or true/false to get all
     * @return array|string Array of scripts if true passed, string of HTML otherwise
     */
    public function admin_head($scriptSrc)
    {
        if (self::$adminHeaders === null) {
            self::$adminHeaders = [];
        }

        if (is_string($scriptSrc)) {
            if (!in_array($scriptSrc, self::$adminHeaders)) {
                self::$adminHeaders[] = $scriptSrc;
            }
            return self::$adminHeaders;
        }

        if (is_bool($scriptSrc)) {
            return $this->renderAdminHead();
        }

        return [];
    }

    /**
     * Render head section HTML.
     *
     * @return string HTML output for head section
     */
    protected function renderHead(): string
    {
        $src = '';

        if (!empty($this->head)) {
            foreach ($this->head as $header) {
                $src .= $this->renderAsset($header);
            }
        }

        return $src;
    }

    /**
     * Render foot section HTML.
     *
     * @return string HTML output for foot section
     */
    protected function renderFoot(): string
    {
        $src = '';

        if (!empty($this->foot)) {
            foreach ($this->foot as $footer) {
                $src .= $this->renderAsset($footer);
            }
        }

        return $src;
    }

    /**
     * Render admin head section HTML.
     *
     * @return string HTML output for admin head section
     */
    protected function renderAdminHead(): string
    {
        $src = '';

        if (!empty(self::$adminHeaders)) {
            foreach (self::$adminHeaders as $header) {
                $src .= $this->renderAsset($header);
            }
        }

        return $src;
    }

    /**
     * Render an individual asset as HTML.
     *
     * @param string $asset The asset URL or inline content
     * @return string The HTML tag for the asset
     */
    protected function renderAsset(string $asset): string
    {
        $ext = $this->getFileExtension($asset);

        switch (strtolower($ext)) {
            case 'css':
                return '<link rel="stylesheet" href="' . $asset . '" type="text/css" media="all">' . "\n";
            case 'js':
                return '<script src="' . $asset . '"></script>' . "\n";
            default:
                return $asset . "\n";
        }
    }

    /**
     * Get the file extension from a path.
     *
     * @param string $path The file path or URL
     * @return string The file extension
     */
    protected function getFileExtension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * Get all head scripts.
     *
     * @return array Array of head scripts
     */
    public function getHead(): array
    {
        return $this->head;
    }

    /**
     * Get all foot scripts.
     *
     * @return array Array of foot scripts
     */
    public function getFoot(): array
    {
        return $this->foot;
    }

    /**
     * Get all head callable functions.
     *
     * @return array Array of callable functions
     */
    public function getHeadCallable(): array
    {
        return $this->headCallable;
    }

    /**
     * Get all foot callable functions.
     *
     * @return array Array of callable functions
     */
    public function getFootCallable(): array
    {
        return $this->footCallable;
    }

    /**
     * Clear all head scripts.
     *
     * @return void
     */
    public function clearHead(): void
    {
        $this->head = [];
        $this->headCallable = [];
    }

    /**
     * Clear all foot scripts.
     *
     * @return void
     */
    public function clearFoot(): void
    {
        $this->foot = [];
        $this->footCallable = [];
    }

    /**
     * Clear admin head scripts.
     *
     * @return void
     */
    public static function clearAdminHead(): void
    {
        self::$adminHeaders = [];
    }
}
