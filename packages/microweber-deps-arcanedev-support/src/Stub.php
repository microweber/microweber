<?php

declare(strict_types=1);

namespace Arcanedev\Support;

/**
 * Class     Stub
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Stub
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The stub path.
     *
     * @var string
     */
    protected $path;

    /**
     * The base path of stub file.
     *
     * @var string|null
     */
    protected static $basePath = null;

    /**
     * The replacements array.
     *
     * @var array
     */
    protected $replaces = [];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Create a new instance.
     *
     * @param  string  $path
     * @param  array   $replaces
     */
    public function __construct($path, array $replaces = [])
    {
        $this->setPath($path);
        $this->setReplaces($replaces);
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get stub path.
     *
     * @return string
     */
    public function getPath(): string
    {
        $path = $this->path;

        if ( ! empty(static::$basePath)) {
            $path = static::$basePath.DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR);
        }

        return $path;
    }

    /**
     * Set stub path.
     *
     * @param  string  $path
     *
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get base path.
     *
     * @return string|null
     */
    public static function getBasePath(): ?string
    {
        return static::$basePath;
    }

    /**
     * Set base path.
     *
     * @param  string  $path
     */
    public static function setBasePath(string $path)
    {
        static::$basePath = $path;
    }

    /**
     * Get replacements.
     *
     * @return array
     */
    public function getReplaces(): array
    {
        return $this->replaces;
    }

    /**
     * Set replacements array.
     *
     * @param  array  $replaces
     *
     * @return $this
     */
    public function setReplaces(array $replaces = []): self
    {
        $this->replaces = $replaces;

        return $this;
    }

    /**
     * Set replacements array.
     *
     * @param  array  $replaces
     *
     * @return $this
     */
    public function replaces(array $replaces = []): self
    {
        return $this->setReplaces($replaces);
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create new self instance.
     *
     * @param  string  $path
     * @param  array   $replaces
     *
     * @return $this
     */
    public static function create(string $path, array $replaces = []): self
    {
        return new static($path, $replaces);
    }

    /**
     * Create new self instance from full path.
     *
     * @param  string  $path
     * @param  array   $replaces
     *
     * @return $this
     */
    public static function createFromPath(string $path, array $replaces = []): self
    {
        return tap(new static($path, $replaces), function (self $stub) {
            $stub->setBasePath('');
        });
    }

    /**
     * Get stub contents.
     *
     * @return string
     */
    public function render(): string
    {
        return $this->getContents();
    }

    /**
     * Save stub to base path.
     *
     * @param  string  $filename
     *
     * @return bool
     */
    public function save(string $filename): bool
    {
        return $this->saveTo(self::getBasePath(), $filename);
    }

    /**
     * Save stub to specific path.
     *
     * @param  string  $path
     * @param  string  $filename
     *
     * @return bool
     */
    public function saveTo(string $path, string $filename): bool
    {
        return file_put_contents($path.DIRECTORY_SEPARATOR.$filename, $this->render()) !== false;
    }

    /**
     * Get stub contents.
     *
     * @return string|mixed
     */
    public function getContents()
    {
        $contents = file_get_contents($this->getPath());

        foreach ($this->getReplaces() as $search => $replace) {
            $contents = str_replace('$'.strtoupper($search).'$', $replace, $contents);
        }

        return $contents;
    }

    /**
     * Handle magic method __toString.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
