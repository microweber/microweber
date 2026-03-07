<?php declare(strict_types = 1);

// ftm-/home/headless/Documents/GitHub/microweber/vendor/filament/filament/src/Auth/Pages/Register.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v4-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      '760e9bd45d2307c79d455ab29fe557ad' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'bd8691bd497a40f57ddf2d09276611ad' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Pages\\Concerns',
         'uses' => 
        array (
          'closure' => 'Closure',
          'filament' => 'Filament\\Facades\\Filament',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
         'traitData' => 
        array (
          0 => '/home/headless/Documents/GitHub/microweber/vendor/filament/filament/src/Auth/Pages/Register.php',
          1 => 'Filament\\Auth\\Pages\\Register',
          2 => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '1b02d8ddab5ce1f96fdffa7a9163fdb9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Pages\\Concerns',
         'uses' => 
        array (
          'closure' => 'Closure',
          'filament' => 'Filament\\Facades\\Filament',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'hasDatabaseTransactions',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
         'traitData' => 
        array (
          0 => '/home/headless/Documents/GitHub/microweber/vendor/filament/filament/src/Auth/Pages/Register.php',
          1 => 'Filament\\Auth\\Pages\\Register',
          2 => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '4d107564b814e4242e4688a6cc6738c5' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Pages\\Concerns',
         'uses' => 
        array (
          'closure' => 'Closure',
          'filament' => 'Filament\\Facades\\Filament',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'beginDatabaseTransaction',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
         'traitData' => 
        array (
          0 => '/home/headless/Documents/GitHub/microweber/vendor/filament/filament/src/Auth/Pages/Register.php',
          1 => 'Filament\\Auth\\Pages\\Register',
          2 => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'a6ff73e7f317831eead470ab8d884834' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Pages\\Concerns',
         'uses' => 
        array (
          'closure' => 'Closure',
          'filament' => 'Filament\\Facades\\Filament',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'commitDatabaseTransaction',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
         'traitData' => 
        array (
          0 => '/home/headless/Documents/GitHub/microweber/vendor/filament/filament/src/Auth/Pages/Register.php',
          1 => 'Filament\\Auth\\Pages\\Register',
          2 => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'f5c726ed925d18c73eaadf32b8ebd694' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Pages\\Concerns',
         'uses' => 
        array (
          'closure' => 'Closure',
          'filament' => 'Filament\\Facades\\Filament',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'rollBackDatabaseTransaction',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
         'traitData' => 
        array (
          0 => '/home/headless/Documents/GitHub/microweber/vendor/filament/filament/src/Auth/Pages/Register.php',
          1 => 'Filament\\Auth\\Pages\\Register',
          2 => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '894aac82463ea50fee7a7301fad5964d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Pages\\Concerns',
         'uses' => 
        array (
          'closure' => 'Closure',
          'filament' => 'Filament\\Facades\\Filament',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'wrapInDatabaseTransaction',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
         'traitData' => 
        array (
          0 => '/home/headless/Documents/GitHub/microweber/vendor/filament/filament/src/Auth/Pages/Register.php',
          1 => 'Filament\\Auth\\Pages\\Register',
          2 => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '6c1e54df371c329e894b5edd519c864a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'DanHarrin\\LivewireRateLimiting',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
         'traitData' => 
        array (
          0 => '/home/headless/Documents/GitHub/microweber/vendor/filament/filament/src/Auth/Pages/Register.php',
          1 => 'Filament\\Auth\\Pages\\Register',
          2 => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'b32a138ee742c592e44a4f29820ba894' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'DanHarrin\\LivewireRateLimiting',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'clearRateLimiter',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
         'traitData' => 
        array (
          0 => '/home/headless/Documents/GitHub/microweber/vendor/filament/filament/src/Auth/Pages/Register.php',
          1 => 'Filament\\Auth\\Pages\\Register',
          2 => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'cc10beeb90b552de6044e9513316496b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'DanHarrin\\LivewireRateLimiting',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'getRateLimitKey',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
         'traitData' => 
        array (
          0 => '/home/headless/Documents/GitHub/microweber/vendor/filament/filament/src/Auth/Pages/Register.php',
          1 => 'Filament\\Auth\\Pages\\Register',
          2 => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '63567a886f7b12b2d675554bf3da018a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'DanHarrin\\LivewireRateLimiting',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'hitRateLimiter',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
         'traitData' => 
        array (
          0 => '/home/headless/Documents/GitHub/microweber/vendor/filament/filament/src/Auth/Pages/Register.php',
          1 => 'Filament\\Auth\\Pages\\Register',
          2 => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '7904d1620e40825513cdf617e1d8de66' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'DanHarrin\\LivewireRateLimiting',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'rateLimit',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
         'traitData' => 
        array (
          0 => '/home/headless/Documents/GitHub/microweber/vendor/filament/filament/src/Auth/Pages/Register.php',
          1 => 'Filament\\Auth\\Pages\\Register',
          2 => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'a42459a2c1b04bf98859330b59a0b8a4' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'mount',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'e3058be816b7c08b05316fcbbedd775e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'register',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '16c24d80f7a9d23abd3075b70603bff7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'getRateLimitedNotification',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '1e292e500790078388ea9f84b2933788' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'isRegisterRateLimited',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '5ae96675c0658bb32a4c7a23975d138f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'handleRegistration',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '846005bc99aac8bd88700c7750a85fe2' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'sendEmailVerificationNotification',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '10bd6c8dc337b483d2f5ea51b5bb91c4' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'defaultForm',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '191149900eb6cc87870ee86a5d2f49e8' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'form',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '466679489e805a9872dcdbcce17e16f0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'getNameFormComponent',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '4813ce95c012afd34d4a147467ae8007' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'getEmailFormComponent',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'c96bf3c646325739a5f596407b7a208c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'getPasswordFormComponent',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '3b04ef0ae909ae9b8acc5d4c0c2a1226' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'getPasswordConfirmationFormComponent',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '585f27046042a1fef4895902e48e8442' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'loginAction',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '154da0d99f183307dade968f63ae6f8c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'getUserModel',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '96a2f7854c3e9596a082eb4958a8594e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'getTitle',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'b4100c0bb7e2c943bd48625e8222b5b1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'getHeading',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'e670d23622ed39dd5c33fc4a5538c8ba' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'getFormActions',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '4776db8f6afaeeadd132b5b1b1f97980' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'getRegisterFormAction',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '4a1c0e5c5e7fb81ebcf840c7ca9044d8' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'hasFullWidthFormActions',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '9b44fc8d20842dd42273d266de67b41e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'mutateFormDataBeforeRegister',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '48b772d0d41dcee52c696188d0efc4fc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'getSubheading',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '844ed270659cf89694c42c8c388093ec' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'content',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'ef5ae47ae7d54efca9693fd99d546d8b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Filament\\Auth\\Pages',
         'uses' => 
        array (
          'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
          'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
          'action' => 'Filament\\Actions\\Action',
          'actiongroup' => 'Filament\\Actions\\ActionGroup',
          'registered' => 'Filament\\Auth\\Events\\Registered',
          'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
          'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
          'filament' => 'Filament\\Facades\\Filament',
          'textinput' => 'Filament\\Forms\\Components\\TextInput',
          'notification' => 'Filament\\Notifications\\Notification',
          'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
          'simplepage' => 'Filament\\Pages\\SimplePage',
          'actions' => 'Filament\\Schemas\\Components\\Actions',
          'component' => 'Filament\\Schemas\\Components\\Component',
          'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
          'form' => 'Filament\\Schemas\\Components\\Form',
          'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
          'schema' => 'Filament\\Schemas\\Schema',
          'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
          'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
          'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
          'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
          'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
          'htmlstring' => 'Illuminate\\Support\\HtmlString',
          'password' => 'Illuminate\\Validation\\Rules\\Password',
          'logicexception' => 'LogicException',
        ),
         'className' => 'Filament\\Auth\\Pages\\Register',
         'functionName' => 'getFormContentComponent',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Filament\\Auth\\Pages',
           'uses' => 
          array (
            'toomanyrequestsexception' => 'DanHarrin\\LivewireRateLimiting\\Exceptions\\TooManyRequestsException',
            'withratelimiting' => 'DanHarrin\\LivewireRateLimiting\\WithRateLimiting',
            'action' => 'Filament\\Actions\\Action',
            'actiongroup' => 'Filament\\Actions\\ActionGroup',
            'registered' => 'Filament\\Auth\\Events\\Registered',
            'registrationresponse' => 'Filament\\Auth\\Http\\Responses\\Contracts\\RegistrationResponse',
            'verifyemail' => 'Filament\\Auth\\Notifications\\VerifyEmail',
            'filament' => 'Filament\\Facades\\Filament',
            'textinput' => 'Filament\\Forms\\Components\\TextInput',
            'notification' => 'Filament\\Notifications\\Notification',
            'canusedatabasetransactions' => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
            'simplepage' => 'Filament\\Pages\\SimplePage',
            'actions' => 'Filament\\Schemas\\Components\\Actions',
            'component' => 'Filament\\Schemas\\Components\\Component',
            'embeddedschema' => 'Filament\\Schemas\\Components\\EmbeddedSchema',
            'form' => 'Filament\\Schemas\\Components\\Form',
            'renderhook' => 'Filament\\Schemas\\Components\\RenderHook',
            'schema' => 'Filament\\Schemas\\Schema',
            'panelsrenderhook' => 'Filament\\View\\PanelsRenderHook',
            'eloquentuserprovider' => 'Illuminate\\Auth\\EloquentUserProvider',
            'sessionguard' => 'Illuminate\\Auth\\SessionGuard',
            'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
            'htmlable' => 'Illuminate\\Contracts\\Support\\Htmlable',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'hash' => 'Illuminate\\Support\\Facades\\Hash',
            'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
            'htmlstring' => 'Illuminate\\Support\\HtmlString',
            'password' => 'Illuminate\\Validation\\Rules\\Password',
            'logicexception' => 'LogicException',
          ),
           'className' => 'Filament\\Auth\\Pages\\Register',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
    ),
    1 => 
    array (
      '/home/headless/Documents/GitHub/microweber/vendor/filament/filament/src/Auth/Pages/Register.php' => 'b926d97251e8023592951f68c4f79c98942bca50a7826b4fadba4c8a4e0c7207',
      '/home/headless/Documents/GitHub/microweber/vendor/composer/../filament/filament/src/Pages/Concerns/CanUseDatabaseTransactions.php' => '5c27fb508b106a7faaee445dc3a2abeddbd328e9931c5dff43b5ed2643c53b8c',
      '/home/headless/Documents/GitHub/microweber/vendor/composer/../danharrin/livewire-rate-limiting/src/WithRateLimiting.php' => '308fdf711d525ee7864bad9461307a5004ae6c343419070e68d3eecd591bb494',
    ),
  ),
));