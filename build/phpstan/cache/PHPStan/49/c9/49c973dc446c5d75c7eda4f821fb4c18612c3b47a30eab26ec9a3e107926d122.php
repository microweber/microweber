<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../nwidart/laravel-modules/src/Generators/ModuleGenerator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Nwidart\Modules\Generators\ModuleGenerator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-11608561d4c0ad796cc053f8a6b3b051afbeff32a5bca64c6068d91b27b3f20d-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../nwidart/laravel-modules/src/Generators/ModuleGenerator.php',
      ),
    ),
    'namespace' => 'Nwidart\\Modules\\Generators',
    'name' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
    'shortName' => 'ModuleGenerator',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 615,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Nwidart\\Modules\\Generators\\Generator',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Nwidart\\Modules\\Traits\\PathNamespace',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'name' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'name' => 'name',
        'modifiers' => 2,
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 106,
            'startFilePos' => 725,
            'endTokenPos' => 106,
            'endFilePos' => 728,
          ),
        ),
        'docComment' => '/**
 * The module name will created.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'config' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'name' => 'config',
        'modifiers' => 2,
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
                  'name' => 'Illuminate\\Config\\Repository',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 120,
            'startFilePos' => 816,
            'endTokenPos' => 120,
            'endFilePos' => 819,
          ),
        ),
        'docComment' => '/**
 * The laravel config instance.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'filesystem' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'name' => 'filesystem',
        'modifiers' => 2,
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
                  'name' => 'Illuminate\\Filesystem\\Filesystem',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 134,
            'startFilePos' => 919,
            'endTokenPos' => 134,
            'endFilePos' => 922,
          ),
        ),
        'docComment' => '/**
 * The laravel filesystem instance.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 45,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'console' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'name' => 'console',
        'modifiers' => 2,
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
                  'name' => 'Illuminate\\Console\\Command',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 148,
            'startFilePos' => 1013,
            'endTokenPos' => 148,
            'endFilePos' => 1016,
          ),
        ),
        'docComment' => '/**
 * The laravel console instance.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'component' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'name' => 'component',
        'modifiers' => 2,
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
                  'name' => 'Illuminate\\Console\\View\\Components\\Factory',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 162,
            'startFilePos' => 1119,
            'endTokenPos' => 162,
            'endFilePos' => 1122,
          ),
        ),
        'docComment' => '/**
 * The laravel component Factory instance.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'activator' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'name' => 'activator',
        'modifiers' => 2,
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
                  'name' => 'Nwidart\\Modules\\Contracts\\ActivatorInterface',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 176,
            'startFilePos' => 1219,
            'endTokenPos' => 176,
            'endFilePos' => 1222,
          ),
        ),
        'docComment' => '/**
 * The activator instance
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 52,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'module' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'name' => 'module',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 57,
            'endLine' => 57,
            'startTokenPos' => 191,
            'startFilePos' => 1316,
            'endTokenPos' => 191,
            'endFilePos' => 1319,
          ),
        ),
        'docComment' => '/**
 * The module instance.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'force' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'name' => 'force',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 62,
            'endLine' => 62,
            'startTokenPos' => 204,
            'startFilePos' => 1388,
            'endTokenPos' => 204,
            'endFilePos' => 1392,
          ),
        ),
        'docComment' => '/**
 * Force status.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 62,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'type' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'name' => 'type',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '\'web\'',
          'attributes' => 
          array (
            'startLine' => 67,
            'endLine' => 67,
            'startTokenPos' => 217,
            'startFilePos' => 1473,
            'endTokenPos' => 217,
            'endFilePos' => 1477,
          ),
        ),
        'docComment' => '/**
 * set default module type.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 67,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isActive' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'name' => 'isActive',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 72,
            'endLine' => 72,
            'startTokenPos' => 230,
            'startFilePos' => 1555,
            'endTokenPos' => 230,
            'endFilePos' => 1559,
          ),
        ),
        'docComment' => '/**
 * Enables the module.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 72,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'author' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'name' => 'author',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[\'name\', \'email\']',
          'attributes' => 
          array (
            'startLine' => 77,
            'endLine' => 79,
            'startTokenPos' => 243,
            'startFilePos' => 1630,
            'endTokenPos' => 251,
            'endFilePos' => 1661,
          ),
        ),
        'docComment' => '/**
 * Module author
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 77,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'vendor' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'name' => 'vendor',
        'modifiers' => 2,
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 84,
            'endLine' => 84,
            'startTokenPos' => 265,
            'startFilePos' => 1732,
            'endTokenPos' => 265,
            'endFilePos' => 1735,
          ),
        ),
        'docComment' => '/**
 * Vendor name
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 84,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 90,
            'endLine' => 90,
            'startColumn' => 9,
            'endColumn' => 13,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'module' => 
          array (
            'name' => 'module',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 91,
                'endLine' => 91,
                'startTokenPos' => 287,
                'startFilePos' => 1861,
                'endTokenPos' => 287,
                'endFilePos' => 1864,
              ),
            ),
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
                      'name' => 'Nwidart\\Modules\\FileRepository',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 91,
            'endLine' => 91,
            'startColumn' => 9,
            'endColumn' => 38,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'config' => 
          array (
            'name' => 'config',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 92,
                'endLine' => 92,
                'startTokenPos' => 297,
                'startFilePos' => 1893,
                'endTokenPos' => 297,
                'endFilePos' => 1896,
              ),
            ),
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
                      'name' => 'Illuminate\\Config\\Repository',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 92,
            'endLine' => 92,
            'startColumn' => 9,
            'endColumn' => 30,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'filesystem' => 
          array (
            'name' => 'filesystem',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 93,
                'endLine' => 93,
                'startTokenPos' => 307,
                'startFilePos' => 1933,
                'endTokenPos' => 307,
                'endFilePos' => 1936,
              ),
            ),
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
                      'name' => 'Illuminate\\Filesystem\\Filesystem',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 9,
            'endColumn' => 38,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'console' => 
          array (
            'name' => 'console',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 94,
                'endLine' => 94,
                'startTokenPos' => 317,
                'startFilePos' => 1967,
                'endTokenPos' => 317,
                'endFilePos' => 1970,
              ),
            ),
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
                      'name' => 'Illuminate\\Console\\Command',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 94,
            'endLine' => 94,
            'startColumn' => 9,
            'endColumn' => 32,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'activator' => 
          array (
            'name' => 'activator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 95,
                'endLine' => 95,
                'startTokenPos' => 327,
                'startFilePos' => 2014,
                'endTokenPos' => 327,
                'endFilePos' => 2017,
              ),
            ),
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
                      'name' => 'Nwidart\\Modules\\Contracts\\ActivatorInterface',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 95,
            'endLine' => 95,
            'startColumn' => 9,
            'endColumn' => 45,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The constructor.
 */',
        'startLine' => 89,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'setType' => 
      array (
        'name' => 'setType',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
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
            'startLine' => 108,
            'endLine' => 108,
            'startColumn' => 29,
            'endColumn' => 40,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set type.
 */',
        'startLine' => 108,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'setActive' => 
      array (
        'name' => 'setActive',
        'parameters' => 
        array (
          'active' => 
          array (
            'name' => 'active',
            'default' => NULL,
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
            'startLine' => 118,
            'endLine' => 118,
            'startColumn' => 31,
            'endColumn' => 42,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set active flag.
 */',
        'startLine' => 118,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
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
 * Get the name of module that will be created (in StudlyCase).
 */',
        'startLine' => 128,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getConfig' => 
      array (
        'name' => 'getConfig',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Config\\Repository',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the laravel config instance.
 */',
        'startLine' => 136,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'setConfig' => 
      array (
        'name' => 'setConfig',
        'parameters' => 
        array (
          'config' => 
          array (
            'name' => 'config',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Config\\Repository',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 144,
            'endLine' => 144,
            'startColumn' => 31,
            'endColumn' => 44,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the laravel config instance.
 */',
        'startLine' => 144,
        'endLine' => 149,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'setActivator' => 
      array (
        'name' => 'setActivator',
        'parameters' => 
        array (
          'activator' => 
          array (
            'name' => 'activator',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Nwidart\\Modules\\Contracts\\ActivatorInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 154,
            'endLine' => 154,
            'startColumn' => 34,
            'endColumn' => 62,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the modules activator
 */',
        'startLine' => 154,
        'endLine' => 159,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getFilesystem' => 
      array (
        'name' => 'getFilesystem',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Filesystem\\Filesystem',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the laravel filesystem instance.
 */',
        'startLine' => 164,
        'endLine' => 167,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'setFilesystem' => 
      array (
        'name' => 'setFilesystem',
        'parameters' => 
        array (
          'filesystem' => 
          array (
            'name' => 'filesystem',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Filesystem\\Filesystem',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 172,
            'endLine' => 172,
            'startColumn' => 35,
            'endColumn' => 56,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the laravel filesystem instance.
 */',
        'startLine' => 172,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getConsole' => 
      array (
        'name' => 'getConsole',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Console\\Command',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the laravel console instance.
 */',
        'startLine' => 182,
        'endLine' => 185,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'setConsole' => 
      array (
        'name' => 'setConsole',
        'parameters' => 
        array (
          'console' => 
          array (
            'name' => 'console',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Console\\Command',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 190,
            'endLine' => 190,
            'startColumn' => 32,
            'endColumn' => 47,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the laravel console instance.
 */',
        'startLine' => 190,
        'endLine' => 195,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getComponent' => 
      array (
        'name' => 'getComponent',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Console\\View\\Components\\Factory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 197,
        'endLine' => 200,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'setComponent' => 
      array (
        'name' => 'setComponent',
        'parameters' => 
        array (
          'component' => 
          array (
            'name' => 'component',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Console\\View\\Components\\Factory',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 202,
            'endLine' => 202,
            'startColumn' => 34,
            'endColumn' => 87,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 202,
        'endLine' => 207,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getModule' => 
      array (
        'name' => 'getModule',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Nwidart\\Modules\\Module',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the module instance.
 */',
        'startLine' => 212,
        'endLine' => 215,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'setModule' => 
      array (
        'name' => 'setModule',
        'parameters' => 
        array (
          'module' => 
          array (
            'name' => 'module',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 220,
            'endLine' => 220,
            'startColumn' => 31,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the module instance.
 */',
        'startLine' => 220,
        'endLine' => 225,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'setAuthor' => 
      array (
        'name' => 'setAuthor',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 230,
                'endLine' => 230,
                'startTokenPos' => 821,
                'startFilePos' => 4619,
                'endTokenPos' => 821,
                'endFilePos' => 4622,
              ),
            ),
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
            'startLine' => 230,
            'endLine' => 230,
            'startColumn' => 31,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'email' => 
          array (
            'name' => 'email',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 230,
                'endLine' => 230,
                'startTokenPos' => 831,
                'startFilePos' => 4642,
                'endTokenPos' => 831,
                'endFilePos' => 4645,
              ),
            ),
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
            'startLine' => 230,
            'endLine' => 230,
            'startColumn' => 53,
            'endColumn' => 73,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Setting the author from the command
 */',
        'startLine' => 230,
        'endLine' => 236,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'setVendor' => 
      array (
        'name' => 'setVendor',
        'parameters' => 
        array (
          'vendor' => 
          array (
            'name' => 'vendor',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 241,
                'endLine' => 241,
                'startTokenPos' => 885,
                'startFilePos' => 4876,
                'endTokenPos' => 885,
                'endFilePos' => 4879,
              ),
            ),
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
            'startLine' => 241,
            'endLine' => 241,
            'startColumn' => 31,
            'endColumn' => 52,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Installing vendor from the command
 */',
        'startLine' => 241,
        'endLine' => 246,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getFolders' => 
      array (
        'name' => 'getFolders',
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
 * Get the list of folders will created.
 */',
        'startLine' => 251,
        'endLine' => 254,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getFiles' => 
      array (
        'name' => 'getFiles',
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
 * Get the list of files will created.
 */',
        'startLine' => 259,
        'endLine' => 262,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'setForce' => 
      array (
        'name' => 'setForce',
        'parameters' => 
        array (
          'force' => 
          array (
            'name' => 'force',
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
                      'name' => 'bool',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
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
            'startLine' => 267,
            'endLine' => 267,
            'startColumn' => 30,
            'endColumn' => 44,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set force status.
 */',
        'startLine' => 267,
        'endLine' => 272,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'generate' => 
      array (
        'name' => 'generate',
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
 * Generate the module.
 */',
        'startLine' => 277,
        'endLine' => 317,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'generateFolders' => 
      array (
        'name' => 'generateFolders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generate the folders.
 */',
        'startLine' => 322,
        'endLine' => 338,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'generateGitKeep' => 
      array (
        'name' => 'generateGitKeep',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
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
            'startLine' => 343,
            'endLine' => 343,
            'startColumn' => 37,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generate git keep to the specified path.
 */',
        'startLine' => 343,
        'endLine' => 346,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'generateFiles' => 
      array (
        'name' => 'generateFiles',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generate the files.
 */',
        'startLine' => 351,
        'endLine' => 364,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'generateResources' => 
      array (
        'name' => 'generateResources',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generate some resources.
 */',
        'startLine' => 369,
        'endLine' => 441,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getStubContents' => 
      array (
        'name' => 'getStubContents',
        'parameters' => 
        array (
          'stub' => 
          array (
            'name' => 'stub',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 446,
            'endLine' => 446,
            'startColumn' => 40,
            'endColumn' => 44,
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
 * Get the contents of the specified stub file by given stub name.
 */',
        'startLine' => 446,
        'endLine' => 453,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getReplacements' => 
      array (
        'name' => 'getReplacements',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * get the list for the replacements.
 */',
        'startLine' => 458,
        'endLine' => 461,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getReplacement' => 
      array (
        'name' => 'getReplacement',
        'parameters' => 
        array (
          'stub' => 
          array (
            'name' => 'stub',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 466,
            'endLine' => 466,
            'startColumn' => 39,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get array replacement for the specified stub.
 */',
        'startLine' => 466,
        'endLine' => 496,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'generateModuleJsonFile' => 
      array (
        'name' => 'generateModuleJsonFile',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generate the module.json file
 */',
        'startLine' => 501,
        'endLine' => 512,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'cleanModuleJsonFile' => 
      array (
        'name' => 'cleanModuleJsonFile',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Remove the default service provider that was added in the module.json file
 * This is needed when a --plain module was created
 */',
        'startLine' => 518,
        'endLine' => 531,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getLowerNameReplacement' => 
      array (
        'name' => 'getLowerNameReplacement',
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
 * Get the module name in lower case.
 */',
        'startLine' => 536,
        'endLine' => 539,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getStudlyNameReplacement' => 
      array (
        'name' => 'getStudlyNameReplacement',
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
 * Get the module name in studly case.
 */',
        'startLine' => 544,
        'endLine' => 547,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getVendorReplacement' => 
      array (
        'name' => 'getVendorReplacement',
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
 * Get replacement for $VENDOR$.
 */',
        'startLine' => 552,
        'endLine' => 555,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getModuleNamespaceReplacement' => 
      array (
        'name' => 'getModuleNamespaceReplacement',
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
 * Get replacement for $MODULE_NAMESPACE$.
 */',
        'startLine' => 560,
        'endLine' => 563,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getControllerNamespaceReplacement' => 
      array (
        'name' => 'getControllerNamespaceReplacement',
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
 * Get replacement for $CONTROLLER_NAMESPACE$.
 */',
        'startLine' => 568,
        'endLine' => 575,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getAuthorNameReplacement' => 
      array (
        'name' => 'getAuthorNameReplacement',
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
 * Get replacement for $AUTHOR_NAME$.
 */',
        'startLine' => 580,
        'endLine' => 583,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getAuthorEmailReplacement' => 
      array (
        'name' => 'getAuthorEmailReplacement',
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
 * Get replacement for $AUTHOR_EMAIL$.
 */',
        'startLine' => 588,
        'endLine' => 591,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getAppFolderNameReplacement' => 
      array (
        'name' => 'getAppFolderNameReplacement',
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
 * Get replacement for $APP_FOLDER_NAME$.
 */',
        'startLine' => 596,
        'endLine' => 599,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'getProviderNamespaceReplacement' => 
      array (
        'name' => 'getProviderNamespaceReplacement',
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
        'docComment' => NULL,
        'startLine' => 601,
        'endLine' => 604,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'aliasName' => NULL,
      ),
      'fireEvent' => 
      array (
        'name' => 'fireEvent',
        'parameters' => 
        array (
          'event' => 
          array (
            'name' => 'event',
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
            'startLine' => 609,
            'endLine' => 609,
            'startColumn' => 34,
            'endColumn' => 46,
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
 * fire the module event.
 */',
        'startLine' => 609,
        'endLine' => 614,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Generators',
        'declaringClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'implementingClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
        'currentClassName' => 'Nwidart\\Modules\\Generators\\ModuleGenerator',
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