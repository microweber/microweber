<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Storage.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Support\Facades\Storage
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-8fdf4c57242402998442f1c2ca593b24204c106ec4871369480530b86e0544c4-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Support\\Facades\\Storage',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Storage.php',
      ),
    ),
    'namespace' => 'Illuminate\\Support\\Facades',
    'name' => 'Illuminate\\Support\\Facades\\Storage',
    'shortName' => 'Storage',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method static \\Illuminate\\Contracts\\Filesystem\\Filesystem drive(string|null $name = null)
 * @method static \\Illuminate\\Contracts\\Filesystem\\Filesystem disk(string|null $name = null)
 * @method static \\Illuminate\\Contracts\\Filesystem\\Cloud cloud()
 * @method static \\Illuminate\\Contracts\\Filesystem\\Filesystem build(string|array $config)
 * @method static \\Illuminate\\Contracts\\Filesystem\\Filesystem createLocalDriver(array $config, string $name = \'local\')
 * @method static \\Illuminate\\Contracts\\Filesystem\\Filesystem createFtpDriver(array $config)
 * @method static \\Illuminate\\Contracts\\Filesystem\\Filesystem createSftpDriver(array $config)
 * @method static \\Illuminate\\Contracts\\Filesystem\\Cloud createS3Driver(array $config)
 * @method static \\Illuminate\\Contracts\\Filesystem\\Filesystem createScopedDriver(array $config)
 * @method static \\Illuminate\\Filesystem\\FilesystemManager set(string $name, mixed $disk)
 * @method static string getDefaultDriver()
 * @method static string getDefaultCloudDriver()
 * @method static \\Illuminate\\Filesystem\\FilesystemManager forgetDisk(array|string $disk)
 * @method static void purge(string|null $name = null)
 * @method static \\Illuminate\\Filesystem\\FilesystemManager extend(string $driver, \\Closure $callback)
 * @method static \\Illuminate\\Filesystem\\FilesystemManager setApplication(\\Illuminate\\Contracts\\Foundation\\Application $app)
 * @method static string path(string $path)
 * @method static bool exists(string $path)
 * @method static string|null get(string $path)
 * @method static resource|null readStream(string $path)
 * @method static bool put(string $path, \\Psr\\Http\\Message\\StreamInterface|\\Illuminate\\Http\\File|\\Illuminate\\Http\\UploadedFile|string|resource $contents, mixed $options = [])
 * @method static string|false putFile(\\Illuminate\\Http\\File|\\Illuminate\\Http\\UploadedFile|string $path, \\Illuminate\\Http\\File|\\Illuminate\\Http\\UploadedFile|string|array|null $file = null, mixed $options = [])
 * @method static string|false putFileAs(\\Illuminate\\Http\\File|\\Illuminate\\Http\\UploadedFile|string $path, \\Illuminate\\Http\\File|\\Illuminate\\Http\\UploadedFile|string|array|null $file, string|array|null $name = null, mixed $options = [])
 * @method static bool writeStream(string $path, resource $resource, array $options = [])
 * @method static string getVisibility(string $path)
 * @method static bool setVisibility(string $path, string $visibility)
 * @method static bool prepend(string $path, string $data)
 * @method static bool append(string $path, string $data)
 * @method static bool delete(string|array $paths)
 * @method static bool copy(string $from, string $to)
 * @method static bool move(string $from, string $to)
 * @method static int size(string $path)
 * @method static int lastModified(string $path)
 * @method static array files(string|null $directory = null, bool $recursive = false)
 * @method static array allFiles(string|null $directory = null)
 * @method static array directories(string|null $directory = null, bool $recursive = false)
 * @method static array allDirectories(string|null $directory = null)
 * @method static bool makeDirectory(string $path)
 * @method static bool deleteDirectory(string $directory)
 * @method static \\Illuminate\\Filesystem\\FilesystemAdapter assertExists(string|array $path, string|null $content = null)
 * @method static \\Illuminate\\Filesystem\\FilesystemAdapter assertCount(string $path, int $count, bool $recursive = false)
 * @method static \\Illuminate\\Filesystem\\FilesystemAdapter assertMissing(string|array $path)
 * @method static \\Illuminate\\Filesystem\\FilesystemAdapter assertDirectoryEmpty(string $path)
 * @method static bool missing(string $path)
 * @method static bool fileExists(string $path)
 * @method static bool fileMissing(string $path)
 * @method static bool directoryExists(string $path)
 * @method static bool directoryMissing(string $path)
 * @method static array|null json(string $path, int $flags = 0)
 * @method static \\Symfony\\Component\\HttpFoundation\\StreamedResponse response(string $path, string|null $name = null, array $headers = [], string|null $disposition = \'inline\')
 * @method static \\Symfony\\Component\\HttpFoundation\\StreamedResponse serve(\\Illuminate\\Http\\Request $request, string $path, string|null $name = null, array $headers = [])
 * @method static \\Symfony\\Component\\HttpFoundation\\StreamedResponse download(string $path, string|null $name = null, array $headers = [])
 * @method static string|false checksum(string $path, array $options = [])
 * @method static string|false mimeType(string $path)
 * @method static string url(string $path)
 * @method static bool providesTemporaryUrls()
 * @method static string temporaryUrl(string $path, \\DateTimeInterface $expiration, array $options = [])
 * @method static array temporaryUploadUrl(string $path, \\DateTimeInterface $expiration, array $options = [])
 * @method static \\League\\Flysystem\\FilesystemOperator getDriver()
 * @method static \\League\\Flysystem\\FilesystemAdapter getAdapter()
 * @method static array getConfig()
 * @method static void serveUsing(\\Closure $callback)
 * @method static void buildTemporaryUrlsUsing(\\Closure $callback)
 * @method static \\Illuminate\\Filesystem\\FilesystemAdapter|mixed when(\\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 * @method static \\Illuminate\\Filesystem\\FilesystemAdapter|mixed unless(\\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 * @method static mixed macroCall(string $method, array $parameters)
 * @method static bool has(string $location)
 * @method static string read(string $location)
 * @method static \\League\\Flysystem\\DirectoryListing listContents(string $location, bool $deep = false)
 * @method static int fileSize(string $path)
 * @method static string visibility(string $path)
 * @method static void write(string $location, string $contents, array $config = [])
 * @method static void createDirectory(string $location, array $config = [])
 *
 * @see \\Illuminate\\Filesystem\\FilesystemManager
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 88,
    'endLine' => 173,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Support\\Facades\\Facade',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'fake' => 
      array (
        'name' => 'fake',
        'parameters' => 
        array (
          'disk' => 
          array (
            'name' => 'disk',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 97,
                'endLine' => 97,
                'startTokenPos' => 38,
                'startFilePos' => 6652,
                'endTokenPos' => 38,
                'endFilePos' => 6655,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'config' => 
          array (
            'name' => 'config',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 97,
                'endLine' => 97,
                'startTokenPos' => 47,
                'startFilePos' => 6674,
                'endTokenPos' => 48,
                'endFilePos' => 6675,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 47,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Replace the given disk with a local testing disk.
 *
 * @param  string|null  $disk
 * @param  array  $config
 * @return \\Illuminate\\Contracts\\Filesystem\\Filesystem
 */',
        'startLine' => 97,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'aliasName' => NULL,
      ),
      'persistentFake' => 
      array (
        'name' => 'persistentFake',
        'parameters' => 
        array (
          'disk' => 
          array (
            'name' => 'disk',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 123,
                'endLine' => 123,
                'startTokenPos' => 223,
                'startFilePos' => 7533,
                'endTokenPos' => 223,
                'endFilePos' => 7536,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 43,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'config' => 
          array (
            'name' => 'config',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 123,
                'endLine' => 123,
                'startTokenPos' => 232,
                'startFilePos' => 7555,
                'endTokenPos' => 233,
                'endFilePos' => 7556,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 57,
            'endColumn' => 74,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Replace the given disk with a persistent local testing disk.
 *
 * @param  string|null  $disk
 * @param  array  $config
 * @return \\Illuminate\\Contracts\\Filesystem\\Filesystem
 */',
        'startLine' => 123,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'aliasName' => NULL,
      ),
      'getRootPath' => 
      array (
        'name' => 'getRootPath',
        'parameters' => 
        array (
          'disk' => 
          array (
            'name' => 'disk',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 140,
            'endLine' => 140,
            'startColumn' => 43,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the root path of the given disk.
 *
 * @param  string  $disk
 * @return string
 */',
        'startLine' => 140,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'aliasName' => NULL,
      ),
      'buildDiskConfiguration' => 
      array (
        'name' => 'buildDiskConfiguration',
        'parameters' => 
        array (
          'disk' => 
          array (
            'name' => 'disk',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 153,
            'endLine' => 153,
            'startColumn' => 54,
            'endColumn' => 65,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'config' => 
          array (
            'name' => 'config',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 153,
            'endLine' => 153,
            'startColumn' => 68,
            'endColumn' => 80,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'root' => 
          array (
            'name' => 'root',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 153,
            'endLine' => 153,
            'startColumn' => 83,
            'endColumn' => 94,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assemble the configuration of the given disk.
 *
 * @param  string  $disk
 * @param  array  $config
 * @param  string  $root
 * @return array
 */',
        'startLine' => 153,
        'endLine' => 162,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'aliasName' => NULL,
      ),
      'getFacadeAccessor' => 
      array (
        'name' => 'getFacadeAccessor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the registered name of the component.
 *
 * @return string
 */',
        'startLine' => 169,
        'endLine' => 172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Storage',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));