<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Microweber/Traits/HasMicroweberModule.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Microweber\Traits\HasMicroweberModule
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-490a0cbf6e1ec88ea67bc02edc21ed3d23d0584e7bdc8ecfb53315787a5570ee',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Microweber/Traits/HasMicroweberModule.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Microweber\\Traits',
    'name' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
    'shortName' => 'HasMicroweberModule',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Trait HasMicroweberModule
 *
 * Provides common functionality for Microweber modules, such as retrieving
 * module-specific properties like name, icon, position, and settings component.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 97,
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
 * Get the name of the module.
 *
 * @return string The name of the module, or an empty string if not set.
 */',
        'startLine' => 19,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'aliasName' => NULL,
      ),
      'getModuleType' => 
      array (
        'name' => 'getModuleType',
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
 * Get the type of the module.
 *
 * @return string The name of the module, or an empty string if not set.
 */',
        'startLine' => 33,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'aliasName' => NULL,
      ),
      'getIcon' => 
      array (
        'name' => 'getIcon',
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
 * Get the icon of the module.
 *
 * @return string The icon of the module, or an empty string if not set.
 */',
        'startLine' => 47,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'aliasName' => NULL,
      ),
      'getPosition' => 
      array (
        'name' => 'getPosition',
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
 * Get the position of the module.
 *
 * @return int The position of the module, or 0 if not set.
 */',
        'startLine' => 61,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'aliasName' => NULL,
      ),
      'getSettingsComponent' => 
      array (
        'name' => 'getSettingsComponent',
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
 * Get the settings component of the module.
 *
 * @return string The settings component of the module, or an empty string if not set.
 */',
        'startLine' => 74,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'aliasName' => NULL,
      ),
      'isStaticElement' => 
      array (
        'name' => 'isStaticElement',
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
        'docComment' => NULL,
        'startLine' => 83,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'aliasName' => NULL,
      ),
      'shouldRegisterNavigtion' => 
      array (
        'name' => 'shouldRegisterNavigtion',
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
        'docComment' => NULL,
        'startLine' => 90,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Microweber\\Traits',
        'declaringClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'implementingClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
        'currentClassName' => 'MicroweberPackages\\Microweber\\Traits\\HasMicroweberModule',
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