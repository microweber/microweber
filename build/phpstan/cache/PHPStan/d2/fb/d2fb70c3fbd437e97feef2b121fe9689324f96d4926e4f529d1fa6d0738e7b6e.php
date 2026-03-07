<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../dutchcodingcompany/filament-socialite/src/Models/Contracts/FilamentSocialiteUser.php-PHPStan\BetterReflection\Reflection\ReflectionClass-DutchCodingCompany\FilamentSocialite\Models\Contracts\FilamentSocialiteUser
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-716f529da5d417dc6d3b28cabcf21f688ce16c94e69f843f1ba51562b4af593f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts\\FilamentSocialiteUser',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../dutchcodingcompany/filament-socialite/src/Models/Contracts/FilamentSocialiteUser.php',
      ),
    ),
    'namespace' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts',
    'name' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts\\FilamentSocialiteUser',
    'shortName' => 'FilamentSocialiteUser',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 15,
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
      'getUser' => 
      array (
        'name' => 'getUser',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Contracts\\Auth\\Authenticatable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 10,
        'endLine' => 10,
        'startColumn' => 5,
        'endColumn' => 47,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts',
        'declaringClassName' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts\\FilamentSocialiteUser',
        'implementingClassName' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts\\FilamentSocialiteUser',
        'currentClassName' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts\\FilamentSocialiteUser',
        'aliasName' => NULL,
      ),
      'findForProvider' => 
      array (
        'name' => 'findForProvider',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
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
            'startLine' => 12,
            'endLine' => 12,
            'startColumn' => 44,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'oauthUser' => 
          array (
            'name' => 'oauthUser',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Laravel\\Socialite\\Contracts\\User',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 12,
            'endLine' => 12,
            'startColumn' => 62,
            'endColumn' => 93,
            'parameterIndex' => 1,
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
                  'name' => 'self',
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
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 5,
        'endColumn' => 102,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts',
        'declaringClassName' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts\\FilamentSocialiteUser',
        'implementingClassName' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts\\FilamentSocialiteUser',
        'currentClassName' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts\\FilamentSocialiteUser',
        'aliasName' => NULL,
      ),
      'createForProvider' => 
      array (
        'name' => 'createForProvider',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
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
            'startLine' => 14,
            'endLine' => 14,
            'startColumn' => 46,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'oauthUser' => 
          array (
            'name' => 'oauthUser',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Laravel\\Socialite\\Contracts\\User',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 14,
            'endLine' => 14,
            'startColumn' => 64,
            'endColumn' => 95,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'user' => 
          array (
            'name' => 'user',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Auth\\Authenticatable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 14,
            'endLine' => 14,
            'startColumn' => 98,
            'endColumn' => 118,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 126,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts',
        'declaringClassName' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts\\FilamentSocialiteUser',
        'implementingClassName' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts\\FilamentSocialiteUser',
        'currentClassName' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts\\FilamentSocialiteUser',
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