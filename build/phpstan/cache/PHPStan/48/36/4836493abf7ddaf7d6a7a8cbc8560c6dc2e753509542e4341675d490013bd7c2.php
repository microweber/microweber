<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/mime/Part/AbstractMultipartPart.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Symfony\Component\Mime\Part\AbstractMultipartPart
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-a8d1e9bfa6178cf57bc622e298ec0bcd82695705da6adc8ebf37f40e4cf5b1c9-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/mime/Part/AbstractMultipartPart.php',
      ),
    ),
    'namespace' => 'Symfony\\Component\\Mime\\Part',
    'name' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
    'shortName' => 'AbstractMultipartPart',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => '/**
 * @author Fabien Potencier <fabien@symfony.com>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 95,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractPart',
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
      'boundary' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'implementingClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'name' => 'boundary',
        'modifiers' => 4,
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
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 37,
            'startFilePos' => 471,
            'endTokenPos' => 37,
            'endFilePos' => 474,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'parts' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'implementingClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'name' => 'parts',
        'modifiers' => 4,
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
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 48,
            'startFilePos' => 504,
            'endTokenPos' => 49,
            'endFilePos' => 505,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 30,
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
          'parts' => 
          array (
            'name' => 'parts',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\Mime\\Part\\AbstractPart',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 33,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 24,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mime\\Part',
        'declaringClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'implementingClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'currentClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'aliasName' => NULL,
      ),
      'getParts' => 
      array (
        'name' => 'getParts',
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
 * @return AbstractPart[]
 */',
        'startLine' => 36,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mime\\Part',
        'declaringClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'implementingClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'currentClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'aliasName' => NULL,
      ),
      'getMediaType' => 
      array (
        'name' => 'getMediaType',
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
        'startLine' => 41,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mime\\Part',
        'declaringClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'implementingClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'currentClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'aliasName' => NULL,
      ),
      'getPreparedHeaders' => 
      array (
        'name' => 'getPreparedHeaders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Symfony\\Component\\Mime\\Header\\Headers',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 46,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mime\\Part',
        'declaringClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'implementingClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'currentClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'aliasName' => NULL,
      ),
      'bodyToString' => 
      array (
        'name' => 'bodyToString',
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
        'startLine' => 54,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mime\\Part',
        'declaringClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'implementingClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'currentClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'aliasName' => NULL,
      ),
      'bodyToIterable' => 
      array (
        'name' => 'bodyToIterable',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'iterable',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 66,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => true,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mime\\Part',
        'declaringClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'implementingClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'currentClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'aliasName' => NULL,
      ),
      'asDebugString' => 
      array (
        'name' => 'asDebugString',
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
        'startLine' => 77,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mime\\Part',
        'declaringClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'implementingClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'currentClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'aliasName' => NULL,
      ),
      'getBoundary' => 
      array (
        'name' => 'getBoundary',
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
        'startLine' => 91,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Symfony\\Component\\Mime\\Part',
        'declaringClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'implementingClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
        'currentClassName' => 'Symfony\\Component\\Mime\\Part\\AbstractMultipartPart',
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