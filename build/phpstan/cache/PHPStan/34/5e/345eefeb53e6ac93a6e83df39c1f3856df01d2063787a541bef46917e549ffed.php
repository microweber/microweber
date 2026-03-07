<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/AiTools/Registry/ToolRegistry.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\AiTools\Registry\ToolRegistry
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-c37f3cbe2a4fc4662befa86e3b4759221cd8cd04547da60f6bf1540481fe6380',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/AiTools/Registry/ToolRegistry.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\AiTools\\Registry',
    'name' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
    'shortName' => 'ToolRegistry',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Tool Registry for managing AI tools.
 *
 * This class provides centralized registration and discovery
 * of AI tools. Tools can be registered manually or auto-discovered.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 177,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'MicroweberPackages\\AiTools\\Contracts\\ToolRegistryInterface',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'tools' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'name' => 'tools',
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
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 47,
            'startFilePos' => 556,
            'endTokenPos' => 48,
            'endFilePos' => 557,
          ),
        ),
        'docComment' => '/**
 * Registered tools.
 *
 * @var array<string, ToolInterface>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'aliases' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'name' => 'aliases',
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
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 61,
            'startFilePos' => 697,
            'endTokenPos' => 62,
            'endFilePos' => 698,
          ),
        ),
        'docComment' => '/**
 * Tool aliases for backward compatibility.
 *
 * @var array<string, string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 34,
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
            'startLine' => 32,
            'endLine' => 32,
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
        'docComment' => NULL,
        'startLine' => 32,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
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
            'startLine' => 48,
            'endLine' => 48,
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
        'docComment' => NULL,
        'startLine' => 48,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
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
            'startLine' => 53,
            'endLine' => 53,
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
        'docComment' => NULL,
        'startLine' => 53,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
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
        'docComment' => NULL,
        'startLine' => 68,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
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
            'startLine' => 73,
            'endLine' => 73,
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
        'docComment' => NULL,
        'startLine' => 73,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
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
            'startLine' => 81,
            'endLine' => 81,
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
        'docComment' => NULL,
        'startLine' => 81,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
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
        'docComment' => NULL,
        'startLine' => 86,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
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
            'startLine' => 91,
            'endLine' => 91,
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
        'docComment' => NULL,
        'startLine' => 91,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
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
        'docComment' => NULL,
        'startLine' => 98,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'aliasName' => NULL,
      ),
      'registerAlias' => 
      array (
        'name' => 'registerAlias',
        'parameters' => 
        array (
          'alias' => 
          array (
            'name' => 'alias',
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
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 35,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 50,
            'endColumn' => 65,
            'parameterIndex' => 1,
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
 * Register an alias for a tool.
 *
 * @param string $alias
 * @param string $toolName
 * @return void
 */',
        'startLine' => 111,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'aliasName' => NULL,
      ),
      'getAliases' => 
      array (
        'name' => 'getAliases',
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
 * Get all registered aliases.
 *
 * @return array
 */',
        'startLine' => 121,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'aliasName' => NULL,
      ),
      'getByPermission' => 
      array (
        'name' => 'getByPermission',
        'parameters' => 
        array (
          'permission' => 
          array (
            'name' => 'permission',
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
            'startLine' => 132,
            'endLine' => 132,
            'startColumn' => 37,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get tools by required permission.
 *
 * @param string $permission
 * @return array
 */',
        'startLine' => 132,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'aliasName' => NULL,
      ),
      'getAuthorized' => 
      array (
        'name' => 'getAuthorized',
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
 * Get tools authorized for current user.
 *
 * @return array
 */',
        'startLine' => 145,
        'endLine' => 151,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'aliasName' => NULL,
      ),
      'count' => 
      array (
        'name' => 'count',
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
 * Get count of registered tools.
 *
 * @return int
 */',
        'startLine' => 158,
        'endLine' => 161,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'aliasName' => NULL,
      ),
      'getDomains' => 
      array (
        'name' => 'getDomains',
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
 * Get domains of all registered tools.
 *
 * @return array
 */',
        'startLine' => 168,
        'endLine' => 176,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Registry',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Registry\\ToolRegistry',
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