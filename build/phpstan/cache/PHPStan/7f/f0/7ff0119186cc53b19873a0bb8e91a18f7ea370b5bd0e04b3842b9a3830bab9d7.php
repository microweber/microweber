<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Mail.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Support\Facades\Mail
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-529ee57000d9bcbfdb9a2ab7ec15ebadbcb80f0aafe8756c74700091b9f81b5e-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Support\\Facades\\Mail',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Mail.php',
      ),
    ),
    'namespace' => 'Illuminate\\Support\\Facades',
    'name' => 'Illuminate\\Support\\Facades\\Mail',
    'shortName' => 'Mail',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method static \\Illuminate\\Contracts\\Mail\\Mailer mailer(string|null $name = null)
 * @method static \\Illuminate\\Mail\\Mailer driver(string|null $driver = null)
 * @method static \\Illuminate\\Mail\\Mailer build(array $config)
 * @method static \\Symfony\\Component\\Mailer\\Transport\\TransportInterface createSymfonyTransport(array $config)
 * @method static string getDefaultDriver()
 * @method static void setDefaultDriver(string $name)
 * @method static void purge(string|null $name = null)
 * @method static \\Illuminate\\Mail\\MailManager extend(string $driver, \\Closure $callback)
 * @method static \\Illuminate\\Contracts\\Foundation\\Application getApplication()
 * @method static \\Illuminate\\Mail\\MailManager setApplication(\\Illuminate\\Contracts\\Foundation\\Application $app)
 * @method static \\Illuminate\\Mail\\MailManager forgetMailers()
 * @method static void alwaysFrom(string $address, string|null $name = null)
 * @method static void alwaysReplyTo(string $address, string|null $name = null)
 * @method static void alwaysReturnPath(string $address)
 * @method static void alwaysTo(string $address, string|null $name = null)
 * @method static \\Illuminate\\Mail\\PendingMail to(mixed $users, string|null $name = null)
 * @method static \\Illuminate\\Mail\\PendingMail cc(mixed $users, string|null $name = null)
 * @method static \\Illuminate\\Mail\\PendingMail bcc(mixed $users, string|null $name = null)
 * @method static \\Illuminate\\Mail\\SentMessage|null html(string $html, mixed $callback)
 * @method static \\Illuminate\\Mail\\SentMessage|null raw(string $text, mixed $callback)
 * @method static \\Illuminate\\Mail\\SentMessage|null plain(string $view, array $data, mixed $callback)
 * @method static string render(string|array $view, array $data = [])
 * @method static \\Illuminate\\Mail\\SentMessage|null send(\\Illuminate\\Contracts\\Mail\\Mailable|string|array $view, array $data = [], \\Closure|string|null $callback = null)
 * @method static \\Illuminate\\Mail\\SentMessage|null sendNow(\\Illuminate\\Contracts\\Mail\\Mailable|string|array $mailable, array $data = [], \\Closure|string|null $callback = null)
 * @method static mixed queue(\\Illuminate\\Contracts\\Mail\\Mailable|string|array $view, \\BackedEnum|string|null $queue = null)
 * @method static mixed onQueue(\\BackedEnum|string|null $queue, \\Illuminate\\Contracts\\Mail\\Mailable $view)
 * @method static mixed queueOn(string $queue, \\Illuminate\\Contracts\\Mail\\Mailable $view)
 * @method static mixed later(\\DateTimeInterface|\\DateInterval|int $delay, \\Illuminate\\Contracts\\Mail\\Mailable $view, string|null $queue = null)
 * @method static mixed laterOn(string $queue, \\DateTimeInterface|\\DateInterval|int $delay, \\Illuminate\\Contracts\\Mail\\Mailable $view)
 * @method static \\Symfony\\Component\\Mailer\\Transport\\TransportInterface getSymfonyTransport()
 * @method static \\Illuminate\\Contracts\\View\\Factory getViewFactory()
 * @method static void setSymfonyTransport(\\Symfony\\Component\\Mailer\\Transport\\TransportInterface $transport)
 * @method static \\Illuminate\\Mail\\Mailer setQueue(\\Illuminate\\Contracts\\Queue\\Factory $queue)
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 * @method static void assertSent(string|\\Closure $mailable, callable|array|string|int|null $callback = null)
 * @method static void assertNotOutgoing(string|\\Closure $mailable, callable|null $callback = null)
 * @method static void assertNotSent(string|\\Closure $mailable, callable|array|string|null $callback = null)
 * @method static void assertNothingOutgoing()
 * @method static void assertNothingSent()
 * @method static void assertQueued(string|\\Closure $mailable, callable|array|string|int|null $callback = null)
 * @method static void assertNotQueued(string|\\Closure $mailable, callable|array|string|null $callback = null)
 * @method static void assertNothingQueued()
 * @method static void assertSentCount(int $count)
 * @method static void assertQueuedCount(int $count)
 * @method static void assertOutgoingCount(int $count)
 * @method static \\Illuminate\\Support\\Collection sent(string|\\Closure $mailable, callable|null $callback = null)
 * @method static bool hasSent(string $mailable)
 * @method static \\Illuminate\\Support\\Collection queued(string|\\Closure $mailable, callable|null $callback = null)
 * @method static bool hasQueued(string $mailable)
 *
 * @see \\Illuminate\\Mail\\MailManager
 * @see \\Illuminate\\Support\\Testing\\Fakes\\MailFake
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 64,
    'endLine' => 91,
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
      'fake' => 
      array (
        'name' => 'fake',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Replace the bound instance with a fake.
 *
 * @return \\Illuminate\\Support\\Testing\\Fakes\\MailFake
 */',
        'startLine' => 71,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Mail',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Mail',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Mail',
        'aliasName' => NULL,
      ),
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
        'startLine' => 87,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Mail',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Mail',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Mail',
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