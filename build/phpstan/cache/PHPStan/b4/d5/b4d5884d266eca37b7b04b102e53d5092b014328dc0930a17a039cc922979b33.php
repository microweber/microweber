<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Console/View/Components/Factory.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Console\View\Components\Factory
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-e49552b6848a86cbb4dd391273e957cbefe41059b1fd89cd0060f67849adcbf4-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Console\\View\\Components\\Factory',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Console/View/Components/Factory.php',
      ),
    ),
    'namespace' => 'Illuminate\\Console\\View\\Components',
    'name' => 'Illuminate\\Console\\View\\Components\\Factory',
    'shortName' => 'Factory',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method void alert(string $string, int $verbosity = \\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_NORMAL)
 * @method mixed ask(string $question, string $default = null, bool $multiline = false)
 * @method mixed askWithCompletion(string $question, array|callable $choices, string $default = null)
 * @method void bulletList(array $elements, int $verbosity = \\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_NORMAL)
 * @method mixed choice(string $question, array $choices, $default = null, int $attempts = null, bool $multiple = false)
 * @method bool confirm(string $question, bool $default = false)
 * @method void info(string $string, int $verbosity = \\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_NORMAL)
 * @method void success(string $string, int $verbosity = \\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_NORMAL)
 * @method void error(string $string, int $verbosity = \\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_NORMAL)
 * @method void line(string $style, string $string, int $verbosity = \\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_NORMAL)
 * @method void secret(string $question, bool $fallback = true)
 * @method void task(string $description, ?callable $task = null, int $verbosity = \\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_NORMAL)
 * @method void twoColumnDetail(string $first, ?string $second = null, int $verbosity = \\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_NORMAL)
 * @method void warn(string $string, int $verbosity = \\Symfony\\Component\\Console\\Output\\OutputInterface::VERBOSITY_NORMAL)
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 62,
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
      'output' => 
      array (
        'declaringClassName' => 'Illuminate\\Console\\View\\Components\\Factory',
        'implementingClassName' => 'Illuminate\\Console\\View\\Components\\Factory',
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
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 22,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'output' => 
          array (
            'name' => 'output',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 33,
            'endColumn' => 39,
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
 * Creates a new factory instance.
 *
 * @param  \\Illuminate\\Console\\OutputStyle  $output
 * @return void
 */',
        'startLine' => 38,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\View\\Components',
        'declaringClassName' => 'Illuminate\\Console\\View\\Components\\Factory',
        'implementingClassName' => 'Illuminate\\Console\\View\\Components\\Factory',
        'currentClassName' => 'Illuminate\\Console\\View\\Components\\Factory',
        'aliasName' => NULL,
      ),
      '__call' => 
      array (
        'name' => '__call',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 37,
            'endColumn' => 47,
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
 * Dynamically handle calls into the component instance.
 *
 * @param  string  $method
 * @param  array  $parameters
 * @return mixed
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 52,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\View\\Components',
        'declaringClassName' => 'Illuminate\\Console\\View\\Components\\Factory',
        'implementingClassName' => 'Illuminate\\Console\\View\\Components\\Factory',
        'currentClassName' => 'Illuminate\\Console\\View\\Components\\Factory',
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