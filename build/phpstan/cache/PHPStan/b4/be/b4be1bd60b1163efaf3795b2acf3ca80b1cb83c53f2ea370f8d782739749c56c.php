<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Ai/tests/Filament/Resources/AgentChatResourceAuthorizationTest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Ai\Tests\Filament\Resources\AgentChatResourceAuthorizationTest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-8dae118f555d41d192b7d835e5dc8a46c64f43438b14ebf0c39af4c2d31cc369',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Ai/tests/Filament/Resources/AgentChatResourceAuthorizationTest.php',
      ),
    ),
    'namespace' => 'Modules\\Ai\\Tests\\Filament\\Resources',
    'name' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
    'shortName' => 'AgentChatResourceAuthorizationTest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Authorization tests for AgentChatResource.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 231,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Tests\\TestCase',
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
        'startLine' => 27,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Ai\\Tests\\Filament\\Resources',
        'declaringClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'implementingClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'currentClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'aliasName' => NULL,
      ),
      'actingAsUser' => 
      array (
        'name' => 'actingAsUser',
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
 * Authenticate as a non-admin user.
 *
 * @return User
 */',
        'startLine' => 44,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Ai\\Tests\\Filament\\Resources',
        'declaringClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'implementingClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'currentClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
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
 * Test that non-admin users cannot access AgentChat resource.
 */',
        'startLine' => 59,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Tests\\Filament\\Resources',
        'declaringClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'implementingClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'currentClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'aliasName' => NULL,
      ),
      'it_non_admin_cannot_create_chat' => 
      array (
        'name' => 'it_non_admin_cannot_create_chat',
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
 * Test that non-admin users cannot create chats.
 */',
        'startLine' => 78,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Tests\\Filament\\Resources',
        'declaringClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'implementingClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'currentClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'aliasName' => NULL,
      ),
      'it_non_admin_cannot_edit_chat' => 
      array (
        'name' => 'it_non_admin_cannot_edit_chat',
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
 * Test that non-admin users cannot edit chats.
 */',
        'startLine' => 97,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Tests\\Filament\\Resources',
        'declaringClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'implementingClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'currentClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
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
        'startLine' => 119,
        'endLine' => 130,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Tests\\Filament\\Resources',
        'declaringClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'implementingClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'currentClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'aliasName' => NULL,
      ),
      'it_admin_can_access_resource_list' => 
      array (
        'name' => 'it_admin_can_access_resource_list',
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
 * Test that admin users can access the resource list.
 */',
        'startLine' => 135,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Tests\\Filament\\Resources',
        'declaringClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'implementingClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'currentClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'aliasName' => NULL,
      ),
      'it_admin_can_create_chat' => 
      array (
        'name' => 'it_admin_can_create_chat',
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
 * Test that admin users can create chats.
 */',
        'startLine' => 149,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Tests\\Filament\\Resources',
        'declaringClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'implementingClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'currentClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'aliasName' => NULL,
      ),
      'it_admin_can_edit_any_chat' => 
      array (
        'name' => 'it_admin_can_edit_any_chat',
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
 * Test that admin users can edit any chat.
 */',
        'startLine' => 163,
        'endLine' => 176,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Tests\\Filament\\Resources',
        'declaringClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'implementingClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'currentClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'aliasName' => NULL,
      ),
      'it_admin_can_view_any_chat' => 
      array (
        'name' => 'it_admin_can_view_any_chat',
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
 * Test that admin users can view any chat.
 */',
        'startLine' => 181,
        'endLine' => 194,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Tests\\Filament\\Resources',
        'declaringClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'implementingClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'currentClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
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
 * Test that guests are redirected from all resource pages.
 */',
        'startLine' => 199,
        'endLine' => 212,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Tests\\Filament\\Resources',
        'declaringClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'implementingClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'currentClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'aliasName' => NULL,
      ),
      'it_canaccesspanel_behavior' => 
      array (
        'name' => 'it_canaccesspanel_behavior',
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
 * Test that canAccessPanel returns correct values for different user types.
 */',
        'startLine' => 217,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Tests\\Filament\\Resources',
        'declaringClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'implementingClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
        'currentClassName' => 'Modules\\Ai\\Tests\\Filament\\Resources\\AgentChatResourceAuthorizationTest',
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