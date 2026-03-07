<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../intervention/image/src/Interfaces/FileInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Intervention\Image\Interfaces\FileInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-1c321507273931d113b59392bdc8fb5b04c068f1828b0739366b6cb5d9b8b822-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Intervention\\Image\\Interfaces\\FileInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../intervention/image/src/Interfaces/FileInterface.php',
      ),
    ),
    'namespace' => 'Intervention\\Image\\Interfaces',
    'name' => 'Intervention\\Image\\Interfaces\\FileInterface',
    'shortName' => 'FileInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 39,
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
      'save' => 
      array (
        'name' => 'save',
        'parameters' => 
        array (
          'filepath' => 
          array (
            'name' => 'filepath',
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
            'startLine' => 16,
            'endLine' => 16,
            'startColumn' => 26,
            'endColumn' => 41,
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
 * Save data in given path in file system
 *
 * @throws RuntimeException
 */',
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 49,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Intervention\\Image\\Interfaces',
        'declaringClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
        'implementingClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
        'currentClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
        'aliasName' => NULL,
      ),
      'toFilePointer' => 
      array (
        'name' => 'toFilePointer',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create file pointer from encoded data
 *
 * @return resource
 */',
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Intervention\\Image\\Interfaces',
        'declaringClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
        'implementingClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
        'currentClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
        'aliasName' => NULL,
      ),
      'size' => 
      array (
        'name' => 'size',
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
 * Return size in bytes
 */',
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 32,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Intervention\\Image\\Interfaces',
        'declaringClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
        'implementingClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
        'currentClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
        'aliasName' => NULL,
      ),
      'toString' => 
      array (
        'name' => 'toString',
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
 * Turn encoded data into string
 */',
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Intervention\\Image\\Interfaces',
        'declaringClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
        'implementingClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
        'currentClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
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
 * Cast encoded data into string
 */',
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Intervention\\Image\\Interfaces',
        'declaringClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
        'implementingClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
        'currentClassName' => 'Intervention\\Image\\Interfaces\\FileInterface',
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