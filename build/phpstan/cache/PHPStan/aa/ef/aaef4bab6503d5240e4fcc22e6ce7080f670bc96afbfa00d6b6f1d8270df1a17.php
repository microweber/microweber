<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/ConfirmsPasswords.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\User\ConfirmsPasswords
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-df84f212c69687cf779e4e0d8b1ada1f367089291550be428341483b180cd490',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/ConfirmsPasswords.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\User',
    'name' => 'MicroweberPackages\\User\\ConfirmsPasswords',
    'shortName' => 'ConfirmsPasswords',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 115,
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
      'confirmingPassword' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'implementingClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'name' => 'confirmingPassword',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 41,
            'startFilePos' => 377,
            'endTokenPos' => 41,
            'endFilePos' => 381,
          ),
        ),
        'docComment' => '/**
 * Indicates if the user\'s password is being confirmed.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'confirmableId' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'implementingClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'name' => 'confirmableId',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 24,
            'startTokenPos' => 52,
            'startFilePos' => 508,
            'endTokenPos' => 52,
            'endFilePos' => 511,
          ),
        ),
        'docComment' => '/**
 * The ID of the operation being confirmed.
 *
 * @var string|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'confirmablePassword' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'implementingClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'name' => 'confirmablePassword',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'\'',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 63,
            'startFilePos' => 619,
            'endTokenPos' => 63,
            'endFilePos' => 620,
          ),
        ),
        'docComment' => '/**
 * The user\'s password.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 37,
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
      'startConfirmingPassword' => 
      array (
        'name' => 'startConfirmingPassword',
        'parameters' => 
        array (
          'confirmableId' => 
          array (
            'name' => 'confirmableId',
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
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 45,
            'endColumn' => 65,
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
 * Start confirming the user\'s password.
 *
 * @param  string  $confirmableId
 * @return void
 */',
        'startLine' => 39,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User',
        'declaringClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'implementingClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'currentClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'aliasName' => NULL,
      ),
      'stopConfirmingPassword' => 
      array (
        'name' => 'stopConfirmingPassword',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Stop confirming the user\'s password.
 *
 * @return void
 */',
        'startLine' => 61,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User',
        'declaringClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'implementingClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'currentClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'aliasName' => NULL,
      ),
      'confirmPassword' => 
      array (
        'name' => 'confirmPassword',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Confirm the user\'s password.
 *
 * @return void
 */',
        'startLine' => 73,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User',
        'declaringClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'implementingClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'currentClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'aliasName' => NULL,
      ),
      'ensurePasswordIsConfirmed' => 
      array (
        'name' => 'ensurePasswordIsConfirmed',
        'parameters' => 
        array (
          'maximumSecondsSinceConfirmation' => 
          array (
            'name' => 'maximumSecondsSinceConfirmation',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 96,
                'endLine' => 96,
                'startTokenPos' => 332,
                'startFilePos' => 2353,
                'endTokenPos' => 332,
                'endFilePos' => 2356,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 96,
            'endLine' => 96,
            'startColumn' => 50,
            'endColumn' => 88,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Ensure that the user\'s password has been recently confirmed.
 *
 * @param  int|null  $maximumSecondsSinceConfirmation
 * @return void
 */',
        'startLine' => 96,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\User',
        'declaringClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'implementingClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'currentClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'aliasName' => NULL,
      ),
      'passwordIsConfirmed' => 
      array (
        'name' => 'passwordIsConfirmed',
        'parameters' => 
        array (
          'maximumSecondsSinceConfirmation' => 
          array (
            'name' => 'maximumSecondsSinceConfirmation',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 109,
                'endLine' => 109,
                'startTokenPos' => 388,
                'startFilePos' => 2828,
                'endTokenPos' => 388,
                'endFilePos' => 2831,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 109,
            'endLine' => 109,
            'startColumn' => 44,
            'endColumn' => 82,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the user\'s password has been recently confirmed.
 *
 * @param  int|null  $maximumSecondsSinceConfirmation
 * @return bool
 */',
        'startLine' => 109,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\User',
        'declaringClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'implementingClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
        'currentClassName' => 'MicroweberPackages\\User\\ConfirmsPasswords',
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