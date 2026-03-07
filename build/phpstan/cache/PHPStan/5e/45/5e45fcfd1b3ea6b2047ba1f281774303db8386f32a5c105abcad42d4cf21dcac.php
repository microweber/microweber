<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/View/Concerns/ManagesStacks.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\View\Concerns\ManagesStacks
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-0ccbf3bbcad38eb41b126733bc3f426f748753a61daab4da9282c75593c47968-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/View/Concerns/ManagesStacks.php',
      ),
    ),
    'namespace' => 'Illuminate\\View\\Concerns',
    'name' => 'Illuminate\\View\\Concerns\\ManagesStacks',
    'shortName' => 'ManagesStacks',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 179,
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
      'pushes' => 
      array (
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'name' => 'pushes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 26,
            'startFilePos' => 214,
            'endTokenPos' => 27,
            'endFilePos' => 215,
          ),
        ),
        'docComment' => '/**
 * All of the finished, captured push sections.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'prepends' => 
      array (
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'name' => 'prepends',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 38,
            'startFilePos' => 341,
            'endTokenPos' => 39,
            'endFilePos' => 342,
          ),
        ),
        'docComment' => '/**
 * All of the finished, captured prepend sections.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'pushStack' => 
      array (
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'name' => 'pushStack',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 50,
            'startFilePos' => 461,
            'endTokenPos' => 51,
            'endFilePos' => 462,
          ),
        ),
        'docComment' => '/**
 * The stack of in-progress push sections.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 30,
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
      'startPush' => 
      array (
        'name' => 'startPush',
        'parameters' => 
        array (
          'section' => 
          array (
            'name' => 'section',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 37,
            'endLine' => 37,
            'startColumn' => 31,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'content' => 
          array (
            'name' => 'content',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 37,
                'endLine' => 37,
                'startTokenPos' => 69,
                'startFilePos' => 676,
                'endTokenPos' => 69,
                'endFilePos' => 677,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 37,
            'endLine' => 37,
            'startColumn' => 41,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Start injecting content into a push section.
 *
 * @param  string  $section
 * @param  string  $content
 * @return void
 */',
        'startLine' => 37,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'aliasName' => NULL,
      ),
      'stopPush' => 
      array (
        'name' => 'stopPush',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Stop injecting content into a push section.
 *
 * @return string
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 55,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'aliasName' => NULL,
      ),
      'extendPush' => 
      array (
        'name' => 'extendPush',
        'parameters' => 
        array (
          'section' => 
          array (
            'name' => 'section',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 73,
            'endLine' => 73,
            'startColumn' => 35,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'content' => 
          array (
            'name' => 'content',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 73,
            'endLine' => 73,
            'startColumn' => 45,
            'endColumn' => 52,
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
 * Append content to a given push section.
 *
 * @param  string  $section
 * @param  string  $content
 * @return void
 */',
        'startLine' => 73,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'aliasName' => NULL,
      ),
      'startPrepend' => 
      array (
        'name' => 'startPrepend',
        'parameters' => 
        array (
          'section' => 
          array (
            'name' => 'section',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 34,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'content' => 
          array (
            'name' => 'content',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 93,
                'endLine' => 93,
                'startTokenPos' => 337,
                'startFilePos' => 2135,
                'endTokenPos' => 337,
                'endFilePos' => 2136,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 44,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Start prepending content into a push section.
 *
 * @param  string  $section
 * @param  string  $content
 * @return void
 */',
        'startLine' => 93,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'aliasName' => NULL,
      ),
      'stopPrepend' => 
      array (
        'name' => 'stopPrepend',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Stop prepending content into a push section.
 *
 * @return string
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 111,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'aliasName' => NULL,
      ),
      'extendPrepend' => 
      array (
        'name' => 'extendPrepend',
        'parameters' => 
        array (
          'section' => 
          array (
            'name' => 'section',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 38,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'content' => 
          array (
            'name' => 'content',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 48,
            'endColumn' => 55,
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
 * Prepend content to a given stack.
 *
 * @param  string  $section
 * @param  string  $content
 * @return void
 */',
        'startLine' => 129,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'aliasName' => NULL,
      ),
      'yieldPushContent' => 
      array (
        'name' => 'yieldPushContent',
        'parameters' => 
        array (
          'section' => 
          array (
            'name' => 'section',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 149,
            'endLine' => 149,
            'startColumn' => 38,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 149,
                'endLine' => 149,
                'startTokenPos' => 617,
                'startFilePos' => 3666,
                'endTokenPos' => 617,
                'endFilePos' => 3667,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 149,
            'endLine' => 149,
            'startColumn' => 48,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the string contents of a push section.
 *
 * @param  string  $section
 * @param  string  $default
 * @return string
 */',
        'startLine' => 149,
        'endLine' => 166,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'aliasName' => NULL,
      ),
      'flushStacks' => 
      array (
        'name' => 'flushStacks',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Flush all of the stacks.
 *
 * @return void
 */',
        'startLine' => 173,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesStacks',
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