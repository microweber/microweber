<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/Models/User.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\User\Models\User
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-2645a8a6448180645031e8763f0fb41a5aba5ee0a2145b420bef2ade7f80bd56',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\User\\Models\\User',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/Models/User.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\User\\Models',
    'name' => 'MicroweberPackages\\User\\Models\\User',
    'shortName' => 'User',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 41,
    'endLine' => 499,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Foundation\\Auth\\User',
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
      1 => 'Filament\\Models\\Contracts\\FilamentUser',
      2 => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts\\FilamentSocialiteUser',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      1 => 'Illuminate\\Notifications\\Notifiable',
      2 => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
      3 => 'Laravel\\Sanctum\\HasApiTokens',
      4 => 'EloquentFilter\\Filterable',
      5 => 'MicroweberPackages\\Core\\Models\\HasSearchableTrait',
      6 => 'MicroweberPackages\\User\\Notifications\\MustVerifyEmailTrait',
      7 => 'Illuminate\\Auth\\Passwords\\CanResetPassword',
      8 => 'MicroweberPackages\\Database\\Traits\\CacheableQueryBuilderTrait',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'casts' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'username\' => \\MicroweberPackages\\Database\\Casts\\StripTagsCast::class, \'thumbnail\' => \\MicroweberPackages\\Database\\Casts\\ReplaceSiteUrlCast::class]',
          'attributes' => 
          array (
            'startLine' => 59,
            'endLine' => 62,
            'startTokenPos' => 249,
            'startFilePos' => 2036,
            'endTokenPos' => 269,
            'endFilePos' => 2136,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'attributes' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'name' => 'attributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'is_active\' => 1, \'is_admin\' => 0, \'is_verified\' => 0]',
          'attributes' => 
          array (
            'startLine' => 64,
            'endLine' => 68,
            'startTokenPos' => 278,
            'startFilePos' => 2168,
            'endTokenPos' => 301,
            'endFilePos' => 2253,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 64,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'searchable' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'name' => 'searchable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'email\', \'username\', \'first_name\', \'last_name\', \'phone\']',
          'attributes' => 
          array (
            'startLine' => 70,
            'endLine' => 76,
            'startTokenPos' => 310,
            'startFilePos' => 2285,
            'endTokenPos' => 327,
            'endFilePos' => 2388,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 70,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'hidden' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'name' => 'hidden',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'api_key\', \'remember_token\', \'oauth_token\', \'oauth_token_secret\', \'password_reset_hash\', \'two_factor_recovery_codes\', \'two_factor_secret\', \'subscr_id\', \'oauth_uid\', \'oauth_provider\', \'last_login_ip\', \'password\']',
          'attributes' => 
          array (
            'startLine' => 78,
            'endLine' => 91,
            'startTokenPos' => 336,
            'startFilePos' => 2416,
            'endTokenPos' => 374,
            'endFilePos' => 2730,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 78,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillableByUser' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'name' => 'fillableByUser',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array(\'username\', \'password\', \'email\', \'first_name\', \'last_name\', \'thumbnail\', \'user_information\', \'profile_url\', \'website_url\', \'phone\')',
          'attributes' => 
          array (
            'startLine' => 95,
            'endLine' => 106,
            'startTokenPos' => 385,
            'startFilePos' => 2830,
            'endTokenPos' => 418,
            'endFilePos' => 3053,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 95,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array(\'updated_at\', \'created_at\', \'expires_on\', \'last_login\', \'last_login_ip\', \'created_by\', \'edited_by\', \'username\', \'password\', \'email\', \'is_active\', \'is_admin\', \'is_verified\', \'is_public\', \'basic_mode\', \'first_name\', \'last_name\', \'thumbnail\', \'parent_id\', \'user_information\', \'subscr_id\', \'role\', \'medium\', \'oauth_uid\', \'oauth_provider\', \'profile_url\', \'website_url\', \'phone\', \'two_factor_secret\', \'two_factor_recovery_codes\', \'two_factor_confirmed_at\')',
          'attributes' => 
          array (
            'startLine' => 108,
            'endLine' => 140,
            'startTokenPos' => 427,
            'startFilePos' => 3083,
            'endTokenPos' => 522,
            'endFilePos' => 3792,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 108,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'rules' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'name' => 'rules',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 142,
            'endLine' => 144,
            'startTokenPos' => 531,
            'startFilePos' => 3819,
            'endTokenPos' => 535,
            'endFilePos' => 3860,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 142,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'validator' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'name' => 'validator',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 146,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'refreshToken' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'name' => 'refreshToken',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The refresh token for the user.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 383,
        'endLine' => 383,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'expiresIn' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'name' => 'expiresIn',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The number of seconds the access token is valid for.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 390,
        'endLine' => 390,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'tokenType' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'name' => 'tokenType',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The token type.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 397,
        'endLine' => 397,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'raw' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'name' => 'raw',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 404,
            'endLine' => 404,
            'startTokenPos' => 1969,
            'startFilePos' => 10267,
            'endTokenPos' => 1970,
            'endFilePos' => 10268,
          ),
        ),
        'docComment' => '/**
 * The raw response from the provider.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 404,
        'endLine' => 404,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'token' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'name' => 'token',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The token.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 412,
        'endLine' => 412,
        'startColumn' => 5,
        'endColumn' => 18,
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
      'newFactory' => 
      array (
        'name' => 'newFactory',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 54,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'boot' => 
      array (
        'name' => 'boot',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 148,
        'endLine' => 156,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'modelFilter' => 
      array (
        'name' => 'modelFilter',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 158,
        'endLine' => 161,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'setPasswordAttribute' => 
      array (
        'name' => 'setPasswordAttribute',
        'parameters' => 
        array (
          'pass' => 
          array (
            'name' => 'pass',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 163,
            'endLine' => 163,
            'startColumn' => 42,
            'endColumn' => 46,
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
        'startLine' => 163,
        'endLine' => 166,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'findForPassport' => 
      array (
        'name' => 'findForPassport',
        'parameters' => 
        array (
          'username' => 
          array (
            'name' => 'username',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 174,
            'endLine' => 174,
            'startColumn' => 37,
            'endColumn' => 45,
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
 * Find the user instance for the given username.
 *
 * @param string $username
 * @return \\App\\User
 */',
        'startLine' => 174,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'isAdmin' => 
      array (
        'name' => 'isAdmin',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 179,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'login' => 
      array (
        'name' => 'login',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 184,
            'endLine' => 184,
            'startColumn' => 34,
            'endColumn' => 41,
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
        'startLine' => 184,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'sendPasswordResetNotification' => 
      array (
        'name' => 'sendPasswordResetNotification',
        'parameters' => 
        array (
          'token' => 
          array (
            'name' => 'token',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 196,
            'endLine' => 196,
            'startColumn' => 51,
            'endColumn' => 56,
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
 * Override the mail body for reset password notification mail.
 */',
        'startLine' => 196,
        'endLine' => 199,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'customer' => 
      array (
        'name' => 'customer',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 201,
        'endLine' => 204,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'getAvatarAttribute' => 
      array (
        'name' => 'getAvatarAttribute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 206,
        'endLine' => 209,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'getRoleNameAttribute' => 
      array (
        'name' => 'getRoleNameAttribute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 211,
        'endLine' => 218,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'getValidatorMessages' => 
      array (
        'name' => 'getValidatorMessages',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 220,
        'endLine' => 223,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'validateAndFill' => 
      array (
        'name' => 'validateAndFill',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 225,
            'endLine' => 225,
            'startColumn' => 37,
            'endColumn' => 41,
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
        'startLine' => 225,
        'endLine' => 295,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'avatarUrl' => 
      array (
        'name' => 'avatarUrl',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 297,
        'endLine' => 300,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'getFullNameAttribute' => 
      array (
        'name' => 'getFullNameAttribute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 302,
        'endLine' => 305,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'displayName' => 
      array (
        'name' => 'displayName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 307,
        'endLine' => 328,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'twoFactorQrCodeUrl' => 
      array (
        'name' => 'twoFactorQrCodeUrl',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the two factor authentication QR code URL.
 *
 * @return string
 */',
        'startLine' => 335,
        'endLine' => 342,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'canAccessPanel' => 
      array (
        'name' => 'canAccessPanel',
        'parameters' => 
        array (
          'panel' => 
          array (
            'name' => 'panel',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Filament\\Panel',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 345,
            'endLine' => 345,
            'startColumn' => 36,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 345,
        'endLine' => 375,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'setToken' => 
      array (
        'name' => 'setToken',
        'parameters' => 
        array (
          'token' => 
          array (
            'name' => 'token',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 414,
            'endLine' => 414,
            'startColumn' => 30,
            'endColumn' => 35,
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
        'startLine' => 414,
        'endLine' => 419,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'setRefreshToken' => 
      array (
        'name' => 'setRefreshToken',
        'parameters' => 
        array (
          'refreshToken' => 
          array (
            'name' => 'refreshToken',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 428,
            'endLine' => 428,
            'startColumn' => 37,
            'endColumn' => 49,
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
 * Set the refresh token required to obtain a new access token.
 *
 * @param string $refreshToken
 * @return $this
 */',
        'startLine' => 428,
        'endLine' => 433,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
      'setExpiresIn' => 
      array (
        'name' => 'setExpiresIn',
        'parameters' => 
        array (
          'expiresIn' => 
          array (
            'name' => 'expiresIn',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 441,
            'endLine' => 441,
            'startColumn' => 34,
            'endColumn' => 43,
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
 * Set the number of seconds the access token is valid for.
 *
 * @param int $expiresIn
 * @return $this
 */',
        'startLine' => 441,
        'endLine' => 446,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
        'aliasName' => NULL,
      ),
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
        'startLine' => 448,
        'endLine' => 451,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
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
            'startLine' => 453,
            'endLine' => 453,
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
            'startLine' => 453,
            'endLine' => 453,
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
        'startLine' => 453,
        'endLine' => 478,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
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
            'startLine' => 480,
            'endLine' => 480,
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
            'startLine' => 480,
            'endLine' => 480,
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
                      'name' => 'Illuminate\\Foundation\\Auth\\User',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Illuminate\\Contracts\\Auth\\Authenticatable',
                      'isIdentifier' => false,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 480,
            'endLine' => 480,
            'startColumn' => 98,
            'endColumn' => 161,
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
            'name' => 'DutchCodingCompany\\FilamentSocialite\\Models\\Contracts\\FilamentSocialiteUser',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 480,
        'endLine' => 498,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\User\\Models',
        'declaringClassName' => 'MicroweberPackages\\User\\Models\\User',
        'implementingClassName' => 'MicroweberPackages\\User\\Models\\User',
        'currentClassName' => 'MicroweberPackages\\User\\Models\\User',
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