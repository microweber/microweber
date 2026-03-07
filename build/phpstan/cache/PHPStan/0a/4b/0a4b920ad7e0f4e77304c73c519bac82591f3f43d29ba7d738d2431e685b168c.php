<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/./composer/src/Composer/Package/PackageInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Composer\Package\PackageInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6cccd56e61a94dae27a3c139004bfe99af1c2e9ff1af7ed16bc59b461a7c32a1-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Composer\\Package\\PackageInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/./composer/src/Composer/Package/PackageInterface.php',
      ),
    ),
    'namespace' => 'Composer\\Package',
    'name' => 'Composer\\Package\\PackageInterface',
    'shortName' => 'PackageInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Defines the essential information a package has that is used during solving/installation
 *
 * PackageInterface & derivatives are considered internal, you may use them in type hints but extending/implementing them is not recommended and not supported. Things may change without notice.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 *
 * @phpstan-type AutoloadRules    array{psr-0?: array<string, string|string[]>, psr-4?: array<string, string|string[]>, classmap?: list<string>, files?: list<string>, exclude-from-classmap?: list<string>}
 * @phpstan-type DevAutoloadRules array{psr-0?: array<string, string|string[]>, psr-4?: array<string, string|string[]>, classmap?: list<string>, files?: list<string>}
 * @phpstan-type PhpExtConfig     array{extension-name?: string, priority?: int, support-zts?: bool, support-nts?: bool, build-path?: string|null, download-url-method?: string|list<string>, os-families?: non-empty-list<non-empty-string>, os-families-exclude?: non-empty-list<non-empty-string>, configure-options?: list<array{name: string, description?: string}>}
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 28,
    'endLine' => 394,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'DISPLAY_SOURCE_REF_IF_DEV' => 
      array (
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'name' => 'DISPLAY_SOURCE_REF_IF_DEV',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '0',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 37,
            'startFilePos' => 1528,
            'endTokenPos' => 37,
            'endFilePos' => 1528,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'DISPLAY_SOURCE_REF' => 
      array (
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'name' => 'DISPLAY_SOURCE_REF',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '1',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 48,
            'startFilePos' => 1569,
            'endTokenPos' => 48,
            'endFilePos' => 1569,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 40,
      ),
      'DISPLAY_DIST_REF' => 
      array (
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'name' => 'DISPLAY_DIST_REF',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '2',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 59,
            'startFilePos' => 1608,
            'endTokenPos' => 59,
            'endFilePos' => 1608,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 38,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'getName' => 
      array (
        'name' => 'getName',
        'parameters' => 
        array (
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
 * Returns the package\'s name without version info, thus not a unique identifier
 *
 * @return string package name
 */',
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 38,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getPrettyName' => 
      array (
        'name' => 'getPrettyName',
        'parameters' => 
        array (
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
 * Returns the package\'s pretty (i.e. with proper case) name
 *
 * @return string package name
 */',
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getNames' => 
      array (
        'name' => 'getNames',
        'parameters' => 
        array (
          'provides' => 
          array (
            'name' => 'provides',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 58,
                'endLine' => 58,
                'startTokenPos' => 104,
                'startFilePos' => 2408,
                'endTokenPos' => 104,
                'endFilePos' => 2411,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 58,
            'endLine' => 58,
            'startColumn' => 30,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => true,
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
 * Returns a set of names that could refer to this package
 *
 * No version or release type information should be included in any of the
 * names. Provided or replaced package names need to be returned as well.
 *
 * @param bool $provides Whether provided names should be included
 *
 * @return string[] An array of strings referring to this package
 */',
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 59,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'setId' => 
      array (
        'name' => 'setId',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 27,
            'endColumn' => 33,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Allows the solver to set an id for this package to refer to it.
 */',
        'startLine' => 63,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getId' => 
      array (
        'name' => 'getId',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieves the package\'s id set through setId
 *
 * @return int The previously set package id
 */',
        'startLine' => 70,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'isDev' => 
      array (
        'name' => 'isDev',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns whether the package is a development virtual package or a concrete one
 */',
        'startLine' => 75,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getType' => 
      array (
        'name' => 'getType',
        'parameters' => 
        array (
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
 * Returns the package type, e.g. library
 *
 * @return string The package type
 */',
        'startLine' => 82,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 38,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getTargetDir' => 
      array (
        'name' => 'getTargetDir',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the package targetDir property
 *
 * @return ?string The package targetDir
 */',
        'startLine' => 89,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getExtra' => 
      array (
        'name' => 'getExtra',
        'parameters' => 
        array (
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
 * Returns the package extra data
 *
 * @return mixed[] The package extra data
 */',
        'startLine' => 96,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 38,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'setInstallationSource' => 
      array (
        'name' => 'setInstallationSource',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 104,
            'endLine' => 104,
            'startColumn' => 43,
            'endColumn' => 55,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sets source from which this package was installed (source/dist).
 *
 * @param ?string $type source/dist
 * @phpstan-param \'source\'|\'dist\'|null $type
 */',
        'startLine' => 104,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 63,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getInstallationSource' => 
      array (
        'name' => 'getInstallationSource',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns source from which this package was installed (source/dist).
 *
 * @return ?string source/dist
 * @phpstan-return \'source\'|\'dist\'|null
 */',
        'startLine' => 112,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 53,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getSourceType' => 
      array (
        'name' => 'getSourceType',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the repository type of this package, e.g. git, svn
 *
 * @return ?string The repository type
 */',
        'startLine' => 119,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getSourceUrl' => 
      array (
        'name' => 'getSourceUrl',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the repository url of this package, e.g. git://github.com/naderman/composer.git
 *
 * @return ?string The repository url
 */',
        'startLine' => 126,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getSourceUrls' => 
      array (
        'name' => 'getSourceUrls',
        'parameters' => 
        array (
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
 * Returns the repository urls of this package including mirrors, e.g. git://github.com/naderman/composer.git
 *
 * @return list<string>
 */',
        'startLine' => 133,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getSourceReference' => 
      array (
        'name' => 'getSourceReference',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the repository reference of this package, e.g. master, 1.0.0 or a commit hash for git
 *
 * @return ?string The repository reference
 */',
        'startLine' => 140,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 50,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getSourceMirrors' => 
      array (
        'name' => 'getSourceMirrors',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'array',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the source mirrors of this package
 *
 * @return ?list<array{url: non-empty-string, preferred: bool}>
 */',
        'startLine' => 147,
        'endLine' => 147,
        'startColumn' => 5,
        'endColumn' => 47,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'setSourceMirrors' => 
      array (
        'name' => 'setSourceMirrors',
        'parameters' => 
        array (
          'mirrors' => 
          array (
            'name' => 'mirrors',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 38,
            'endColumn' => 52,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  null|list<array{url: non-empty-string, preferred: bool}> $mirrors
 */',
        'startLine' => 152,
        'endLine' => 152,
        'startColumn' => 5,
        'endColumn' => 60,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getDistType' => 
      array (
        'name' => 'getDistType',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the type of the distribution archive of this version, e.g. zip, tarball
 *
 * @return ?string The repository type
 */',
        'startLine' => 159,
        'endLine' => 159,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getDistUrl' => 
      array (
        'name' => 'getDistUrl',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the url of the distribution archive of this version
 *
 * @return ?non-empty-string
 */',
        'startLine' => 166,
        'endLine' => 166,
        'startColumn' => 5,
        'endColumn' => 42,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getDistUrls' => 
      array (
        'name' => 'getDistUrls',
        'parameters' => 
        array (
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
 * Returns the urls of the distribution archive of this version, including mirrors
 *
 * @return non-empty-string[]
 */',
        'startLine' => 173,
        'endLine' => 173,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getDistReference' => 
      array (
        'name' => 'getDistReference',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the reference of the distribution archive of this version, e.g. master, 1.0.0 or a commit hash for git
 */',
        'startLine' => 178,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 48,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getDistSha1Checksum' => 
      array (
        'name' => 'getDistSha1Checksum',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the sha1 checksum for the distribution archive of this version
 *
 * Can be an empty string which should be treated as null
 */',
        'startLine' => 185,
        'endLine' => 185,
        'startColumn' => 5,
        'endColumn' => 51,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getDistMirrors' => 
      array (
        'name' => 'getDistMirrors',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'array',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the dist mirrors of this package
 *
 * @return ?list<array{url: non-empty-string, preferred: bool}>
 */',
        'startLine' => 192,
        'endLine' => 192,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'setDistMirrors' => 
      array (
        'name' => 'setDistMirrors',
        'parameters' => 
        array (
          'mirrors' => 
          array (
            'name' => 'mirrors',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 197,
            'endLine' => 197,
            'startColumn' => 36,
            'endColumn' => 50,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  null|list<array{url: non-empty-string, preferred: bool}> $mirrors
 */',
        'startLine' => 197,
        'endLine' => 197,
        'startColumn' => 5,
        'endColumn' => 58,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getVersion' => 
      array (
        'name' => 'getVersion',
        'parameters' => 
        array (
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
 * Returns the version of this package
 *
 * @return string version
 */',
        'startLine' => 204,
        'endLine' => 204,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getPrettyVersion' => 
      array (
        'name' => 'getPrettyVersion',
        'parameters' => 
        array (
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
 * Returns the pretty (i.e. non-normalized) version string of this package
 *
 * @return string version
 */',
        'startLine' => 211,
        'endLine' => 211,
        'startColumn' => 5,
        'endColumn' => 47,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getFullPrettyVersion' => 
      array (
        'name' => 'getFullPrettyVersion',
        'parameters' => 
        array (
          'truncate' => 
          array (
            'name' => 'truncate',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 224,
                'endLine' => 224,
                'startTokenPos' => 473,
                'startFilePos' => 7128,
                'endTokenPos' => 473,
                'endFilePos' => 7131,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 224,
            'endLine' => 224,
            'startColumn' => 42,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'displayMode' => 
          array (
            'name' => 'displayMode',
            'default' => 
            array (
              'code' => 'self::DISPLAY_SOURCE_REF_IF_DEV',
              'attributes' => 
              array (
                'startLine' => 224,
                'endLine' => 224,
                'startTokenPos' => 482,
                'startFilePos' => 7153,
                'endTokenPos' => 484,
                'endFilePos' => 7183,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 224,
            'endLine' => 224,
            'startColumn' => 65,
            'endColumn' => 114,
            'parameterIndex' => 1,
            'isOptional' => true,
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
 * Returns the pretty version string plus a git or hg commit hash of this package
 *
 * @see getPrettyVersion
 *
 * @param  bool   $truncate    If the source reference is a sha1 hash, truncate it
 * @param  int    $displayMode One of the DISPLAY_ constants on this interface determining display of references
 * @return string version
 *
 * @phpstan-param self::DISPLAY_SOURCE_REF_IF_DEV|self::DISPLAY_SOURCE_REF|self::DISPLAY_DIST_REF $displayMode
 */',
        'startLine' => 224,
        'endLine' => 224,
        'startColumn' => 5,
        'endColumn' => 124,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getReleaseDate' => 
      array (
        'name' => 'getReleaseDate',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'DateTimeInterface',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the release date of the package
 */',
        'startLine' => 229,
        'endLine' => 229,
        'startColumn' => 5,
        'endColumn' => 58,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getStability' => 
      array (
        'name' => 'getStability',
        'parameters' => 
        array (
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
 * Returns the stability of this package: one of (dev, alpha, beta, RC, stable)
 *
 * @phpstan-return \'stable\'|\'RC\'|\'beta\'|\'alpha\'|\'dev\'
 */',
        'startLine' => 236,
        'endLine' => 236,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getRequires' => 
      array (
        'name' => 'getRequires',
        'parameters' => 
        array (
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
 * Returns a set of links to packages which need to be installed before
 * this package can be installed
 *
 * @return array<string, Link> A map of package links defining required packages, indexed by the require package\'s name
 */',
        'startLine' => 244,
        'endLine' => 244,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getConflicts' => 
      array (
        'name' => 'getConflicts',
        'parameters' => 
        array (
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
 * Returns a set of links to packages which must not be installed at the
 * same time as this package
 *
 * @return Link[] An array of package links defining conflicting packages
 */',
        'startLine' => 252,
        'endLine' => 252,
        'startColumn' => 5,
        'endColumn' => 42,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getProvides' => 
      array (
        'name' => 'getProvides',
        'parameters' => 
        array (
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
 * Returns a set of links to virtual packages that are provided through
 * this package
 *
 * @return Link[] An array of package links defining provided packages
 */',
        'startLine' => 260,
        'endLine' => 260,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getReplaces' => 
      array (
        'name' => 'getReplaces',
        'parameters' => 
        array (
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
 * Returns a set of links to packages which can alternatively be
 * satisfied by installing this package
 *
 * @return Link[] An array of package links defining replaced packages
 */',
        'startLine' => 268,
        'endLine' => 268,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getDevRequires' => 
      array (
        'name' => 'getDevRequires',
        'parameters' => 
        array (
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
 * Returns a set of links to packages which are required to develop
 * this package. These are installed if in dev mode.
 *
 * @return array<string, Link> A map of package links defining packages required for development, indexed by the require package\'s name
 */',
        'startLine' => 276,
        'endLine' => 276,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getSuggests' => 
      array (
        'name' => 'getSuggests',
        'parameters' => 
        array (
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
 * Returns a set of package names and reasons why they are useful in
 * combination with this package.
 *
 * @return array An array of package suggestions with descriptions
 * @phpstan-return array<string, string>
 */',
        'startLine' => 285,
        'endLine' => 285,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getAutoload' => 
      array (
        'name' => 'getAutoload',
        'parameters' => 
        array (
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
 * Returns an associative array of autoloading rules
 *
 * {"<type>": {"<namespace": "<directory>"}}
 *
 * Type is either "psr-4", "psr-0", "classmap" or "files". Namespaces are mapped to
 * directories for autoloading using the type specified.
 *
 * @return array Mapping of autoloading rules
 * @phpstan-return AutoloadRules
 */',
        'startLine' => 298,
        'endLine' => 298,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getDevAutoload' => 
      array (
        'name' => 'getDevAutoload',
        'parameters' => 
        array (
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
 * Returns an associative array of dev autoloading rules
 *
 * {"<type>": {"<namespace": "<directory>"}}
 *
 * Type is either "psr-4", "psr-0", "classmap" or "files". Namespaces are mapped to
 * directories for autoloading using the type specified.
 *
 * @return array Mapping of dev autoloading rules
 * @phpstan-return DevAutoloadRules
 */',
        'startLine' => 311,
        'endLine' => 311,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getIncludePaths' => 
      array (
        'name' => 'getIncludePaths',
        'parameters' => 
        array (
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
 * Returns a list of directories which should get added to PHP\'s
 * include path.
 *
 * @return string[]
 */',
        'startLine' => 319,
        'endLine' => 319,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getPhpExt' => 
      array (
        'name' => 'getPhpExt',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'array',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the settings for php extension packages
 *
 *
 * @phpstan-return PhpExtConfig|null
 */',
        'startLine' => 327,
        'endLine' => 327,
        'startColumn' => 5,
        'endColumn' => 40,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'setRepository' => 
      array (
        'name' => 'setRepository',
        'parameters' => 
        array (
          'repository' => 
          array (
            'name' => 'repository',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Composer\\Repository\\RepositoryInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 332,
            'endLine' => 332,
            'startColumn' => 35,
            'endColumn' => 65,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Stores a reference to the repository that owns the package
 */',
        'startLine' => 332,
        'endLine' => 332,
        'startColumn' => 5,
        'endColumn' => 73,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getRepository' => 
      array (
        'name' => 'getRepository',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'Composer\\Repository\\RepositoryInterface',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a reference to the repository that owns the package
 */',
        'startLine' => 337,
        'endLine' => 337,
        'startColumn' => 5,
        'endColumn' => 58,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getBinaries' => 
      array (
        'name' => 'getBinaries',
        'parameters' => 
        array (
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
 * Returns the package binaries
 *
 * @return string[]
 */',
        'startLine' => 344,
        'endLine' => 344,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getUniqueName' => 
      array (
        'name' => 'getUniqueName',
        'parameters' => 
        array (
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
 * Returns package unique name, constructed from name and version.
 */',
        'startLine' => 349,
        'endLine' => 349,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getNotificationUrl' => 
      array (
        'name' => 'getNotificationUrl',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the package notification url
 */',
        'startLine' => 354,
        'endLine' => 354,
        'startColumn' => 5,
        'endColumn' => 50,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      '__toString' => 
      array (
        'name' => '__toString',
        'parameters' => 
        array (
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
 * Converts the package into a readable and unique string
 */',
        'startLine' => 359,
        'endLine' => 359,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getPrettyString' => 
      array (
        'name' => 'getPrettyString',
        'parameters' => 
        array (
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
 * Converts the package into a pretty readable string
 */',
        'startLine' => 364,
        'endLine' => 364,
        'startColumn' => 5,
        'endColumn' => 46,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'isDefaultBranch' => 
      array (
        'name' => 'isDefaultBranch',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 366,
        'endLine' => 366,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'getTransportOptions' => 
      array (
        'name' => 'getTransportOptions',
        'parameters' => 
        array (
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
 * Returns a list of options to download package dist files
 *
 * @return mixed[]
 */',
        'startLine' => 373,
        'endLine' => 373,
        'startColumn' => 5,
        'endColumn' => 49,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'setTransportOptions' => 
      array (
        'name' => 'setTransportOptions',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
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
            'startLine' => 380,
            'endLine' => 380,
            'startColumn' => 41,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Configures the list of options to download package dist files
 *
 * @param mixed[] $options
 */',
        'startLine' => 380,
        'endLine' => 380,
        'startColumn' => 5,
        'endColumn' => 62,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'setSourceReference' => 
      array (
        'name' => 'setSourceReference',
        'parameters' => 
        array (
          'reference' => 
          array (
            'name' => 'reference',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 382,
            'endLine' => 382,
            'startColumn' => 40,
            'endColumn' => 57,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 382,
        'endLine' => 382,
        'startColumn' => 5,
        'endColumn' => 65,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'setDistUrl' => 
      array (
        'name' => 'setDistUrl',
        'parameters' => 
        array (
          'url' => 
          array (
            'name' => 'url',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 384,
            'endLine' => 384,
            'startColumn' => 32,
            'endColumn' => 43,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 384,
        'endLine' => 384,
        'startColumn' => 5,
        'endColumn' => 51,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'setDistType' => 
      array (
        'name' => 'setDistType',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 386,
            'endLine' => 386,
            'startColumn' => 33,
            'endColumn' => 45,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 386,
        'endLine' => 386,
        'startColumn' => 5,
        'endColumn' => 53,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'setDistReference' => 
      array (
        'name' => 'setDistReference',
        'parameters' => 
        array (
          'reference' => 
          array (
            'name' => 'reference',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 388,
            'endLine' => 388,
            'startColumn' => 38,
            'endColumn' => 55,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 388,
        'endLine' => 388,
        'startColumn' => 5,
        'endColumn' => 63,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
        'aliasName' => NULL,
      ),
      'setSourceDistReferences' => 
      array (
        'name' => 'setSourceDistReferences',
        'parameters' => 
        array (
          'reference' => 
          array (
            'name' => 'reference',
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
            'startLine' => 393,
            'endLine' => 393,
            'startColumn' => 45,
            'endColumn' => 61,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set dist and source references and update dist URL for ones that contain a reference
 */',
        'startLine' => 393,
        'endLine' => 393,
        'startColumn' => 5,
        'endColumn' => 69,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer\\Package',
        'declaringClassName' => 'Composer\\Package\\PackageInterface',
        'implementingClassName' => 'Composer\\Package\\PackageInterface',
        'currentClassName' => 'Composer\\Package\\PackageInterface',
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