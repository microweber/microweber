<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/tests/Feature/Regression/AiChatRegressionTest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Tests\Feature\Regression\AiChatRegressionTest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-d6dc0dff3a6935032f1e1cb64741d39f8f852719d32c80a86e10930bea29c60d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'filename' => '/home/headless/Documents/GitHub/microweber/tests/Feature/Regression/AiChatRegressionTest.php',
      ),
    ),
    'namespace' => 'Tests\\Feature\\Regression',
    'name' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
    'shortName' => 'AiChatRegressionTest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Full Regression Test Suite - AI Chat and Tools
 *
 * End-to-end testing of AI functionality including:
 * - Chat creation
 * - Tool execution
 * - Message handling
 * - Streaming responses
 *
 * @covers \\Modules\\Ai
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 32,
    'endLine' => 438,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Tests\\TestCase',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Foundation\\Testing\\RefreshDatabase',
      1 => 'Illuminate\\Foundation\\Testing\\WithFaker',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'admin' => 
      array (
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'name' => 'admin',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Models\\User',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'agentService' => 
      array (
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'name' => 'agentService',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Modules\\Ai\\Services\\AiService',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 38,
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
      'setUp' => 
      array (
        'name' => 'setUp',
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
        ),
        'docComment' => NULL,
        'startLine' => 39,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'aliasName' => NULL,
      ),
      'it_complete_ai_chat_flow' => 
      array (
        'name' => 'it_complete_ai_chat_flow',
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
 * Test complete AI chat flow
 */',
        'startLine' => 73,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'aliasName' => NULL,
      ),
      'it_chat_with_create_content_tool' => 
      array (
        'name' => 'it_chat_with_create_content_tool',
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
 * Test chat with CreateContentTool
 */',
        'startLine' => 116,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'aliasName' => NULL,
      ),
      'it_amazon_scraper_tool_execution' => 
      array (
        'name' => 'it_amazon_scraper_tool_execution',
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
 * Test AmazonScraperTool execution
 */',
        'startLine' => 176,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'aliasName' => NULL,
      ),
      'it_google_trends_tool_execution' => 
      array (
        'name' => 'it_google_trends_tool_execution',
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
 * Test GoogleTrendsTool execution
 */',
        'startLine' => 211,
        'endLine' => 238,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'aliasName' => NULL,
      ),
      'it_rag_search_tool_execution' => 
      array (
        'name' => 'it_rag_search_tool_execution',
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
 * Test RAG search tool
 */',
        'startLine' => 243,
        'endLine' => 269,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'aliasName' => NULL,
      ),
      'it_chat_message_streaming' => 
      array (
        'name' => 'it_chat_message_streaming',
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
 * Test chat message streaming
 */',
        'startLine' => 274,
        'endLine' => 294,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'aliasName' => NULL,
      ),
      'it_chat_file_upload' => 
      array (
        'name' => 'it_chat_file_upload',
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
 * Test file upload in chat
 */',
        'startLine' => 299,
        'endLine' => 322,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'aliasName' => NULL,
      ),
      'it_chat_history_is_maintained' => 
      array (
        'name' => 'it_chat_history_is_maintained',
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
 * Test chat history is maintained
 */',
        'startLine' => 327,
        'endLine' => 353,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'aliasName' => NULL,
      ),
      'it_tool_error_handling' => 
      array (
        'name' => 'it_tool_error_handling',
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
 * Test tool error handling
 */',
        'startLine' => 358,
        'endLine' => 380,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'aliasName' => NULL,
      ),
      'it_chat_list_pagination' => 
      array (
        'name' => 'it_chat_list_pagination',
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
 * Test chat list pagination
 */',
        'startLine' => 385,
        'endLine' => 397,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'aliasName' => NULL,
      ),
      'it_chat_deletion' => 
      array (
        'name' => 'it_chat_deletion',
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
 * Test chat deletion
 */',
        'startLine' => 402,
        'endLine' => 420,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'aliasName' => NULL,
      ),
      'it_unauthorized_user_cannot_access_others_chats' => 
      array (
        'name' => 'it_unauthorized_user_cannot_access_others_chats',
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
 * Test unauthorized access to chat
 */',
        'startLine' => 425,
        'endLine' => 437,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\AiChatRegressionTest',
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