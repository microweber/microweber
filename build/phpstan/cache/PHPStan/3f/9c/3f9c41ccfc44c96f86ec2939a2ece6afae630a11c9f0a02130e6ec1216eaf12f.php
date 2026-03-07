<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Http.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Support\Facades\Http
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-ca91e5ae2fd8f7b9dd6862daf7f31d46a6821a274db6c8a20367ea230ac90d29-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Support\\Facades\\Http',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Http.php',
      ),
    ),
    'namespace' => 'Illuminate\\Support\\Facades',
    'name' => 'Illuminate\\Support\\Facades\\Http',
    'shortName' => 'Http',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method static \\Illuminate\\Http\\Client\\Factory globalMiddleware(callable $middleware)
 * @method static \\Illuminate\\Http\\Client\\Factory globalRequestMiddleware(callable $middleware)
 * @method static \\Illuminate\\Http\\Client\\Factory globalResponseMiddleware(callable $middleware)
 * @method static \\Illuminate\\Http\\Client\\Factory globalOptions(\\Closure|array $options)
 * @method static \\GuzzleHttp\\Promise\\PromiseInterface response(array|string|null $body = null, int $status = 200, array $headers = [])
 * @method static \\GuzzleHttp\\Promise\\PromiseInterface failedConnection(string|null $message = null)
 * @method static \\Illuminate\\Http\\Client\\ResponseSequence sequence(array $responses = [])
 * @method static bool preventingStrayRequests()
 * @method static \\Illuminate\\Http\\Client\\Factory allowStrayRequests()
 * @method static void recordRequestResponsePair(\\Illuminate\\Http\\Client\\Request $request, \\Illuminate\\Http\\Client\\Response|null $response)
 * @method static void assertSent(callable $callback)
 * @method static void assertSentInOrder(array $callbacks)
 * @method static void assertNotSent(callable $callback)
 * @method static void assertNothingSent()
 * @method static void assertSentCount(int $count)
 * @method static void assertSequencesAreEmpty()
 * @method static \\Illuminate\\Support\\Collection recorded(callable $callback = null)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest createPendingRequest()
 * @method static \\Illuminate\\Contracts\\Events\\Dispatcher|null getDispatcher()
 * @method static array getGlobalMiddleware()
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 * @method static mixed macroCall(string $method, array $parameters)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest baseUrl(string $url)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withBody(\\Psr\\Http\\Message\\StreamInterface|string $content, string $contentType = \'application/json\')
 * @method static \\Illuminate\\Http\\Client\\PendingRequest asJson()
 * @method static \\Illuminate\\Http\\Client\\PendingRequest asForm()
 * @method static \\Illuminate\\Http\\Client\\PendingRequest attach(string|array $name, string|resource $contents = \'\', string|null $filename = null, array $headers = [])
 * @method static \\Illuminate\\Http\\Client\\PendingRequest asMultipart()
 * @method static \\Illuminate\\Http\\Client\\PendingRequest bodyFormat(string $format)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withQueryParameters(array $parameters)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest contentType(string $contentType)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest acceptJson()
 * @method static \\Illuminate\\Http\\Client\\PendingRequest accept(string $contentType)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withHeaders(array $headers)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withHeader(string $name, mixed $value)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest replaceHeaders(array $headers)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withBasicAuth(string $username, string $password)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withDigestAuth(string $username, string $password)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withToken(string $token, string $type = \'Bearer\')
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withUserAgent(string|bool $userAgent)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withUrlParameters(array $parameters = [])
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withCookies(array $cookies, string $domain)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest maxRedirects(int $max)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withoutRedirecting()
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withoutVerifying()
 * @method static \\Illuminate\\Http\\Client\\PendingRequest sink(string|resource $to)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest timeout(int|float $seconds)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest connectTimeout(int|float $seconds)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest retry(array|int $times, \\Closure|int $sleepMilliseconds = 0, callable|null $when = null, bool $throw = true)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withOptions(array $options)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withMiddleware(callable $middleware)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withRequestMiddleware(callable $middleware)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest withResponseMiddleware(callable $middleware)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest beforeSending(callable $callback)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest throw(callable|null $callback = null)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest throwIf(callable|bool $condition)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest throwUnless(callable|bool $condition)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest dump()
 * @method static \\Illuminate\\Http\\Client\\PendingRequest dd()
 * @method static \\Illuminate\\Http\\Client\\Response get(string $url, array|string|null $query = null)
 * @method static \\Illuminate\\Http\\Client\\Response head(string $url, array|string|null $query = null)
 * @method static \\Illuminate\\Http\\Client\\Response post(string $url, array $data = [])
 * @method static \\Illuminate\\Http\\Client\\Response patch(string $url, array $data = [])
 * @method static \\Illuminate\\Http\\Client\\Response put(string $url, array $data = [])
 * @method static \\Illuminate\\Http\\Client\\Response delete(string $url, array $data = [])
 * @method static array pool(callable $callback)
 * @method static \\Illuminate\\Http\\Client\\Response send(string $method, string $url, array $options = [])
 * @method static \\GuzzleHttp\\Client buildClient()
 * @method static \\GuzzleHttp\\Client createClient(\\GuzzleHttp\\HandlerStack $handlerStack)
 * @method static \\GuzzleHttp\\HandlerStack buildHandlerStack()
 * @method static \\GuzzleHttp\\HandlerStack pushHandlers(\\GuzzleHttp\\HandlerStack $handlerStack)
 * @method static \\Closure buildBeforeSendingHandler()
 * @method static \\Closure buildRecorderHandler()
 * @method static \\Closure buildStubHandler()
 * @method static \\GuzzleHttp\\Psr7\\RequestInterface runBeforeSendingCallbacks(\\GuzzleHttp\\Psr7\\RequestInterface $request, array $options)
 * @method static array mergeOptions(array ...$options)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest stub(callable $callback)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest async(bool $async = true)
 * @method static \\GuzzleHttp\\Promise\\PromiseInterface|null getPromise()
 * @method static \\Illuminate\\Http\\Client\\PendingRequest setClient(\\GuzzleHttp\\Client $client)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest setHandler(callable $handler)
 * @method static array getOptions()
 * @method static \\Illuminate\\Http\\Client\\PendingRequest|mixed when(\\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 * @method static \\Illuminate\\Http\\Client\\PendingRequest|mixed unless(\\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 *
 * @see \\Illuminate\\Http\\Client\\Factory
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 98,
    'endLine' => 164,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Support\\Facades\\Facade',
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
      'getFacadeAccessor' => 
      array (
        'name' => 'getFacadeAccessor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the registered name of the component.
 *
 * @return string
 */',
        'startLine' => 105,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Http',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Http',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Http',
        'aliasName' => NULL,
      ),
      'fake' => 
      array (
        'name' => 'fake',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 116,
                'endLine' => 116,
                'startTokenPos' => 61,
                'startFilePos' => 8053,
                'endTokenPos' => 61,
                'endFilePos' => 8056,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 116,
            'endLine' => 116,
            'startColumn' => 33,
            'endColumn' => 48,
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
 * Register a stub callable that will intercept requests and be able to return stub responses.
 *
 * @param  \\Closure|array  $callback
 * @return \\Illuminate\\Http\\Client\\Factory
 */',
        'startLine' => 116,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Http',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Http',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Http',
        'aliasName' => NULL,
      ),
      'fakeSequence' => 
      array (
        'name' => 'fakeSequence',
        'parameters' => 
        array (
          'urlPattern' => 
          array (
            'name' => 'urlPattern',
            'default' => 
            array (
              'code' => '\'*\'',
              'attributes' => 
              array (
                'startLine' => 129,
                'endLine' => 129,
                'startTokenPos' => 126,
                'startFilePos' => 8451,
                'endTokenPos' => 126,
                'endFilePos' => 8453,
              ),
            ),
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
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 41,
            'endColumn' => 64,
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
 * Register a response sequence for the given URL pattern.
 *
 * @param  string  $urlPattern
 * @return \\Illuminate\\Http\\Client\\ResponseSequence
 */',
        'startLine' => 129,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Http',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Http',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Http',
        'aliasName' => NULL,
      ),
      'preventStrayRequests' => 
      array (
        'name' => 'preventStrayRequests',
        'parameters' => 
        array (
          'prevent' => 
          array (
            'name' => 'prevent',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 144,
                'endLine' => 144,
                'startTokenPos' => 190,
                'startFilePos' => 8867,
                'endTokenPos' => 190,
                'endFilePos' => 8870,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 144,
            'endLine' => 144,
            'startColumn' => 49,
            'endColumn' => 63,
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
 * Indicate that an exception should be thrown if any request is not faked.
 *
 * @param  bool  $prevent
 * @return \\Illuminate\\Http\\Client\\Factory
 */',
        'startLine' => 144,
        'endLine' => 149,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Http',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Http',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Http',
        'aliasName' => NULL,
      ),
      'stubUrl' => 
      array (
        'name' => 'stubUrl',
        'parameters' => 
        array (
          'url' => 
          array (
            'name' => 'url',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 158,
            'endLine' => 158,
            'startColumn' => 36,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 158,
            'endLine' => 158,
            'startColumn' => 42,
            'endColumn' => 50,
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
 * Stub the given URL using the given callback.
 *
 * @param  string  $url
 * @param  \\Illuminate\\Http\\Client\\Response|\\GuzzleHttp\\Promise\\PromiseInterface|callable  $callback
 * @return \\Illuminate\\Http\\Client\\Factory
 */',
        'startLine' => 158,
        'endLine' => 163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Http',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Http',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Http',
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