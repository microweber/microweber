<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Ai/Tools/BaseTool.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Ai\Tools\BaseTool
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-0d3549da9f6cf7d2de37ad292a143364d97f88137b15767fd9aca82a58bf0394',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Ai\\Tools\\BaseTool',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Ai/Tools/BaseTool.php',
      ),
    ),
    'namespace' => 'Modules\\Ai\\Tools',
    'name' => 'Modules\\Ai\\Tools\\BaseTool',
    'shortName' => 'BaseTool',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => '/**
 * Base Tool class - Backward Compatibility Layer
 *
 * This class extends the new microweber-packages/ai-tools BaseTool
 * to maintain backward compatibility with existing code.
 *
 * @deprecated Use MicroweberPackages\\AiTools\\Base\\BaseTool instead
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 80,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'MicroweberPackages\\AiTools\\Base\\BaseTool',
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
      'dependencies' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Tools\\BaseTool',
        'implementingClassName' => 'Modules\\Ai\\Tools\\BaseTool',
        'name' => 'dependencies',
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
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 9,
        'endColumn' => 42,
        'isPromoted' => true,
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
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 27,
                'endLine' => 27,
                'startTokenPos' => 53,
                'startFilePos' => 664,
                'endTokenPos' => 53,
                'endFilePos' => 665,
              ),
            ),
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
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 9,
            'endColumn' => 25,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'description' => 
          array (
            'name' => 'description',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 28,
                'endLine' => 28,
                'startTokenPos' => 62,
                'startFilePos' => 698,
                'endTokenPos' => 62,
                'endFilePos' => 699,
              ),
            ),
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
            'startLine' => 28,
            'endLine' => 28,
            'startColumn' => 9,
            'endColumn' => 32,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'dependencies' => 
          array (
            'name' => 'dependencies',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 29,
                'endLine' => 29,
                'startTokenPos' => 73,
                'startFilePos' => 742,
                'endTokenPos' => 74,
                'endFilePos' => 743,
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 29,
            'endLine' => 29,
            'startColumn' => 9,
            'endColumn' => 42,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Legacy constructor for backward compatibility.
 *
 * @param string $name
 * @param string $description
 * @param array $dependencies
 */',
        'startLine' => 26,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Tools',
        'declaringClassName' => 'Modules\\Ai\\Tools\\BaseTool',
        'implementingClassName' => 'Modules\\Ai\\Tools\\BaseTool',
        'currentClassName' => 'Modules\\Ai\\Tools\\BaseTool',
        'aliasName' => NULL,
      ),
      'getToolNameFromClass' => 
      array (
        'name' => 'getToolNameFromClass',
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
 * Get tool name from class name.
 *
 * @return string
 */',
        'startLine' => 49,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Ai\\Tools',
        'declaringClassName' => 'Modules\\Ai\\Tools\\BaseTool',
        'implementingClassName' => 'Modules\\Ai\\Tools\\BaseTool',
        'currentClassName' => 'Modules\\Ai\\Tools\\BaseTool',
        'aliasName' => NULL,
      ),
      'getDefaultDescription' => 
      array (
        'name' => 'getDefaultDescription',
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
 * Get default description from class docblock.
 *
 * @return string
 */',
        'startLine' => 62,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Ai\\Tools',
        'declaringClassName' => 'Modules\\Ai\\Tools\\BaseTool',
        'implementingClassName' => 'Modules\\Ai\\Tools\\BaseTool',
        'currentClassName' => 'Modules\\Ai\\Tools\\BaseTool',
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