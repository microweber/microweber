<?php

namespace MicroweberPackages\View;

use Illuminate\Filesystem\Filesystem;

class StringBlade
{
    /**
     * @var Filesystem
     */
    protected $file;

    /**
     * @var \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected $viewer;

    /**
     * StringBlade constructor.
     *
     * @param Filesystem $file
     */
    public function __construct(Filesystem $file)
    {
        $this->file = $file;
        $this->viewer = view();
    }

    /**
     * Get Blade File path.
     *
     * @param $bladeString
     * @return bool|string
     */
    protected function getBlade($bladeString)
    {
        $bladePath = $this->generateBladePath();

        $content = \Blade::compileString($bladeString);

        return $this->file->put($bladePath, $content)
            ? $bladePath
            : false;
    }

    /**
     * Get the rendered HTML.
     *
     * @param $bladeString
     * @param array $data
     * @return bool|string
     */
    public function render($bladeString, $data = [])
    {
        // Put the php version of blade String to *.php temp file & returns the temp file path
        $bladePath = $this->getBlade($bladeString);

        if (!$bladePath) {
            return false;
        }

        // Render the php temp file & return the HTML content
        $content = $this->viewer->file($bladePath, $data)->render();

        // Delete the php temp file.
        $this->file->delete($bladePath);

        return $content;
    }

    /**
     * Generate a blade file path.
     *
     * @return string
     */
    protected function generateBladePath()
    {
        $cachePath = rtrim(config('cache.stores.file.path'), '/');
        $tempFileName = sha1('string-blade' . microtime());
        $directory = "{$cachePath}/string-blades";

        if (!is_dir($directory)) {
            mkdir($directory, 0777);
        }

        return "{$directory}/{$tempFileName}.php";
    }
}
