<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/AiTools/Contracts/ToolInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\AiTools\Contracts\ToolInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-454a8883f0d5ea0083613933209ff79d8318c74c1616c2c2a32b6cf548ba413d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/AiTools/Contracts/ToolInterface.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
    'name' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
    'shortName' => 'ToolInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Interface for all AI tools in the ecosystem.
 *
 * This interface defines the contract that all tools must implement
 * to be registered and executed within the AI Tools framework.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 71,
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
 * Get the unique name of the tool.
 *
 * @return string
 */',
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 38,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'aliasName' => NULL,
      ),
      'getDescription' => 
      array (
        'name' => 'getDescription',
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
 * Get the description of what the tool does.
 *
 * @return string
 */',
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'aliasName' => NULL,
      ),
      'getDomain' => 
      array (
        'name' => 'getDomain',
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
 * Get the domain/category this tool belongs to.
 *
 * @return string
 */',
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 40,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'aliasName' => NULL,
      ),
      'getRequiredPermissions' => 
      array (
        'name' => 'getRequiredPermissions',
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
 * Get the required permissions to use this tool.
 *
 * @return array
 */',
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 52,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'aliasName' => NULL,
      ),
      'isAuthorized' => 
      array (
        'name' => 'isAuthorized',
        'parameters' => 
        array (
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
 * Check if the current user is authorized to use this tool.
 *
 * @return bool
 */',
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'aliasName' => NULL,
      ),
      'getProperties' => 
      array (
        'name' => 'getProperties',
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
 * Get the tool\'s input properties/schema.
 *
 * @return array
 */',
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'aliasName' => NULL,
      ),
      '__invoke' => 
      array (
        'name' => '__invoke',
        'parameters' => 
        array (
          'args' => 
          array (
            'name' => 'args',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 30,
            'endColumn' => 37,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Execute the tool with the provided arguments.
 *
 * @param mixed ...$args
 * @return string
 */',
        'startLine' => 63,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 47,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'aliasName' => NULL,
      ),
      'getMaxTries' => 
      array (
        'name' => 'getMaxTries',
        'parameters' => 
        array (
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
                  'name' => 'int',
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the maximum number of retry attempts.
 *
 * @return int|null
 */',
        'startLine' => 70,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 40,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Contracts',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Contracts\\ToolInterface',
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