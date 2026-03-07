<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Concerns/CompilesJsonPaths.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Database\Concerns\CompilesJsonPaths
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-ef2946ce3fcaf6ebc219a271967dbb87983d819121c1091ecf2670639705d13f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Database\\Concerns\\CompilesJsonPaths',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Concerns/CompilesJsonPaths.php',
      ),
    ),
    'namespace' => 'Illuminate\\Database\\Concerns',
    'name' => 'Illuminate\\Database\\Concerns\\CompilesJsonPaths',
    'shortName' => 'CompilesJsonPaths',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 65,
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
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'wrapJsonFieldAndPath' => 
      array (
        'name' => 'wrapJsonFieldAndPath',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 16,
            'endLine' => 16,
            'startColumn' => 45,
            'endColumn' => 51,
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
 * Split the given JSON selector into the field and the optional path and wrap them separately.
 *
 * @param  string  $column
 * @return array
 */',
        'startLine' => 16,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\CompilesJsonPaths',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\CompilesJsonPaths',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\CompilesJsonPaths',
        'aliasName' => NULL,
      ),
      'wrapJsonPath' => 
      array (
        'name' => 'wrapJsonPath',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 37,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'delimiter' => 
          array (
            'name' => 'delimiter',
            'default' => 
            array (
              'code' => '\'->\'',
              'attributes' => 
              array (
                'startLine' => 34,
                'endLine' => 34,
                'startTokenPos' => 129,
                'startFilePos' => 782,
                'endTokenPos' => 129,
                'endFilePos' => 785,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 45,
            'endColumn' => 61,
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
 * Wrap the given JSON path.
 *
 * @param  string  $value
 * @param  string  $delimiter
 * @return string
 */',
        'startLine' => 34,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\CompilesJsonPaths',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\CompilesJsonPaths',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\CompilesJsonPaths',
        'aliasName' => NULL,
      ),
      'wrapJsonPathSegment' => 
      array (
        'name' => 'wrapJsonPathSegment',
        'parameters' => 
        array (
          'segment' => 
          array (
            'name' => 'segment',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 44,
            'endColumn' => 51,
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
 * Wrap the given JSON path segment.
 *
 * @param  string  $segment
 * @return string
 */',
        'startLine' => 51,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\CompilesJsonPaths',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\CompilesJsonPaths',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\CompilesJsonPaths',
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