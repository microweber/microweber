<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/Http/Livewire/Admin/LoginAsUserForm.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\User\Http\Livewire\Admin\LoginAsUserForm
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-0f49c3668ac19d0e8e0eb4065df7300602c7357560d71af82556743b3e5be709',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/Http/Livewire/Admin/LoginAsUserForm.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin',
    'name' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
    'shortName' => 'LoginAsUserForm',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 63,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'MicroweberPackages\\Admin\\Http\\Livewire\\AdminComponent',
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
      'confirmingUserLogin' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'implementingClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'name' => 'confirmingUserLogin',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 35,
            'startFilePos' => 343,
            'endTokenPos' => 35,
            'endFilePos' => 347,
          ),
        ),
        'docComment' => '/**
 * Indicates if user deletion is being confirmed.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'userId' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'implementingClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'name' => 'userId',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 44,
            'startFilePos' => 373,
            'endTokenPos' => 44,
            'endFilePos' => 377,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 27,
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
      'confirmUserLogin' => 
      array (
        'name' => 'confirmUserLogin',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Confirm that the user would like to delete their account.
 *
 * @return void
 */',
        'startLine' => 25,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin',
        'declaringClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'implementingClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'currentClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'aliasName' => NULL,
      ),
      'loginAsUser' => 
      array (
        'name' => 'loginAsUser',
        'parameters' => 
        array (
          'auth' => 
          array (
            'name' => 'auth',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Auth\\StatefulGuard',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 33,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Delete the current user.
 *
 * @param \\Illuminate\\Contracts\\Auth\\StatefulGuard $auth
 * @return void
 */',
        'startLine' => 38,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin',
        'declaringClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'implementingClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'currentClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'aliasName' => NULL,
      ),
      'mount' => 
      array (
        'name' => 'mount',
        'parameters' => 
        array (
          'userId' => 
          array (
            'name' => 'userId',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 47,
                'endLine' => 47,
                'startTokenPos' => 139,
                'startFilePos' => 993,
                'endTokenPos' => 139,
                'endFilePos' => 997,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 27,
            'endColumn' => 41,
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
        'startLine' => 47,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin',
        'declaringClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'implementingClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'currentClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'aliasName' => NULL,
      ),
      'render' => 
      array (
        'name' => 'render',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Render the component.
 *
 * @return \\Illuminate\\View\\View
 */',
        'startLine' => 59,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin',
        'declaringClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'implementingClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
        'currentClassName' => 'MicroweberPackages\\User\\Http\\Livewire\\Admin\\LoginAsUserForm',
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