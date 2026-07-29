<?php

declare(strict_types=1);

namespace MicroweberPackages\ClassLoader;

/**
 * Instance-based class loader with path deduplication.
 *
 * Replaces the former CMS-static class loaders with a single service that:
 *
 *  - avoids static state (no memory leaks between container cycles / tests)
 *  - registers a single spl_autoload callback (not one per namespace)
 *  - normalizes directory and namespace paths so duplicates with different
 *    separators or trailing slashes are only stored once
 */
class ClassLoader
{
    private PathNormalizer $paths;

    /** @var array<string, true> keyed by normalized directory path */
    private array $directories = [];

    /**
     * PSR-4 map: namespace prefix (no leading/trailing backslash) => list of
     * normalized directory paths.
     *
     * @var array<string, list<string>>
     */
    private array $namespaces = [];

    /** @var array<string, string> class => absolute file path */
    private array $foundClasses = [];

    /** @var array<string, true> */
    private array $notFoundClasses = [];

    private bool $registered = false;

    private bool $cacheLookups = true;

    /** @var (callable(string): void)|null */
    private $autoloadCallback = null;

    public function __construct(?PathNormalizer $paths = null, bool $cacheLookups = true)
    {
        $this->paths = $paths ?? new PathNormalizer();
        $this->cacheLookups = $cacheLookups;
    }

    /**
     * Load the given class file from registered directories or PSR-4 namespaces.
     */
    public function load(string $class): bool
    {
        if ($class === '') {
            return false;
        }

        if ($this->cacheLookups && isset($this->notFoundClasses[$class])) {
            return false;
        }

        if ($this->cacheLookups && isset($this->foundClasses[$class])) {
            $file = $this->foundClasses[$class];
            if (is_file($file)) {
                require_once $file;

                return true;
            }
            unset($this->foundClasses[$class]);
        }

        $file = $this->resolve($class);
        if ($file === null) {
            if ($this->cacheLookups) {
                $this->notFoundClasses[$class] = true;
            }

            return false;
        }

        if ($this->cacheLookups) {
            $this->foundClasses[$class] = $file;
        }

        require_once $file;

        return true;
    }

    /**
     * Resolve a class name to a filesystem path without including the file.
     */
    public function resolve(string $class): ?string
    {
        if ($class === '') {
            return null;
        }

        $fromNamespace = $this->resolveFromNamespaces($class);
        if ($fromNamespace !== null) {
            return $fromNamespace;
        }

        return $this->resolveFromDirectories($class);
    }

    /**
     * Get the relative file name for a class (directory-based lookup).
     */
    public function normalizeClass(string $class): string
    {
        if ($class !== '' && $class[0] === '\\') {
            $class = substr($class, 1);
        }

        return str_replace(['\\', '_'], '/', $class) . '.php';
    }

    /**
     * Register this loader on the auto-loader stack (once).
     */
    public function register(bool $prepend = false): self
    {
        if (!$this->registered) {
            // spl_autoload_register expects callable(string): void; our load()
            // returns bool (PSR-4 / Composer style) which PHP accepts at runtime.
            $callback = function (string $class): void {
                $this->load($class);
            };
            spl_autoload_register($callback, true, $prepend);
            $this->registered = true;
            $this->autoloadCallback = $callback;
        }

        return $this;
    }

    /**
     * Unregister this loader from the auto-loader stack.
     */
    public function unregister(): self
    {
        if ($this->registered) {
            if ($this->autoloadCallback !== null) {
                spl_autoload_unregister($this->autoloadCallback);
                $this->autoloadCallback = null;
            }
            $this->registered = false;
        }

        return $this;
    }

    public function isRegistered(): bool
    {
        return $this->registered;
    }

    /**
     * Add directories for flat class-file lookup.
     *
     * @param  string|list<string>  $directories
     */
    public function addDirectories(string|array $directories): self
    {
        $list = is_array($directories) ? $directories : [$directories];
        foreach ($list as $directory) {
            if ($directory === '') {
                continue;
            }
            $normalized = $this->paths->normalize($directory);
            if ($normalized === '') {
                continue;
            }
            $this->directories[$normalized] = true;
        }

        return $this;
    }

    /**
     * Remove directories (or all when null).
     *
     * @param  string|list<string>|null  $directories
     */
    public function removeDirectories(string|array|null $directories = null): self
    {
        if ($directories === null) {
            $this->directories = [];

            return $this;
        }

        $list = is_array($directories) ? $directories : [$directories];
        foreach ($list as $directory) {
            if ($directory === '') {
                continue;
            }
            $normalized = $this->paths->normalize($directory);
            unset($this->directories[$normalized]);
        }

        return $this;
    }

    /**
     * @return list<string>
     */
    public function getDirectories(): array
    {
        return array_keys($this->directories);
    }

    /**
     * Register a PSR-4 namespace prefix with a base directory.
     */
    public function addNamespace(string $namespace, string $path): self
    {
        $namespace = trim($namespace, '\\');
        $normalized = $this->paths->normalize($path);
        if ($namespace === '' || $normalized === '') {
            return $this;
        }

        if (!isset($this->namespaces[$namespace])) {
            $this->namespaces[$namespace] = [];
        }

        if (!in_array($normalized, $this->namespaces[$namespace], true)) {
            $this->namespaces[$namespace][] = $normalized;
        }

        // New namespace mapping may make previously-not-found classes loadable.
        $this->notFoundClasses = [];

        return $this;
    }

