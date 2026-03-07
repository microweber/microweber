<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/process/PhpExecutableFinder.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Symfony\Component\Process\PhpExecutableFinder
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-4697ea736892b5fe645f5ab1233feca27237021939bb54e5b94a1045932da12c-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Symfony\\Component\\Process\\PhpExecutableFinder',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/process/PhpExecutableFinder.php',
      ),
    ),
    'namespace' => 'Symfony\\Component\\Process',
    'name' => 'Symfony\\Component\\Process\\PhpExecutableFinder',
    'shortName' => 'PhpExecutableFinder',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * An executable finder specifically designed for the PHP executable.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 98,
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
      'executableFinder' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\Process\\PhpExecutableFinder',
        'implementingClassName' => 'Symfony\\Component\\Process\\PhpExecutableFinder',
        'name' => 'executableFinder',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Symfony\\Component\\Process\\ExecutableFinder',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 47,
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
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 24,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Process',
        'declaringClassName' => 'Symfony\\Component\\Process\\PhpExecutableFinder',
        'implementingClassName' => 'Symfony\\Component\\Process\\PhpExecutableFinder',
        'currentClassName' => 'Symfony\\Component\\Process\\PhpExecutableFinder',
        'aliasName' => NULL,
      ),
      'find' => 
      array (
        'name' => 'find',
        'parameters' => 
        array (
          'includeArgs' => 
          array (
            'name' => 'includeArgs',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 32,
                'endLine' => 32,
                'startTokenPos' => 63,
                'startFilePos' => 736,
                'endTokenPos' => 63,
                'endFilePos' => 739,
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
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 26,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
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
                  'name' => 'false',
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
 * Finds The PHP executable.
 */',
        'startLine' => 32,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Process',
        'declaringClassName' => 'Symfony\\Component\\Process\\PhpExecutableFinder',
        'implementingClassName' => 'Symfony\\Component\\Process\\PhpExecutableFinder',
        'currentClassName' => 'Symfony\\Component\\Process\\PhpExecutableFinder',
        'aliasName' => NULL,
      ),
      'findArguments' => 
      array (
        'name' => 'findArguments',
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
 * Finds the PHP executable arguments.
 *
 * @return list<non-empty-string>
 */',
        'startLine' => 89,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Process',
        'declaringClassName' => 'Symfony\\Component\\Process\\PhpExecutableFinder',
        'implementingClassName' => 'Symfony\\Component\\Process\\PhpExecutableFinder',
        'currentClassName' => 'Symfony\\Component\\Process\\PhpExecutableFinder',
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