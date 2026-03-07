<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Module/Repositories/ModuleRepository.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Module\Repositories\ModuleRepository
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-15e472555a6a0f5a00169b23ffd215645463e0af140fca33e8ff31d24da45d0b',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Module/Repositories/ModuleRepository.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Module\\Repositories',
    'name' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
    'shortName' => 'ModuleRepository',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/** @deprecated */',
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 175,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'MicroweberPackages\\Repository\\Repositories\\AbstractRepository',
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
      'model' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'implementingClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'name' => 'model',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\MicroweberPackages\\Module\\Models\\Module::class',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 47,
            'startFilePos' => 416,
            'endTokenPos' => 49,
            'endFilePos' => 462,
          ),
        ),
        'docComment' => '/**
 * Specify Models class name
 *
 * @return string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 68,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_getAllModules' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'implementingClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'name' => '_getAllModules',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 60,
            'startFilePos' => 502,
            'endTokenPos' => 61,
            'endFilePos' => 503,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 39,
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
      'getAllModules' => 
      array (
        'name' => 'getAllModules',
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
 * Get all modules
 *
 * @return array
 */',
        'startLine' => 29,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Module\\Repositories',
        'declaringClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'implementingClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'currentClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'aliasName' => NULL,
      ),
      'getModulesByType' => 
      array (
        'name' => 'getModulesByType',
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
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 38,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 36,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Module\\Repositories',
        'declaringClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'implementingClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'currentClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'aliasName' => NULL,
      ),
      'getModule' => 
      array (
        'name' => 'getModule',
        'parameters' => 
        array (
          'module' => 
          array (
            'name' => 'module',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 31,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 52,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Module\\Repositories',
        'declaringClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'implementingClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'currentClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'aliasName' => NULL,
      ),
      'getSystemLicenses' => 
      array (
        'name' => 'getSystemLicenses',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 68,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Module\\Repositories',
        'declaringClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'implementingClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'currentClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'aliasName' => NULL,
      ),
      'setUninstalled' => 
      array (
        'name' => 'setUninstalled',
        'parameters' => 
        array (
          'module' => 
          array (
            'name' => 'module',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 94,
            'endLine' => 94,
            'startColumn' => 36,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 94,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Module\\Repositories',
        'declaringClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'implementingClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'currentClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'aliasName' => NULL,
      ),
      'setInstalled' => 
      array (
        'name' => 'setInstalled',
        'parameters' => 
        array (
          'module' => 
          array (
            'name' => 'module',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 135,
            'endLine' => 135,
            'startColumn' => 34,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'config' => 
          array (
            'name' => 'config',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 135,
                'endLine' => 135,
                'startTokenPos' => 618,
                'startFilePos' => 3316,
                'endTokenPos' => 619,
                'endFilePos' => 3317,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 135,
            'endLine' => 135,
            'startColumn' => 43,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 135,
        'endLine' => 156,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Module\\Repositories',
        'declaringClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'implementingClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'currentClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'aliasName' => NULL,
      ),
      'clearCache' => 
      array (
        'name' => 'clearCache',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 159,
        'endLine' => 163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Module\\Repositories',
        'declaringClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'implementingClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'currentClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'aliasName' => NULL,
      ),
      'generateCacheTags' => 
      array (
        'name' => 'generateCacheTags',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 166,
        'endLine' => 172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Module\\Repositories',
        'declaringClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'implementingClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
        'currentClassName' => 'MicroweberPackages\\Module\\Repositories\\ModuleRepository',
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