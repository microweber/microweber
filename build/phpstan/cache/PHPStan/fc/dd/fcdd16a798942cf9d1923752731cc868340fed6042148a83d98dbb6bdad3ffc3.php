<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Microweber/Traits/ManagesModules.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Microweber\Traits\ManagesModules
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-733bb696c3f089535c1b347a850d05593655246c9ac97ac98c4acbb2aa82d14e',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Microweber/Traits/ManagesModules.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Microweber\\Traits',
    'name' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
    'shortName' => 'ManagesModules',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Trait ManagesModules
 *
 * Provides functionality to manage and render modules.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 230,
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
      'modules' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'name' => 'modules',
        'modifiers' => 1,
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
            'startLine' => 16,
            'endLine' => 16,
            'startTokenPos' => 28,
            'startFilePos' => 256,
            'endTokenPos' => 29,
            'endFilePos' => 257,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 31,
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
      'module' => 
      array (
        'name' => 'module',
        'parameters' => 
        array (
          'moduleClass' => 
          array (
            'name' => 'moduleClass',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 28,
            'endColumn' => 39,
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
 * Register a module with a specific type and class.
 *
 * @param string $moduleClass The class of the module.
 */',
        'startLine' => 23,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'aliasName' => NULL,
      ),
      'getModules' => 
      array (
        'name' => 'getModules',
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
 * Retrieve all registered modules.
 *
 * @return array The registered modules.
 */',
        'startLine' => 43,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'aliasName' => NULL,
      ),
      'hasModule' => 
      array (
        'name' => 'hasModule',
        'parameters' => 
        array (
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
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 31,
            'endColumn' => 35,
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
 * Check if a module of a specific type is registered.
 *
 * @param string $type The type of the module.
 * @return bool True if the module is registered, false otherwise.
 */',
        'startLine' => 54,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'aliasName' => NULL,
      ),
      'getModuleClass' => 
      array (
        'name' => 'getModuleClass',
        'parameters' => 
        array (
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
            'startLine' => 59,
            'endLine' => 59,
            'startColumn' => 36,
            'endColumn' => 40,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 59,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'aliasName' => NULL,
      ),
      'render' => 
      array (
        'name' => 'render',
        'parameters' => 
        array (
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
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 28,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 35,
            'endColumn' => 41,
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
 * Render a module of a specific type with given parameters.
 *
 * @param string $type The type of the module.
 * @param array $params The parameters for rendering the module.
 * @return string The rendered module output.
 */',
        'startLine' => 71,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'aliasName' => NULL,
      ),
      'make' => 
      array (
        'name' => 'make',
        'parameters' => 
        array (
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
            'startLine' => 80,
            'endLine' => 80,
            'startColumn' => 26,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 80,
            'endLine' => 80,
            'startColumn' => 33,
            'endColumn' => 39,
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
            'name' => 'MicroweberPackages\\Microweber\\Abstract\\BaseModule',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 80,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'aliasName' => NULL,
      ),
      'getSettingsComponents' => 
      array (
        'name' => 'getSettingsComponents',
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
 * Retrieve the settings components for all registered modules.
 *
 * @return array The settings components for the modules.
 */',
        'startLine' => 92,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'aliasName' => NULL,
      ),
      'getTranslatableOptionKeys' => 
      array (
        'name' => 'getTranslatableOptionKeys',
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
 * Retrieve the settings components for all registered modules.
 *
 * @return array The settings components for the modules.
 */',
        'startLine' => 108,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'aliasName' => NULL,
      ),
      'getModulesDetails' => 
      array (
        'name' => 'getModulesDetails',
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
 * Retrieve detailed information about all registered modules.
 *
 * @return array The details of the registered modules.
 */',
        'startLine' => 124,
        'endLine' => 147,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'aliasName' => NULL,
      ),
      'getTemplates' => 
      array (
        'name' => 'getTemplates',
        'parameters' => 
        array (
          'moduleType' => 
          array (
            'name' => 'moduleType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 149,
            'endLine' => 149,
            'startColumn' => 34,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'activeSiteTemplate' => 
          array (
            'name' => 'activeSiteTemplate',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 149,
                'endLine' => 149,
                'startTokenPos' => 678,
                'startFilePos' => 4095,
                'endTokenPos' => 678,
                'endFilePos' => 4099,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 149,
            'endLine' => 149,
            'startColumn' => 47,
            'endColumn' => 73,
            'parameterIndex' => 1,
            'isOptional' => true,
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
        'startLine' => 149,
        'endLine' => 228,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\ManagesModules',
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