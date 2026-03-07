<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Rules/Password.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Fortify\Rules\Password
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-806b4d0ba0401e90674b9ddcf0803a037e89b97dc5f63dab66887af91b7117c1-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Fortify\\Rules\\Password',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Rules/Password.php',
      ),
    ),
    'namespace' => 'Laravel\\Fortify\\Rules',
    'name' => 'Laravel\\Fortify\\Rules\\Password',
    'shortName' => 'Password',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @deprecated Use \\Illuminate\\Validation\\Rules\\Password instead.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 203,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Validation\\Rule',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'length' => 
      array (
        'declaringClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'implementingClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'name' => 'length',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '8',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 37,
            'startFilePos' => 325,
            'endTokenPos' => 37,
            'endFilePos' => 325,
          ),
        ),
        'docComment' => '/**
 * The minimum length of the password.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'requireUppercase' => 
      array (
        'declaringClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'implementingClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'name' => 'requireUppercase',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 48,
            'startFilePos' => 474,
            'endTokenPos' => 48,
            'endFilePos' => 478,
          ),
        ),
        'docComment' => '/**
 * Indicates if the password must contain one uppercase character.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'requireNumeric' => 
      array (
        'declaringClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'implementingClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'name' => 'requireNumeric',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 59,
            'startFilePos' => 619,
            'endTokenPos' => 59,
            'endFilePos' => 623,
          ),
        ),
        'docComment' => '/**
 * Indicates if the password must contain one numeric digit.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'requireSpecialCharacter' => 
      array (
        'declaringClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'implementingClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'name' => 'requireSpecialCharacter',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 70,
            'startFilePos' => 777,
            'endTokenPos' => 70,
            'endFilePos' => 781,
          ),
        ),
        'docComment' => '/**
 * Indicates if the password must contain one special character.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 47,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'message' => 
      array (
        'declaringClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'implementingClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'name' => 'message',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The message that should be used when validation fails.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 23,
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
      'passes' => 
      array (
        'name' => 'passes',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 28,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 40,
            'endColumn' => 45,
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
 * Determine if the validation rule passes.
 *
 * @param  string  $attribute
 * @param  mixed  $value
 * @return bool
 */',
        'startLine' => 55,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Rules',
        'declaringClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'implementingClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'currentClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'message' => 
      array (
        'name' => 'message',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the validation error message.
 *
 * @return string
 */',
        'startLine' => 79,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Rules',
        'declaringClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'implementingClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'currentClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'length' => 
      array (
        'name' => 'length',
        'parameters' => 
        array (
          'length' => 
          array (
            'name' => 'length',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 148,
            'endLine' => 148,
            'startColumn' => 28,
            'endColumn' => 38,
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
 * Set the minimum length of the password.
 *
 * @param  int  $length
 * @return $this
 */',
        'startLine' => 148,
        'endLine' => 153,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Rules',
        'declaringClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'implementingClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'currentClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'requireUppercase' => 
      array (
        'name' => 'requireUppercase',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that at least one uppercase character is required.
 *
 * @return $this
 */',
        'startLine' => 160,
        'endLine' => 165,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Rules',
        'declaringClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'implementingClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'currentClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'requireNumeric' => 
      array (
        'name' => 'requireNumeric',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that at least one numeric digit is required.
 *
 * @return $this
 */',
        'startLine' => 172,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Rules',
        'declaringClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'implementingClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'currentClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'requireSpecialCharacter' => 
      array (
        'name' => 'requireSpecialCharacter',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that at least one special character is required.
 *
 * @return $this
 */',
        'startLine' => 184,
        'endLine' => 189,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Rules',
        'declaringClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'implementingClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'currentClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'withMessage' => 
      array (
        'name' => 'withMessage',
        'parameters' => 
        array (
          'message' => 
          array (
            'name' => 'message',
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
            'startLine' => 197,
            'endLine' => 197,
            'startColumn' => 33,
            'endColumn' => 47,
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
 * Set the message that should be used when the rule fails.
 *
 * @param  string  $message
 * @return $this
 */',
        'startLine' => 197,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Rules',
        'declaringClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'implementingClassName' => 'Laravel\\Fortify\\Rules\\Password',
        'currentClassName' => 'Laravel\\Fortify\\Rules\\Password',
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