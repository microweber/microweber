<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/tests/Feature/Filament/AuthorizationTest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Tests\Feature\Filament\AuthorizationTest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-bbea7bb84cb1be28f4cf6fa1cb2ad2dd132229520af40645f4b0904f1ab0d6d0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'filename' => '/home/headless/Documents/GitHub/microweber/tests/Feature/Filament/AuthorizationTest.php',
      ),
    ),
    'namespace' => 'Tests\\Feature\\Filament',
    'name' => 'Tests\\Feature\\Filament\\AuthorizationTest',
    'shortName' => 'AuthorizationTest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => '/**
 * Base authorization test class for Filament resources.
 *
 * Provides standardized tests for resource authorization including:
 * - Non-admin access denial
 * - Guest access denial to panel
 * - Ownership-based access control
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 269,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Tests\\TestCase',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Foundation\\Testing\\RefreshDatabase',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'getResourceClass' => 
      array (
        'name' => 'getResourceClass',
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
 * Get the resource class being tested.
 * Must be implemented by subclasses.
 *
 * @return string
 */',
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 59,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 66,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'getListPageClass' => 
      array (
        'name' => 'getListPageClass',
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
 * Get the list page class for the resource.
 *
 * @return string
 */',
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 59,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 66,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'getEditPageClass' => 
      array (
        'name' => 'getEditPageClass',
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
                  'name' => 'string',
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
 * Get the edit page class for the resource.
 *
 * @return string|null
 */',
        'startLine' => 44,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'getViewPageClass' => 
      array (
        'name' => 'getViewPageClass',
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
                  'name' => 'string',
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
 * Get the view page class for the resource.
 *
 * @return string|null
 */',
        'startLine' => 54,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'getCreatePageClass' => 
      array (
        'name' => 'getCreatePageClass',
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
                  'name' => 'string',
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
 * Get the create page class for the resource.
 *
 * @return string|null
 */',
        'startLine' => 64,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'createTestRecord' => 
      array (
        'name' => 'createTestRecord',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 76,
                'endLine' => 76,
                'startTokenPos' => 171,
                'startFilePos' => 1714,
                'endTokenPos' => 172,
                'endFilePos' => 1715,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 76,
            'endLine' => 76,
            'startColumn' => 41,
            'endColumn' => 62,
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
 * Create a test record for the resource.
 * Must be implemented by subclasses that use ownership tests.
 *
 * @param array $attributes
 * @return mixed
 */',
        'startLine' => 76,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'supportsOwnership' => 
      array (
        'name' => 'supportsOwnership',
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
 * Check if the resource supports ownership-based access control.
 *
 * @return bool
 */',
        'startLine' => 93,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'getUserForeignKey' => 
      array (
        'name' => 'getUserForeignKey',
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
 * Get the user foreign key field name for ownership.
 *
 * @return string
 */',
        'startLine' => 103,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'actingAsAdmin' => 
      array (
        'name' => 'actingAsAdmin',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'MicroweberPackages\\User\\Models\\User',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Authenticate as an admin user.
 *
 * @return User
 */',
        'startLine' => 113,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'actingAsUser' => 
      array (
        'name' => 'actingAsUser',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 131,
                'endLine' => 131,
                'startTokenPos' => 382,
                'startFilePos' => 2996,
                'endTokenPos' => 383,
                'endFilePos' => 2997,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 131,
            'endLine' => 131,
            'startColumn' => 37,
            'endColumn' => 58,
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
            'name' => 'MicroweberPackages\\User\\Models\\User',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Authenticate as a non-admin user.
 *
 * @param array $attributes
 * @return User
 */',
        'startLine' => 131,
        'endLine' => 141,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'it_non_admin_cannot_access_resource' => 
      array (
        'name' => 'it_non_admin_cannot_access_resource',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test that non-admin users cannot access the resource.
 */',
        'startLine' => 146,
        'endLine' => 162,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'it_canaccesspanel_returns_false_for_guest' => 
      array (
        'name' => 'it_canaccesspanel_returns_false_for_guest',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test that guests cannot access the admin panel.
 */',
        'startLine' => 167,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'it_admin_can_access_resource' => 
      array (
        'name' => 'it_admin_can_access_resource',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test that admin users can access the resource.
 */',
        'startLine' => 183,
        'endLine' => 192,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'it_user_sees_only_own_team_records' => 
      array (
        'name' => 'it_user_sees_only_own_team_records',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test that users can only see their own records (if resource supports ownership).
 */',
        'startLine' => 197,
        'endLine' => 224,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'it_guest_is_redirected_from_resource_pages' => 
      array (
        'name' => 'it_guest_is_redirected_from_resource_pages',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test that guests are redirected from resource pages.
 */',
        'startLine' => 229,
        'endLine' => 240,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'it_canaccesspanel_returns_true_for_admin' => 
      array (
        'name' => 'it_canaccesspanel_returns_true_for_admin',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test that canAccessPanel returns true for admin users.
 */',
        'startLine' => 245,
        'endLine' => 254,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'aliasName' => NULL,
      ),
      'it_canaccesspanel_returns_false_for_non_admin' => 
      array (
        'name' => 'it_canaccesspanel_returns_false_for_non_admin',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test that canAccessPanel returns false for non-admin users.
 */',
        'startLine' => 259,
        'endLine' => 268,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Filament',
        'declaringClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'implementingClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
        'currentClassName' => 'Tests\\Feature\\Filament\\AuthorizationTest',
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