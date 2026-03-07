<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Exceptions/Handler.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Foundation\Exceptions\Handler
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-0b13ec1acc40831616d7b24d9d68e0f784d05fd8bb3b88751b185b6debe02e34-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Exceptions/Handler.php',
      ),
    ),
    'namespace' => 'Illuminate\\Foundation\\Exceptions',
    'name' => 'Illuminate\\Foundation\\Exceptions\\Handler',
    'shortName' => 'Handler',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 57,
    'endLine' => 1075,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Debug\\ExceptionHandler',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\Traits\\ReflectsClosures',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'container' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'container',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The container implementation.
 *
 * @var \\Illuminate\\Contracts\\Container\\Container
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'dontReport' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'dontReport',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 73,
            'endLine' => 73,
            'startTokenPos' => 308,
            'startFilePos' => 2798,
            'endTokenPos' => 309,
            'endFilePos' => 2799,
          ),
        ),
        'docComment' => '/**
 * A list of the exception types that are not reported.
 *
 * @var array<int, class-string<\\Throwable>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 73,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'reportCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'reportCallbacks',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 80,
            'endLine' => 80,
            'startTokenPos' => 320,
            'startFilePos' => 2984,
            'endTokenPos' => 321,
            'endFilePos' => 2985,
          ),
        ),
        'docComment' => '/**
 * The callbacks that should be used during reporting.
 *
 * @var \\Illuminate\\Foundation\\Exceptions\\ReportableHandler[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 80,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'levels' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'levels',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 87,
            'endLine' => 87,
            'startTokenPos' => 332,
            'startFilePos' => 3173,
            'endTokenPos' => 333,
            'endFilePos' => 3174,
          ),
        ),
        'docComment' => '/**
 * A map of exceptions with their corresponding custom log levels.
 *
 * @var array<class-string<\\Throwable>, \\Psr\\Log\\LogLevel::*>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 87,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'throttleCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'throttleCallbacks',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 94,
            'endLine' => 94,
            'startTokenPos' => 344,
            'startFilePos' => 3330,
            'endTokenPos' => 345,
            'endFilePos' => 3331,
          ),
        ),
        'docComment' => '/**
 * The callbacks that should be used to throttle reportable exceptions.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 94,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'contextCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'contextCallbacks',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 101,
            'endLine' => 101,
            'startTokenPos' => 356,
            'startFilePos' => 3484,
            'endTokenPos' => 357,
            'endFilePos' => 3485,
          ),
        ),
        'docComment' => '/**
 * The callbacks that should be used to build exception context data.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 101,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'renderCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'renderCallbacks',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 108,
            'endLine' => 108,
            'startTokenPos' => 368,
            'startFilePos' => 3627,
            'endTokenPos' => 369,
            'endFilePos' => 3628,
          ),
        ),
        'docComment' => '/**
 * The callbacks that should be used during rendering.
 *
 * @var \\Closure[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 108,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'shouldRenderJsonWhenCallback' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'shouldRenderJsonWhenCallback',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The callback that determines if the exception handler response should be JSON.
 *
 * @var callable|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 115,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 44,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'finalizeResponseCallback' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'finalizeResponseCallback',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The callback that prepares responses to be returned to the browser.
 *
 * @var callable|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 122,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'exceptionMap' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'exceptionMap',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 129,
            'endLine' => 129,
            'startTokenPos' => 394,
            'startFilePos' => 4110,
            'endTokenPos' => 395,
            'endFilePos' => 4111,
          ),
        ),
        'docComment' => '/**
 * The registered exception mappings.
 *
 * @var array<string, \\Closure>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 129,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'hashThrottleKeys' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'hashThrottleKeys',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 136,
            'endLine' => 136,
            'startTokenPos' => 406,
            'startFilePos' => 4244,
            'endTokenPos' => 406,
            'endFilePos' => 4247,
          ),
        ),
        'docComment' => '/**
 * Indicates that throttled keys should be hashed.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 136,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'internalDontReport' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'internalDontReport',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\\Illuminate\\Auth\\AuthenticationException::class, \\Illuminate\\Auth\\Access\\AuthorizationException::class, \\Illuminate\\Routing\\Exceptions\\BackedEnumCaseNotFoundException::class, \\Symfony\\Component\\HttpKernel\\Exception\\HttpException::class, \\Illuminate\\Http\\Exceptions\\HttpResponseException::class, \\Illuminate\\Database\\Eloquent\\ModelNotFoundException::class, \\Illuminate\\Database\\MultipleRecordsFoundException::class, \\Illuminate\\Database\\RecordNotFoundException::class, \\Illuminate\\Database\\RecordsNotFoundException::class, \\Symfony\\Component\\HttpFoundation\\Exception\\RequestExceptionInterface::class, \\Illuminate\\Session\\TokenMismatchException::class, \\Illuminate\\Validation\\ValidationException::class]',
          'attributes' => 
          array (
            'startLine' => 143,
            'endLine' => 156,
            'startTokenPos' => 417,
            'startFilePos' => 4434,
            'endTokenPos' => 479,
            'endFilePos' => 4918,
          ),
        ),
        'docComment' => '/**
 * A list of the internal exception types that should not be reported.
 *
 * @var array<int, class-string<\\Throwable>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 143,
        'endLine' => 156,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'dontFlash' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'dontFlash',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'current_password\', \'password\', \'password_confirmation\']',
          'attributes' => 
          array (
            'startLine' => 163,
            'endLine' => 167,
            'startTokenPos' => 490,
            'startFilePos' => 5081,
            'endTokenPos' => 501,
            'endFilePos' => 5168,
          ),
        ),
        'docComment' => '/**
 * A list of the inputs that are never flashed for validation exceptions.
 *
 * @var array<int, string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 163,
        'endLine' => 167,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'withoutDuplicates' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'withoutDuplicates',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 174,
            'endLine' => 174,
            'startTokenPos' => 512,
            'startFilePos' => 5321,
            'endTokenPos' => 512,
            'endFilePos' => 5325,
          ),
        ),
        'docComment' => '/**
 * Indicates that an exception instance should only be reported once.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 174,
        'endLine' => 174,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'reportedExceptionMap' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'name' => 'reportedExceptionMap',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The already reported exception map.
 *
 * @var \\WeakMap
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 181,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 36,
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
          'container' => 
          array (
            'name' => 'container',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Container\\Container',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 189,
            'endLine' => 189,
            'startColumn' => 33,
            'endColumn' => 52,
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
 * Create a new exception handler instance.
 *
 * @param  \\Illuminate\\Contracts\\Container\\Container  $container
 * @return void
 */',
        'startLine' => 189,
        'endLine' => 196,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'register' => 
      array (
        'name' => 'register',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register the exception handling callbacks for the application.
 *
 * @return void
 */',
        'startLine' => 203,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'reportable' => 
      array (
        'name' => 'reportable',
        'parameters' => 
        array (
          'reportUsing' => 
          array (
            'name' => 'reportUsing',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'callable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 214,
            'endLine' => 214,
            'startColumn' => 32,
            'endColumn' => 52,
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
 * Register a reportable callback.
 *
 * @param  callable  $reportUsing
 * @return \\Illuminate\\Foundation\\Exceptions\\ReportableHandler
 */',
        'startLine' => 214,
        'endLine' => 223,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'renderable' => 
      array (
        'name' => 'renderable',
        'parameters' => 
        array (
          'renderUsing' => 
          array (
            'name' => 'renderUsing',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'callable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 231,
            'endLine' => 231,
            'startColumn' => 32,
            'endColumn' => 52,
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
 * Register a renderable callback.
 *
 * @param  callable  $renderUsing
 * @return $this
 */',
        'startLine' => 231,
        'endLine' => 240,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'map' => 
      array (
        'name' => 'map',
        'parameters' => 
        array (
          'from' => 
          array (
            'name' => 'from',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 251,
            'endLine' => 251,
            'startColumn' => 25,
            'endColumn' => 29,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'to' => 
          array (
            'name' => 'to',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 251,
                'endLine' => 251,
                'startTokenPos' => 738,
                'startFilePos' => 7100,
                'endTokenPos' => 738,
                'endFilePos' => 7103,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 251,
            'endLine' => 251,
            'startColumn' => 32,
            'endColumn' => 41,
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
 * Register a new exception mapping.
 *
 * @param  \\Closure|string  $from
 * @param  \\Closure|string|null  $to
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 251,
        'endLine' => 268,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'dontReport' => 
      array (
        'name' => 'dontReport',
        'parameters' => 
        array (
          'exceptions' => 
          array (
            'name' => 'exceptions',
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
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
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
            'startLine' => 278,
            'endLine' => 278,
            'startColumn' => 32,
            'endColumn' => 55,
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
 * Indicate that the given exception type should not be reported.
 *
 * Alias of "ignore".
 *
 * @param  array|string  $exceptions
 * @return $this
 */',
        'startLine' => 278,
        'endLine' => 281,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'ignore' => 
      array (
        'name' => 'ignore',
        'parameters' => 
        array (
          'exceptions' => 
          array (
            'name' => 'exceptions',
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
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
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
            'startLine' => 289,
            'endLine' => 289,
            'startColumn' => 28,
            'endColumn' => 51,
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
 * Indicate that the given exception type should not be reported.
 *
 * @param  array|string  $exceptions
 * @return $this
 */',
        'startLine' => 289,
        'endLine' => 296,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'dontFlash' => 
      array (
        'name' => 'dontFlash',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
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
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
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
            'startLine' => 304,
            'endLine' => 304,
            'startColumn' => 31,
            'endColumn' => 54,
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
 * Indicate that the given attributes should never be flashed to the session on validation errors.
 *
 * @param  array|string  $attributes
 * @return $this
 */',
        'startLine' => 304,
        'endLine' => 311,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'level' => 
      array (
        'name' => 'level',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 320,
            'endLine' => 320,
            'startColumn' => 27,
            'endColumn' => 31,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 320,
            'endLine' => 320,
            'startColumn' => 34,
            'endColumn' => 39,
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
 * Set the log level for the given exception type.
 *
 * @param  class-string<\\Throwable>  $type
 * @param  \\Psr\\Log\\LogLevel::*  $level
 * @return $this
 */',
        'startLine' => 320,
        'endLine' => 325,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'report' => 
      array (
        'name' => 'report',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 335,
            'endLine' => 335,
            'startColumn' => 28,
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
 * Report or log an exception.
 *
 * @param  \\Throwable  $e
 * @return void
 *
 * @throws \\Throwable
 */',
        'startLine' => 335,
        'endLine' => 344,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'reportThrowable' => 
      array (
        'name' => 'reportThrowable',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 354,
            'endLine' => 354,
            'startColumn' => 40,
            'endColumn' => 51,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Reports error based on report method on exception or to logger.
 *
 * @param  \\Throwable  $e
 * @return void
 *
 * @throws \\Throwable
 */',
        'startLine' => 354,
        'endLine' => 382,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'shouldReport' => 
      array (
        'name' => 'shouldReport',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 390,
            'endLine' => 390,
            'startColumn' => 34,
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
 * Determine if the exception should be reported.
 *
 * @param  \\Throwable  $e
 * @return bool
 */',
        'startLine' => 390,
        'endLine' => 393,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'shouldntReport' => 
      array (
        'name' => 'shouldntReport',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 401,
            'endLine' => 401,
            'startColumn' => 39,
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
 * Determine if the exception is in the "do not report" list.
 *
 * @param  \\Throwable  $e
 * @return bool
 */',
        'startLine' => 401,
        'endLine' => 433,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'throttle' => 
      array (
        'name' => 'throttle',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 441,
            'endLine' => 441,
            'startColumn' => 33,
            'endColumn' => 44,
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
 * Throttle the given exception.
 *
 * @param  \\Throwable  $e
 * @return \\Illuminate\\Support\\Lottery|\\Illuminate\\Cache\\RateLimiting\\Limit|null
 */',
        'startLine' => 441,
        'endLine' => 456,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'throttleUsing' => 
      array (
        'name' => 'throttleUsing',
        'parameters' => 
        array (
          'throttleUsing' => 
          array (
            'name' => 'throttleUsing',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'callable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 464,
            'endLine' => 464,
            'startColumn' => 35,
            'endColumn' => 57,
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
 * Specify the callback that should be used to throttle reportable exceptions.
 *
 * @param  callable  $throttleUsing
 * @return $this
 */',
        'startLine' => 464,
        'endLine' => 473,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'stopIgnoring' => 
      array (
        'name' => 'stopIgnoring',
        'parameters' => 
        array (
          'exceptions' => 
          array (
            'name' => 'exceptions',
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
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
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
            'startLine' => 481,
            'endLine' => 481,
            'startColumn' => 34,
            'endColumn' => 57,
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
 * Remove the given exception class from the list of exceptions that should be ignored.
 *
 * @param  array|string  $exceptions
 * @return $this
 */',
        'startLine' => 481,
        'endLine' => 492,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'buildExceptionContext' => 
      array (
        'name' => 'buildExceptionContext',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 500,
            'endLine' => 500,
            'startColumn' => 46,
            'endColumn' => 57,
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
 * Create the context array for logging the given exception.
 *
 * @param  \\Throwable  $e
 * @return array
 */',
        'startLine' => 500,
        'endLine' => 507,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'exceptionContext' => 
      array (
        'name' => 'exceptionContext',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 515,
            'endLine' => 515,
            'startColumn' => 41,
            'endColumn' => 52,
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
 * Get the default exception context variables for logging.
 *
 * @param  \\Throwable  $e
 * @return array
 */',
        'startLine' => 515,
        'endLine' => 528,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'context' => 
      array (
        'name' => 'context',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the default context variables for logging.
 *
 * @return array
 */',
        'startLine' => 535,
        'endLine' => 544,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'buildContextUsing' => 
      array (
        'name' => 'buildContextUsing',
        'parameters' => 
        array (
          'contextCallback' => 
          array (
            'name' => 'contextCallback',
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
            'startLine' => 552,
            'endLine' => 552,
            'startColumn' => 39,
            'endColumn' => 62,
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
 * Register a closure that should be used to build exception context data.
 *
 * @param  \\Closure  $contextCallback
 * @return $this
 */',
        'startLine' => 552,
        'endLine' => 557,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'render' => 
      array (
        'name' => 'render',
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
            'startLine' => 568,
            'endLine' => 568,
            'startColumn' => 28,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 568,
            'endLine' => 568,
            'startColumn' => 38,
            'endColumn' => 49,
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
 * Render an exception into an HTTP response.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Throwable  $e
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 *
 * @throws \\Throwable
 */',
        'startLine' => 568,
        'endLine' => 596,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'finalizeRenderedResponse' => 
      array (
        'name' => 'finalizeRenderedResponse',
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
            'startLine' => 606,
            'endLine' => 606,
            'startColumn' => 49,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'response' => 
          array (
            'name' => 'response',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 606,
            'endLine' => 606,
            'startColumn' => 59,
            'endColumn' => 67,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 606,
            'endLine' => 606,
            'startColumn' => 70,
            'endColumn' => 81,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepare the final, rendered response to be returned to the browser.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Symfony\\Component\\HttpFoundation\\Response  $response
 * @param  \\Throwable  $e
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 606,
        'endLine' => 611,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'respondUsing' => 
      array (
        'name' => 'respondUsing',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 619,
            'endLine' => 619,
            'startColumn' => 34,
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
 * Prepare the final, rendered response for an exception using the given callback.
 *
 * @param  callable  $callback
 * @return $this
 */',
        'startLine' => 619,
        'endLine' => 624,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'prepareException' => 
      array (
        'name' => 'prepareException',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 632,
            'endLine' => 632,
            'startColumn' => 41,
            'endColumn' => 52,
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
 * Prepare exception for rendering.
 *
 * @param  \\Throwable  $e
 * @return \\Throwable
 */',
        'startLine' => 632,
        'endLine' => 647,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'mapException' => 
      array (
        'name' => 'mapException',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 655,
            'endLine' => 655,
            'startColumn' => 37,
            'endColumn' => 48,
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
 * Map the exception using a registered mapper if possible.
 *
 * @param  \\Throwable  $e
 * @return \\Throwable
 */',
        'startLine' => 655,
        'endLine' => 669,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'renderViaCallbacks' => 
      array (
        'name' => 'renderViaCallbacks',
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
            'startLine' => 680,
            'endLine' => 680,
            'startColumn' => 43,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 680,
            'endLine' => 680,
            'startColumn' => 53,
            'endColumn' => 64,
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
 * Try to render a response from request and exception via render callbacks.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Throwable  $e
 * @return mixed
 *
 * @throws \\ReflectionException
 */',
        'startLine' => 680,
        'endLine' => 693,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'renderExceptionResponse' => 
      array (
        'name' => 'renderExceptionResponse',
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
            'startLine' => 702,
            'endLine' => 702,
            'startColumn' => 48,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 702,
            'endLine' => 702,
            'startColumn' => 58,
            'endColumn' => 69,
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
 * Render a default exception response if any.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Throwable  $e
 * @return \\Illuminate\\Http\\Response|\\Illuminate\\Http\\JsonResponse|\\Illuminate\\Http\\RedirectResponse
 */',
        'startLine' => 702,
        'endLine' => 707,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'unauthenticated' => 
      array (
        'name' => 'unauthenticated',
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
            'startLine' => 716,
            'endLine' => 716,
            'startColumn' => 40,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'exception' => 
          array (
            'name' => 'exception',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Auth\\AuthenticationException',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 716,
            'endLine' => 716,
            'startColumn' => 50,
            'endColumn' => 83,
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
 * Convert an authentication exception into a response.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Illuminate\\Auth\\AuthenticationException  $exception
 * @return \\Illuminate\\Http\\Response|\\Illuminate\\Http\\JsonResponse|\\Illuminate\\Http\\RedirectResponse
 */',
        'startLine' => 716,
        'endLine' => 721,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'convertValidationExceptionToResponse' => 
      array (
        'name' => 'convertValidationExceptionToResponse',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Validation\\ValidationException',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 730,
            'endLine' => 730,
            'startColumn' => 61,
            'endColumn' => 82,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 730,
            'endLine' => 730,
            'startColumn' => 85,
            'endColumn' => 92,
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
 * Create a response object from the given validation exception.
 *
 * @param  \\Illuminate\\Validation\\ValidationException  $e
 * @param  \\Illuminate\\Http\\Request  $request
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 730,
        'endLine' => 739,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'invalid' => 
      array (
        'name' => 'invalid',
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
            'startLine' => 748,
            'endLine' => 748,
            'startColumn' => 32,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'exception' => 
          array (
            'name' => 'exception',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Validation\\ValidationException',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 748,
            'endLine' => 748,
            'startColumn' => 42,
            'endColumn' => 71,
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
 * Convert a validation exception into a response.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Illuminate\\Validation\\ValidationException  $exception
 * @return \\Illuminate\\Http\\Response|\\Illuminate\\Http\\JsonResponse|\\Illuminate\\Http\\RedirectResponse
 */',
        'startLine' => 748,
        'endLine' => 753,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'invalidJson' => 
      array (
        'name' => 'invalidJson',
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
            'startLine' => 762,
            'endLine' => 762,
            'startColumn' => 36,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'exception' => 
          array (
            'name' => 'exception',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Validation\\ValidationException',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 762,
            'endLine' => 762,
            'startColumn' => 46,
            'endColumn' => 75,
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
 * Convert a validation exception into a JSON response.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Illuminate\\Validation\\ValidationException  $exception
 * @return \\Illuminate\\Http\\JsonResponse
 */',
        'startLine' => 762,
        'endLine' => 768,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'shouldReturnJson' => 
      array (
        'name' => 'shouldReturnJson',
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
            'startLine' => 777,
            'endLine' => 777,
            'startColumn' => 41,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 777,
            'endLine' => 777,
            'startColumn' => 51,
            'endColumn' => 62,
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
 * Determine if the exception handler response should be JSON.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Throwable  $e
 * @return bool
 */',
        'startLine' => 777,
        'endLine' => 782,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'shouldRenderJsonWhen' => 
      array (
        'name' => 'shouldRenderJsonWhen',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 790,
            'endLine' => 790,
            'startColumn' => 42,
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
 * Register the callable that determines if the exception handler response should be JSON.
 *
 * @param  callable(\\Illuminate\\Http\\Request $request, \\Throwable): bool  $callback
 * @return $this
 */',
        'startLine' => 790,
        'endLine' => 795,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'prepareResponse' => 
      array (
        'name' => 'prepareResponse',
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
            'startLine' => 804,
            'endLine' => 804,
            'startColumn' => 40,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 804,
            'endLine' => 804,
            'startColumn' => 50,
            'endColumn' => 61,
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
 * Prepare a response for the given exception.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Throwable  $e
 * @return \\Illuminate\\Http\\Response|\\Illuminate\\Http\\JsonResponse|\\Illuminate\\Http\\RedirectResponse
 */',
        'startLine' => 804,
        'endLine' => 817,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'convertExceptionToResponse' => 
      array (
        'name' => 'convertExceptionToResponse',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 825,
            'endLine' => 825,
            'startColumn' => 51,
            'endColumn' => 62,
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
 * Create a Symfony response for the given exception.
 *
 * @param  \\Throwable  $e
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 825,
        'endLine' => 832,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'renderExceptionContent' => 
      array (
        'name' => 'renderExceptionContent',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 840,
            'endLine' => 840,
            'startColumn' => 47,
            'endColumn' => 58,
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
 * Get the response content for the given exception.
 *
 * @param  \\Throwable  $e
 * @return string
 */',
        'startLine' => 840,
        'endLine' => 855,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'renderExceptionWithCustomRenderer' => 
      array (
        'name' => 'renderExceptionWithCustomRenderer',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 863,
            'endLine' => 863,
            'startColumn' => 58,
            'endColumn' => 69,
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
 * Render an exception to a string using the registered `ExceptionRenderer`.
 *
 * @param  \\Throwable  $e
 * @return string
 */',
        'startLine' => 863,
        'endLine' => 866,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'renderExceptionWithSymfony' => 
      array (
        'name' => 'renderExceptionWithSymfony',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 875,
            'endLine' => 875,
            'startColumn' => 51,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'debug' => 
          array (
            'name' => 'debug',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 875,
            'endLine' => 875,
            'startColumn' => 65,
            'endColumn' => 70,
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
 * Render an exception to a string using Symfony.
 *
 * @param  \\Throwable  $e
 * @param  bool  $debug
 * @return string
 */',
        'startLine' => 875,
        'endLine' => 880,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'renderHttpException' => 
      array (
        'name' => 'renderHttpException',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\HttpKernel\\Exception\\HttpExceptionInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 888,
            'endLine' => 888,
            'startColumn' => 44,
            'endColumn' => 68,
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
 * Render the given HttpException.
 *
 * @param  \\Symfony\\Component\\HttpKernel\\Exception\\HttpExceptionInterface  $e
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 888,
        'endLine' => 906,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'registerErrorViewPaths' => 
      array (
        'name' => 'registerErrorViewPaths',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register the error template hint paths.
 *
 * @return void
 */',
        'startLine' => 913,
        'endLine' => 916,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'getHttpExceptionView' => 
      array (
        'name' => 'getHttpExceptionView',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\HttpKernel\\Exception\\HttpExceptionInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 924,
            'endLine' => 924,
            'startColumn' => 45,
            'endColumn' => 69,
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
 * Get the view used to render HTTP exceptions.
 *
 * @param  \\Symfony\\Component\\HttpKernel\\Exception\\HttpExceptionInterface  $e
 * @return string|null
 */',
        'startLine' => 924,
        'endLine' => 939,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'toIlluminateResponse' => 
      array (
        'name' => 'toIlluminateResponse',
        'parameters' => 
        array (
          'response' => 
          array (
            'name' => 'response',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 948,
            'endLine' => 948,
            'startColumn' => 45,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 948,
            'endLine' => 948,
            'startColumn' => 56,
            'endColumn' => 67,
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
 * Map the given exception into an Illuminate response.
 *
 * @param  \\Symfony\\Component\\HttpFoundation\\Response  $response
 * @param  \\Throwable  $e
 * @return \\Illuminate\\Http\\Response|\\Illuminate\\Http\\RedirectResponse
 */',
        'startLine' => 948,
        'endLine' => 961,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'prepareJsonResponse' => 
      array (
        'name' => 'prepareJsonResponse',
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
            'startLine' => 970,
            'endLine' => 970,
            'startColumn' => 44,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 970,
            'endLine' => 970,
            'startColumn' => 54,
            'endColumn' => 65,
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
 * Prepare a JSON response for the given exception.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Throwable  $e
 * @return \\Illuminate\\Http\\JsonResponse
 */',
        'startLine' => 970,
        'endLine' => 978,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'convertExceptionToArray' => 
      array (
        'name' => 'convertExceptionToArray',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 986,
            'endLine' => 986,
            'startColumn' => 48,
            'endColumn' => 59,
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
 * Convert the given exception to an array.
 *
 * @param  \\Throwable  $e
 * @return array
 */',
        'startLine' => 986,
        'endLine' => 997,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'renderForConsole' => 
      array (
        'name' => 'renderForConsole',
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
            'startLine' => 1008,
            'endLine' => 1008,
            'startColumn' => 38,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1008,
            'endLine' => 1008,
            'startColumn' => 47,
            'endColumn' => 58,
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
 * Render an exception to the console.
 *
 * @param  \\Symfony\\Component\\Console\\Output\\OutputInterface  $output
 * @param  \\Throwable  $e
 * @return void
 *
 * @internal This method is not meant to be used or overwritten outside the framework.
 */',
        'startLine' => 1008,
        'endLine' => 1028,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'dontReportDuplicates' => 
      array (
        'name' => 'dontReportDuplicates',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Do not report duplicate exceptions.
 *
 * @return $this
 */',
        'startLine' => 1035,
        'endLine' => 1040,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'isHttpException' => 
      array (
        'name' => 'isHttpException',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1048,
            'endLine' => 1048,
            'startColumn' => 40,
            'endColumn' => 51,
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
 * Determine if the given exception is an HTTP exception.
 *
 * @param  \\Throwable  $e
 * @return bool
 */',
        'startLine' => 1048,
        'endLine' => 1051,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'mapLogLevel' => 
      array (
        'name' => 'mapLogLevel',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1059,
            'endLine' => 1059,
            'startColumn' => 36,
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
 * Map the exception to a log level.
 *
 * @param  \\Throwable  $e
 * @return \\Psr\\Log\\LogLevel::*
 */',
        'startLine' => 1059,
        'endLine' => 1064,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'aliasName' => NULL,
      ),
      'newLogger' => 
      array (
        'name' => 'newLogger',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new logger instance.
 *
 * @return \\Psr\\Log\\LoggerInterface
 */',
        'startLine' => 1071,
        'endLine' => 1074,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Exceptions',
        'declaringClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'implementingClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
        'currentClassName' => 'Illuminate\\Foundation\\Exceptions\\Handler',
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