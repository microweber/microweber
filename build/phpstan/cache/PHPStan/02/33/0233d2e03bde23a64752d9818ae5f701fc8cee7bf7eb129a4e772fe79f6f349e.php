<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Http/Concerns/InteractsWithContentTypes.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Http\Concerns\InteractsWithContentTypes
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-c218c1865944decf23f907e07e6281db3a57ab5140b09524838862609255dd76-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Http/Concerns/InteractsWithContentTypes.php',
      ),
    ),
    'namespace' => 'Illuminate\\Http\\Concerns',
    'name' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
    'shortName' => 'InteractsWithContentTypes',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 187,
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
      'isJson' => 
      array (
        'name' => 'isJson',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the request is sending JSON.
 *
 * @return bool
 */',
        'startLine' => 14,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'aliasName' => NULL,
      ),
      'expectsJson' => 
      array (
        'name' => 'expectsJson',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the current request probably expects a JSON response.
 *
 * @return bool
 */',
        'startLine' => 24,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'aliasName' => NULL,
      ),
      'wantsJson' => 
      array (
        'name' => 'wantsJson',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the current request is asking for JSON.
 *
 * @return bool
 */',
        'startLine' => 34,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'aliasName' => NULL,
      ),
      'accepts' => 
      array (
        'name' => 'accepts',
        'parameters' => 
        array (
          'contentTypes' => 
          array (
            'name' => 'contentTypes',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 29,
            'endColumn' => 41,
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
 * Determines whether the current requests accepts a given content type.
 *
 * @param  string|array  $contentTypes
 * @return bool
 */',
        'startLine' => 47,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'aliasName' => NULL,
      ),
      'prefers' => 
      array (
        'name' => 'prefers',
        'parameters' => 
        array (
          'contentTypes' => 
          array (
            'name' => 'contentTypes',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 86,
            'endLine' => 86,
            'startColumn' => 29,
            'endColumn' => 41,
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
 * Return the most suitable content type from the given array based on content negotiation.
 *
 * @param  string|array  $contentTypes
 * @return string|null
 */',
        'startLine' => 86,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'aliasName' => NULL,
      ),
      'acceptsAnyContentType' => 
      array (
        'name' => 'acceptsAnyContentType',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the current request accepts any content type.
 *
 * @return bool
 */',
        'startLine' => 124,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'aliasName' => NULL,
      ),
      'acceptsJson' => 
      array (
        'name' => 'acceptsJson',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determines whether a request accepts JSON.
 *
 * @return bool
 */',
        'startLine' => 138,
        'endLine' => 141,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'aliasName' => NULL,
      ),
      'acceptsHtml' => 
      array (
        'name' => 'acceptsHtml',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determines whether a request accepts HTML.
 *
 * @return bool
 */',
        'startLine' => 148,
        'endLine' => 151,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'aliasName' => NULL,
      ),
      'matchesType' => 
      array (
        'name' => 'matchesType',
        'parameters' => 
        array (
          'actual' => 
          array (
            'name' => 'actual',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 160,
            'endLine' => 160,
            'startColumn' => 40,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 160,
            'endLine' => 160,
            'startColumn' => 49,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the given content types match.
 *
 * @param  string  $actual
 * @param  string  $type
 * @return bool
 */',
        'startLine' => 160,
        'endLine' => 169,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'aliasName' => NULL,
      ),
      'format' => 
      array (
        'name' => 'format',
        'parameters' => 
        array (
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => '\'html\'',
              'attributes' => 
              array (
                'startLine' => 177,
                'endLine' => 177,
                'startTokenPos' => 854,
                'startFilePos' => 4396,
                'endTokenPos' => 854,
                'endFilePos' => 4401,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 177,
            'endLine' => 177,
            'startColumn' => 28,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the data format expected in the response.
 *
 * @param  string  $default
 * @return string
 */',
        'startLine' => 177,
        'endLine' => 186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithContentTypes',
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