    /**
     * Remove a namespace mapping (or a single path under that namespace).
     */
    public function removeNamespace(string $namespace, ?string $path = null): self
    {
        $namespace = trim($namespace, '\\');
        if ($namespace === '' || !isset($this->namespaces[$namespace])) {
            return $this;
        }

        if ($path === null) {
            unset($this->namespaces[$namespace]);

            return $this;
        }

        $normalized = $this->paths->normalize($path);
        $this->namespaces[$namespace] = array_values(array_filter(
            $this->namespaces[$namespace],
            static fn (string $existing): bool => $existing !== $normalized
        ));

        if ($this->namespaces[$namespace] === []) {
            unset($this->namespaces[$namespace]);
        }

        return $this;
    }

    /**
     * @return array<string, list<string>>
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    /**
     * Clear found/not-found lookup caches (frees memory; keeps registrations).
     */
    public function clearCache(): self
    {
        $this->foundClasses = [];
        $this->notFoundClasses = [];

        return $this;
    }

    /**
     * Clear registrations and caches, and unregister the autoloader.
     */
    public function reset(): self
    {
        $this->unregister();
        $this->directories = [];
        $this->namespaces = [];
        $this->clearCache();

        return $this;
    }

    public function setCacheLookups(bool $enabled): self
    {
        $this->cacheLookups = $enabled;
        if (!$enabled) {
            $this->clearCache();
        }

        return $this;
    }

    public function getCacheLookups(): bool
    {
        return $this->cacheLookups;
    }

    public function getPathNormalizer(): PathNormalizer
    {
        return $this->paths;
    }

    /**
     * @return array<string, mixed>
     */
    public function getStatistics(): array
    {
        $namespacePathCount = 0;
        foreach ($this->namespaces as $paths) {
            $namespacePathCount += count($paths);
        }

        return [
            'enabled' => true,
            'registered' => $this->registered,
            'cache_lookups' => $this->cacheLookups,
            'directories_count' => count($this->directories),
            'namespaces_count' => count($this->namespaces),
            'namespace_paths_count' => $namespacePathCount,
            'found_cache_count' => count($this->foundClasses),
            'not_found_cache_count' => count($this->notFoundClasses),
            'directories' => $this->getDirectories(),
            'namespaces' => $this->namespaces,
            'version' => '1.0.0',
        ];
    }

    /**
     * Smoke-test path normalization, deduplication, and resolution.
     *
     * @return array{
     *     ok: bool,
     *     path_dedup: bool,
     *     class_normalize: bool,
     *     details: array<string, mixed>
     * }
     */
    public function selfTest(): array
    {
        $a = $this->paths->normalize('/tmp/foo/');
        $b = $this->paths->normalize('/tmp/foo');
        $c = $this->paths->normalize(str_replace('/', DIRECTORY_SEPARATOR, '/tmp/foo/'));
        $pathDedup = ($a === $b) && ($b === $c) && $a !== '';

        $normalizedClass = $this->normalizeClass('\\Foo\\Bar_Baz');
        $classNormalize = $normalizedClass === 'Foo/Bar/Baz.php';

        return [
            'ok' => $pathDedup && $classNormalize,
            'path_dedup' => $pathDedup,
            'class_normalize' => $classNormalize,
            'details' => [
                'normalized_sample' => $a,
                'class_file' => $normalizedClass,
                'stats' => $this->getStatistics(),
            ],
        ];
    }

    private function resolveFromDirectories(string $class): ?string
    {
        $relative = $this->normalizeClass($class);

        foreach ($this->directories as $directory => $_true) {
            $osDir = $this->paths->toOsPath($directory);
            $path = $osDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    private function resolveFromNamespaces(string $class): ?string
    {
        if ($this->namespaces === []) {
            return null;
        }

        $originalClass = $class;
        if ($originalClass !== '' && $originalClass[0] === '\\') {
            $originalClass = substr($originalClass, 1);
        }

        $segments = explode('\\', $originalClass);
        while ($segments !== []) {
            $namespace = implode('\\', $segments);
            if (isset($this->namespaces[$namespace])) {
                $relativeClass = $originalClass;
                $prefix = $namespace . '\\';
                if (str_starts_with($originalClass, $prefix)) {
                    $relativeClass = substr($originalClass, strlen($prefix));
                } elseif ($originalClass === $namespace) {
                    $relativeClass = '';
                }

                $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass);

                foreach ($this->namespaces[$namespace] as $basePath) {
                    $osBase = $this->paths->toOsPath($basePath);
                    if ($classFile === '') {
                        $file = rtrim($osBase, '\\/') . '.php';
                    } else {
                        $file = rtrim($osBase, '\\/') . DIRECTORY_SEPARATOR . ltrim($classFile, '\\/');
                        if (!str_ends_with($file, '.php')) {
                            $file .= '.php';
                        }
                    }
                    if (is_file($file)) {
                        return $file;
                    }
                }
            }
            array_pop($segments);
        }

        return null;
    }
}
