<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/AiTools/Contracts/ToolRegistryInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\AiTools\Contracts\ToolRegistryInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-64500d89019bec2752f0f8e650dc3901532d52ecade70ebdc8232a76abe56177',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/AiTools/Contracts/ToolRegistryInterface.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
    'name' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
    'shortName' => 'ToolRegistryInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Interface for tool registry.
 *
 * The registry manages tool discovery, registration, and retrieval.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 82,
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
      'register' => 
      array (
        'name' => 'register',
        'parameters' => 
        array (
          'toolClass' => 
          array (
            'name' => 'toolClass',
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
            'startLine' => 20,
            'endLine' => 20,
            'startColumn' => 30,
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
 * Register a tool class.
 *
 * @param string $toolClass
 * @return void
 */',
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 54,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'aliasName' => NULL,
      ),
      'unregister' => 
      array (
        'name' => 'unregister',
        'parameters' => 
        array (
          'toolName' => 
          array (
            'name' => 'toolName',
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
            'startLine' => 28,
            'endLine' => 28,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Unregister a tool.
 *
 * @param string $toolName
 * @return void
 */',
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 55,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'aliasName' => NULL,
      ),
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
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
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 25,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
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
                  'name' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
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
 * Get a tool instance by name.
 *
 * @param string $name
 * @return ToolInterface|null
 */',
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 54,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'aliasName' => NULL,
      ),
      'all' => 
      array (
        'name' => 'all',
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
 * Get all registered tools.
 *
 * @return array
 */',
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'aliasName' => NULL,
      ),
      'getByDomain' => 
      array (
        'name' => 'getByDomain',
        'parameters' => 
        array (
          'domain' => 
          array (
            'name' => 'domain',
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
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 33,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get tools filtered by domain.
 *
 * @param string $domain
 * @return array
 */',
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 55,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'aliasName' => NULL,
      ),
      'has' => 
      array (
        'name' => 'has',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
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
            'startLine' => 59,
            'endLine' => 59,
            'startColumn' => 25,
            'endColumn' => 36,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Check if a tool is registered.
 *
 * @param string $name
 * @return bool
 */',
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'aliasName' => NULL,
      ),
      'names' => 
      array (
        'name' => 'names',
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
 * Get tool names as array.
 *
 * @return array
 */',
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 35,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'aliasName' => NULL,
      ),
      'registerMany' => 
      array (
        'name' => 'registerMany',
        'parameters' => 
        array (
          'toolClasses' => 
          array (
            'name' => 'toolClasses',
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
            'startLine' => 74,
            'endLine' => 74,
            'startColumn' => 34,
            'endColumn' => 51,
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
 * Register multiple tools at once.
 *
 * @param array $toolClasses
 * @return void
 */',
        'startLine' => 74,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 59,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'aliasName' => NULL,
      ),
      'clear' => 
      array (
        'name' => 'clear',
        'parameters' => 
        array (
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
 * Clear all registered tools.
 *
 * @return void
 */',
        'startLine' => 81,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
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