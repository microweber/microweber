<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Traits/ReflectsClosures.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Support\Traits\ReflectsClosures
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-22914bc5f79369d5ab478fdc9e264f4d7c1d01d63b30fbb9426bcd1ad45532b2-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Support\\Traits\\ReflectsClosures',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Traits/ReflectsClosures.php',
      ),
    ),
    'namespace' => 'Illuminate\\Support\\Traits',
    'name' => 'Illuminate\\Support\\Traits\\ReflectsClosures',
    'shortName' => 'ReflectsClosures',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 89,
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
      'firstClosureParameterType' => 
      array (
        'name' => 'firstClosureParameterType',
        'parameters' => 
        array (
          'closure' => 
          array (
            'name' => 'closure',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 50,
            'endColumn' => 65,
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
 * Get the class name of the first parameter of the given Closure.
 *
 * @param  \\Closure  $closure
 * @return string
 *
 * @throws \\ReflectionException
 * @throws \\RuntimeException
 */',
        'startLine' => 22,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\ReflectsClosures',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\ReflectsClosures',
        'currentClassName' => 'Illuminate\\Support\\Traits\\ReflectsClosures',
        'aliasName' => NULL,
      ),
      'firstClosureParameterTypes' => 
      array (
        'name' => 'firstClosureParameterTypes',
        'parameters' => 
        array (
          'closure' => 
          array (
            'name' => 'closure',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 46,
            'endLine' => 46,
            'startColumn' => 51,
            'endColumn' => 66,
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
 * Get the class names of the first parameter of the given Closure, including union types.
 *
 * @param  \\Closure  $closure
 * @return array
 *
 * @throws \\ReflectionException
 * @throws \\RuntimeException
 */',
        'startLine' => 46,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\ReflectsClosures',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\ReflectsClosures',
        'currentClassName' => 'Illuminate\\Support\\Traits\\ReflectsClosures',
        'aliasName' => NULL,
      ),
      'closureParameterTypes' => 
      array (
        'name' => 'closureParameterTypes',
        'parameters' => 
        array (
          'closure' => 
          array (
            'name' => 'closure',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 77,
            'endLine' => 77,
            'startColumn' => 46,
            'endColumn' => 61,
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
 * Get the class names / types of the parameters of the given Closure.
 *
 * @param  \\Closure  $closure
 * @return array
 *
 * @throws \\ReflectionException
 */',
        'startLine' => 77,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\ReflectsClosures',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\ReflectsClosures',
        'currentClassName' => 'Illuminate\\Support\\Traits\\ReflectsClosures',
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