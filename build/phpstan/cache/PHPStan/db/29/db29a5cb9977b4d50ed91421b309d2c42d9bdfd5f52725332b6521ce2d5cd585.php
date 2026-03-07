<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Http/Requests/TwoFactorLoginRequest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Fortify\Http\Requests\TwoFactorLoginRequest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-f2fd68816bcd6d1a88994b8fbb54ea3f6959ecf28b5e8f54772c30f18431032e-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Http/Requests/TwoFactorLoginRequest.php',
      ),
    ),
    'namespace' => 'Laravel\\Fortify\\Http\\Requests',
    'name' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
    'shortName' => 'TwoFactorLoginRequest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 140,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Foundation\\Http\\FormRequest',
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
      'challengedUser' => 
      array (
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'name' => 'challengedUser',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The user attempting the two factor challenge.
 *
 * @var mixed
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'remember' => 
      array (
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'name' => 'remember',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Indicates if the user wished to be remembered after login.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 24,
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
      'authorize' => 
      array (
        'name' => 'authorize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the user is authorized to make this request.
 *
 * @return bool
 */',
        'startLine' => 33,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Requests',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'aliasName' => NULL,
      ),
      'rules' => 
      array (
        'name' => 'rules',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the validation rules that apply to the request.
 *
 * @return array
 */',
        'startLine' => 43,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Requests',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'aliasName' => NULL,
      ),
      'hasValidCode' => 
      array (
        'name' => 'hasValidCode',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the request has a valid two factor code.
 *
 * @return bool
 */',
        'startLine' => 56,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Requests',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'aliasName' => NULL,
      ),
      'validRecoveryCode' => 
      array (
        'name' => 'validRecoveryCode',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the valid recovery code if one exists on the request.
 *
 * @return string|null
 */',
        'startLine' => 72,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Requests',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'aliasName' => NULL,
      ),
      'hasChallengedUser' => 
      array (
        'name' => 'hasChallengedUser',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if there is a challenged user in the current session.
 *
 * @return bool
 */',
        'startLine' => 92,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Requests',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'aliasName' => NULL,
      ),
      'challengedUser' => 
      array (
        'name' => 'challengedUser',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the user that is attempting the two factor challenge.
 *
 * @return mixed
 */',
        'startLine' => 109,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Requests',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'aliasName' => NULL,
      ),
      'remember' => 
      array (
        'name' => 'remember',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the user wanted to be remembered after login.
 *
 * @return bool
 */',
        'startLine' => 132,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Requests',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
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