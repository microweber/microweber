<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Console/Concerns/InteractsWithIO.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Console\Concerns\InteractsWithIO
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-25280fff17acb6207dfc886abd43225a47cc71a82e4b263f91ff45fa56de8880-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Console/Concerns/InteractsWithIO.php',
      ),
    ),
    'namespace' => 'Illuminate\\Console\\Concerns',
    'name' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
    'shortName' => 'InteractsWithIO',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 463,
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
      'components' => 
      array (
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'name' => 'components',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The console components factory.
 *
 * @var \\Illuminate\\Console\\View\\Components\\Factory
 *
 * @internal This property is not meant to be used or overwritten outside the framework.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'input' => 
      array (
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'name' => 'input',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The input interface implementation.
 *
 * @var \\Symfony\\Component\\Console\\Input\\InputInterface
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'output' => 
      array (
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'name' => 'output',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The output interface implementation.
 *
 * @var \\Illuminate\\Console\\OutputStyle
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'verbosity' => 
      array (
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'name' => 'verbosity',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_NORMAL',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 92,
            'startFilePos' => 1154,
            'endTokenPos' => 94,
            'endFilePos' => 1186,
          ),
        ),
        'docComment' => '/**
 * The default verbosity of output commands.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 61,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'verbosityMap' => 
      array (
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'name' => 'verbosityMap',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'v\' => \\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_VERBOSE, \'vv\' => \\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_VERY_VERBOSE, \'vvv\' => \\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_DEBUG, \'quiet\' => \\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_QUIET, \'normal\' => \\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_NORMAL]',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 59,
            'startTokenPos' => 105,
            'startFilePos' => 1351,
            'endTokenPos' => 152,
            'endFilePos' => 1624,
          ),
        ),
        'docComment' => '/**
 * The mapping between human readable verbosity levels and Symfony\'s OutputInterface.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 6,
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
      'hasArgument' => 
      array (
        'name' => 'hasArgument',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 33,
            'endColumn' => 37,
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
 * Determine if the given argument is present.
 *
 * @param  string|int  $name
 * @return bool
 */',
        'startLine' => 67,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'argument' => 
      array (
        'name' => 'argument',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 78,
                'endLine' => 78,
                'startTokenPos' => 194,
                'startFilePos' => 2030,
                'endTokenPos' => 194,
                'endFilePos' => 2033,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 78,
            'endLine' => 78,
            'startColumn' => 30,
            'endColumn' => 40,
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
 * Get the value of a command argument.
 *
 * @param  string|null  $key
 * @return array|string|bool|null
 */',
        'startLine' => 78,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'arguments' => 
      array (
        'name' => 'arguments',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all of the arguments passed to the command.
 *
 * @return array
 */',
        'startLine' => 92,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'hasOption' => 
      array (
        'name' => 'hasOption',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 31,
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
        'docComment' => '/**
 * Determine whether the option is defined in the command signature.
 *
 * @param  string  $name
 * @return bool
 */',
        'startLine' => 103,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'option' => 
      array (
        'name' => 'option',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 114,
                'endLine' => 114,
                'startTokenPos' => 299,
                'startFilePos' => 2776,
                'endTokenPos' => 299,
                'endFilePos' => 2779,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 114,
            'endLine' => 114,
            'startColumn' => 28,
            'endColumn' => 38,
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
 * Get the value of a command option.
 *
 * @param  string|null  $key
 * @return string|array|bool|null
 */',
        'startLine' => 114,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'options' => 
      array (
        'name' => 'options',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all of the options passed to the command.
 *
 * @return array
 */',
        'startLine' => 128,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'confirm' => 
      array (
        'name' => 'confirm',
        'parameters' => 
        array (
          'question' => 
          array (
            'name' => 'question',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 140,
            'endLine' => 140,
            'startColumn' => 29,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 140,
                'endLine' => 140,
                'startTokenPos' => 380,
                'startFilePos' => 3297,
                'endTokenPos' => 380,
                'endFilePos' => 3301,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 140,
            'endLine' => 140,
            'startColumn' => 40,
            'endColumn' => 55,
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
 * Confirm a question with the user.
 *
 * @param  string  $question
 * @param  bool  $default
 * @return bool
 */',
        'startLine' => 140,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'ask' => 
      array (
        'name' => 'ask',
        'parameters' => 
        array (
          'question' => 
          array (
            'name' => 'question',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 25,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 152,
                'endLine' => 152,
                'startTokenPos' => 417,
                'startFilePos' => 3571,
                'endTokenPos' => 417,
                'endFilePos' => 3574,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 36,
            'endColumn' => 50,
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
 * Prompt the user for input.
 *
 * @param  string  $question
 * @param  string|null  $default
 * @return mixed
 */',
        'startLine' => 152,
        'endLine' => 155,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'anticipate' => 
      array (
        'name' => 'anticipate',
        'parameters' => 
        array (
          'question' => 
          array (
            'name' => 'question',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 165,
            'endLine' => 165,
            'startColumn' => 32,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'choices' => 
          array (
            'name' => 'choices',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 165,
            'endLine' => 165,
            'startColumn' => 43,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 165,
                'endLine' => 165,
                'startTokenPos' => 457,
                'startFilePos' => 3918,
                'endTokenPos' => 457,
                'endFilePos' => 3921,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 165,
            'endLine' => 165,
            'startColumn' => 53,
            'endColumn' => 67,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prompt the user for input with auto completion.
 *
 * @param  string  $question
 * @param  array|callable  $choices
 * @param  string|null  $default
 * @return mixed
 */',
        'startLine' => 165,
        'endLine' => 168,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'askWithCompletion' => 
      array (
        'name' => 'askWithCompletion',
        'parameters' => 
        array (
          'question' => 
          array (
            'name' => 'question',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 178,
            'endLine' => 178,
            'startColumn' => 39,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'choices' => 
          array (
            'name' => 'choices',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 178,
            'endLine' => 178,
            'startColumn' => 50,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 178,
                'endLine' => 178,
                'startTokenPos' => 498,
                'startFilePos' => 4288,
                'endTokenPos' => 498,
                'endFilePos' => 4291,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 178,
            'endLine' => 178,
            'startColumn' => 60,
            'endColumn' => 74,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prompt the user for input with auto completion.
 *
 * @param  string  $question
 * @param  array|callable  $choices
 * @param  string|null  $default
 * @return mixed
 */',
        'startLine' => 178,
        'endLine' => 187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'secret' => 
      array (
        'name' => 'secret',
        'parameters' => 
        array (
          'question' => 
          array (
            'name' => 'question',
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
            'startColumn' => 28,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'fallback' => 
          array (
            'name' => 'fallback',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 196,
                'endLine' => 196,
                'startTokenPos' => 571,
                'startFilePos' => 4796,
                'endTokenPos' => 571,
                'endFilePos' => 4799,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 196,
            'endLine' => 196,
            'startColumn' => 39,
            'endColumn' => 54,
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
 * Prompt the user for input but hide the answer from the console.
 *
 * @param  string  $question
 * @param  bool  $fallback
 * @return mixed
 */',
        'startLine' => 196,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'choice' => 
      array (
        'name' => 'choice',
        'parameters' => 
        array (
          'question' => 
          array (
            'name' => 'question',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 215,
            'endLine' => 215,
            'startColumn' => 28,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'choices' => 
          array (
            'name' => 'choices',
            'default' => NULL,
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
            'startLine' => 215,
            'endLine' => 215,
            'startColumn' => 39,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 215,
                'endLine' => 215,
                'startTokenPos' => 635,
                'startFilePos' => 5334,
                'endTokenPos' => 635,
                'endFilePos' => 5337,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 215,
            'endLine' => 215,
            'startColumn' => 55,
            'endColumn' => 69,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'attempts' => 
          array (
            'name' => 'attempts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 215,
                'endLine' => 215,
                'startTokenPos' => 642,
                'startFilePos' => 5352,
                'endTokenPos' => 642,
                'endFilePos' => 5355,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 215,
            'endLine' => 215,
            'startColumn' => 72,
            'endColumn' => 87,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'multiple' => 
          array (
            'name' => 'multiple',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 215,
                'endLine' => 215,
                'startTokenPos' => 649,
                'startFilePos' => 5370,
                'endTokenPos' => 649,
                'endFilePos' => 5374,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 215,
            'endLine' => 215,
            'startColumn' => 90,
            'endColumn' => 106,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Give the user a single choice from an array of answers.
 *
 * @param  string  $question
 * @param  array  $choices
 * @param  string|int|null  $default
 * @param  mixed|null  $attempts
 * @param  bool  $multiple
 * @return string|array
 */',
        'startLine' => 215,
        'endLine' => 222,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'table' => 
      array (
        'name' => 'table',
        'parameters' => 
        array (
          'headers' => 
          array (
            'name' => 'headers',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 233,
            'endLine' => 233,
            'startColumn' => 27,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'rows' => 
          array (
            'name' => 'rows',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 233,
            'endLine' => 233,
            'startColumn' => 37,
            'endColumn' => 41,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'tableStyle' => 
          array (
            'name' => 'tableStyle',
            'default' => 
            array (
              'code' => '\'default\'',
              'attributes' => 
              array (
                'startLine' => 233,
                'endLine' => 233,
                'startTokenPos' => 717,
                'startFilePos' => 5943,
                'endTokenPos' => 717,
                'endFilePos' => 5951,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 233,
            'endLine' => 233,
            'startColumn' => 44,
            'endColumn' => 66,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'columnStyles' => 
          array (
            'name' => 'columnStyles',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 233,
                'endLine' => 233,
                'startTokenPos' => 726,
                'startFilePos' => 5976,
                'endTokenPos' => 727,
                'endFilePos' => 5977,
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
            'startLine' => 233,
            'endLine' => 233,
            'startColumn' => 69,
            'endColumn' => 92,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Format input to textual table.
 *
 * @param  array  $headers
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable|array  $rows
 * @param  \\Symfony\\Component\\Console\\Helper\\TableStyle|string  $tableStyle
 * @param  array  $columnStyles
 * @return void
 */',
        'startLine' => 233,
        'endLine' => 248,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'withProgressBar' => 
      array (
        'name' => 'withProgressBar',
        'parameters' => 
        array (
          'totalSteps' => 
          array (
            'name' => 'totalSteps',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 257,
            'endLine' => 257,
            'startColumn' => 37,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 257,
            'endLine' => 257,
            'startColumn' => 50,
            'endColumn' => 66,
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
 * Execute a given callback while advancing a progress bar.
 *
 * @param  iterable|int  $totalSteps
 * @param  \\Closure  $callback
 * @return mixed|void
 */',
        'startLine' => 257,
        'endLine' => 280,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'info' => 
      array (
        'name' => 'info',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 289,
            'endLine' => 289,
            'startColumn' => 26,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'verbosity' => 
          array (
            'name' => 'verbosity',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 289,
                'endLine' => 289,
                'startTokenPos' => 988,
                'startFilePos' => 7370,
                'endTokenPos' => 988,
                'endFilePos' => 7373,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 289,
            'endLine' => 289,
            'startColumn' => 35,
            'endColumn' => 51,
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
 * Write a string as information output.
 *
 * @param  string  $string
 * @param  int|string|null  $verbosity
 * @return void
 */',
        'startLine' => 289,
        'endLine' => 292,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'line' => 
      array (
        'name' => 'line',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 302,
            'endLine' => 302,
            'startColumn' => 26,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'style' => 
          array (
            'name' => 'style',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 302,
                'endLine' => 302,
                'startTokenPos' => 1024,
                'startFilePos' => 7676,
                'endTokenPos' => 1024,
                'endFilePos' => 7679,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 302,
            'endLine' => 302,
            'startColumn' => 35,
            'endColumn' => 47,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'verbosity' => 
          array (
            'name' => 'verbosity',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 302,
                'endLine' => 302,
                'startTokenPos' => 1031,
                'startFilePos' => 7695,
                'endTokenPos' => 1031,
                'endFilePos' => 7698,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 302,
            'endLine' => 302,
            'startColumn' => 50,
            'endColumn' => 66,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Write a string as standard output.
 *
 * @param  string  $string
 * @param  string|null  $style
 * @param  int|string|null  $verbosity
 * @return void
 */',
        'startLine' => 302,
        'endLine' => 307,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'comment' => 
      array (
        'name' => 'comment',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 316,
            'endLine' => 316,
            'startColumn' => 29,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'verbosity' => 
          array (
            'name' => 'verbosity',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 316,
                'endLine' => 316,
                'startTokenPos' => 1094,
                'startFilePos' => 8064,
                'endTokenPos' => 1094,
                'endFilePos' => 8067,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 316,
            'endLine' => 316,
            'startColumn' => 38,
            'endColumn' => 54,
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
 * Write a string as comment output.
 *
 * @param  string  $string
 * @param  int|string|null  $verbosity
 * @return void
 */',
        'startLine' => 316,
        'endLine' => 319,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'question' => 
      array (
        'name' => 'question',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 328,
            'endLine' => 328,
            'startColumn' => 30,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'verbosity' => 
          array (
            'name' => 'verbosity',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 328,
                'endLine' => 328,
                'startTokenPos' => 1130,
                'startFilePos' => 8346,
                'endTokenPos' => 1130,
                'endFilePos' => 8349,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 328,
            'endLine' => 328,
            'startColumn' => 39,
            'endColumn' => 55,
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
 * Write a string as question output.
 *
 * @param  string  $string
 * @param  int|string|null  $verbosity
 * @return void
 */',
        'startLine' => 328,
        'endLine' => 331,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'error' => 
      array (
        'name' => 'error',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 340,
            'endLine' => 340,
            'startColumn' => 27,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'verbosity' => 
          array (
            'name' => 'verbosity',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 340,
                'endLine' => 340,
                'startTokenPos' => 1166,
                'startFilePos' => 8623,
                'endTokenPos' => 1166,
                'endFilePos' => 8626,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 340,
            'endLine' => 340,
            'startColumn' => 36,
            'endColumn' => 52,
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
 * Write a string as error output.
 *
 * @param  string  $string
 * @param  int|string|null  $verbosity
 * @return void
 */',
        'startLine' => 340,
        'endLine' => 343,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'warn' => 
      array (
        'name' => 'warn',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 352,
            'endLine' => 352,
            'startColumn' => 26,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'verbosity' => 
          array (
            'name' => 'verbosity',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 352,
                'endLine' => 352,
                'startTokenPos' => 1202,
                'startFilePos' => 8898,
                'endTokenPos' => 1202,
                'endFilePos' => 8901,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 352,
            'endLine' => 352,
            'startColumn' => 35,
            'endColumn' => 51,
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
 * Write a string as warning output.
 *
 * @param  string  $string
 * @param  int|string|null  $verbosity
 * @return void
 */',
        'startLine' => 352,
        'endLine' => 361,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'alert' => 
      array (
        'name' => 'alert',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 370,
            'endLine' => 370,
            'startColumn' => 27,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'verbosity' => 
          array (
            'name' => 'verbosity',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 370,
                'endLine' => 370,
                'startTokenPos' => 1290,
                'startFilePos' => 9383,
                'endTokenPos' => 1290,
                'endFilePos' => 9386,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 370,
            'endLine' => 370,
            'startColumn' => 36,
            'endColumn' => 52,
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
 * Write a string in an alert box.
 *
 * @param  string  $string
 * @param  int|string|null  $verbosity
 * @return void
 */',
        'startLine' => 370,
        'endLine' => 379,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'newLine' => 
      array (
        'name' => 'newLine',
        'parameters' => 
        array (
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 387,
                'endLine' => 387,
                'startTokenPos' => 1388,
                'startFilePos' => 9823,
                'endTokenPos' => 1388,
                'endFilePos' => 9823,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 387,
            'endLine' => 387,
            'startColumn' => 29,
            'endColumn' => 38,
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
 * Write a blank line.
 *
 * @param  int  $count
 * @return $this
 */',
        'startLine' => 387,
        'endLine' => 392,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'setInput' => 
      array (
        'name' => 'setInput',
        'parameters' => 
        array (
          'input' => 
          array (
            'name' => 'input',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\Console\\Input\\InputInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 400,
            'endLine' => 400,
            'startColumn' => 30,
            'endColumn' => 50,
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
 * Set the input interface implementation.
 *
 * @param  \\Symfony\\Component\\Console\\Input\\InputInterface  $input
 * @return void
 */',
        'startLine' => 400,
        'endLine' => 403,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'setOutput' => 
      array (
        'name' => 'setOutput',
        'parameters' => 
        array (
          'output' => 
          array (
            'name' => 'output',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Console\\OutputStyle',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 411,
            'endLine' => 411,
            'startColumn' => 31,
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
 * Set the output interface implementation.
 *
 * @param  \\Illuminate\\Console\\OutputStyle  $output
 * @return void
 */',
        'startLine' => 411,
        'endLine' => 414,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'setVerbosity' => 
      array (
        'name' => 'setVerbosity',
        'parameters' => 
        array (
          'level' => 
          array (
            'name' => 'level',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 422,
            'endLine' => 422,
            'startColumn' => 37,
            'endColumn' => 42,
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
 * Set the verbosity level.
 *
 * @param  string|int  $level
 * @return void
 */',
        'startLine' => 422,
        'endLine' => 425,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'parseVerbosity' => 
      array (
        'name' => 'parseVerbosity',
        'parameters' => 
        array (
          'level' => 
          array (
            'name' => 'level',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 433,
                'endLine' => 433,
                'startTokenPos' => 1503,
                'startFilePos' => 10831,
                'endTokenPos' => 1503,
                'endFilePos' => 10834,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 433,
            'endLine' => 433,
            'startColumn' => 39,
            'endColumn' => 51,
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
 * Get the verbosity level in terms of Symfony\'s OutputInterface level.
 *
 * @param  string|int|null  $level
 * @return int
 */',
        'startLine' => 433,
        'endLine' => 442,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'getOutput' => 
      array (
        'name' => 'getOutput',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the output implementation.
 *
 * @return \\Illuminate\\Console\\OutputStyle
 */',
        'startLine' => 449,
        'endLine' => 452,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'aliasName' => NULL,
      ),
      'outputComponents' => 
      array (
        'name' => 'outputComponents',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the output component factory implementation.
 *
 * @return \\Illuminate\\Console\\View\\Components\\Factory
 */',
        'startLine' => 459,
        'endLine' => 462,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithIO',
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