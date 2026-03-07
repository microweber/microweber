<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/./composer/src/Composer/Config.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Composer\Config
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-62f6e9a902c43cf29ab2c6030e4d3788f8407a27efaf544a39d3f091aa78d5cc-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Composer\\Config',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/./composer/src/Composer/Config.php',
      ),
    ),
    'namespace' => 'Composer',
    'name' => 'Composer\\Config',
    'shortName' => 'Config',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 26,
    'endLine' => 681,
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
      'SOURCE_DEFAULT' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'SOURCE_DEFAULT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'default\'',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 67,
            'startFilePos' => 654,
            'endTokenPos' => 67,
            'endFilePos' => 662,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'SOURCE_COMMAND' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'SOURCE_COMMAND',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'command\'',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 78,
            'startFilePos' => 699,
            'endTokenPos' => 78,
            'endFilePos' => 707,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'SOURCE_UNKNOWN' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'SOURCE_UNKNOWN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unknown\'',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 89,
            'startFilePos' => 744,
            'endTokenPos' => 89,
            'endFilePos' => 752,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'RELATIVE_PATHS' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'RELATIVE_PATHS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '1',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 100,
            'startFilePos' => 790,
            'endTokenPos' => 100,
            'endFilePos' => 790,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
    ),
    'immediateProperties' => 
    array (
      'defaultConfig' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'defaultConfig',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[
    \'process-timeout\' => 300,
    \'use-include-path\' => false,
    \'allow-plugins\' => [],
    \'use-parent-dir\' => \'prompt\',
    \'preferred-install\' => \'dist\',
    \'audit\' => [\'ignore\' => [], \'abandoned\' => \\Composer\\Advisory\\Auditor::ABANDONED_FAIL],
    \'notify-on-install\' => true,
    \'github-protocols\' => [\'https\', \'ssh\', \'git\'],
    \'gitlab-protocol\' => null,
    \'vendor-dir\' => \'vendor\',
    \'bin-dir\' => \'{$vendor-dir}/bin\',
    \'cache-dir\' => \'{$home}/cache\',
    \'data-dir\' => \'{$home}\',
    \'cache-files-dir\' => \'{$cache-dir}/files\',
    \'cache-repo-dir\' => \'{$cache-dir}/repo\',
    \'cache-vcs-dir\' => \'{$cache-dir}/vcs\',
    \'cache-ttl\' => 15552000,
    // 6 months
    \'cache-files-ttl\' => null,
    // fallback to cache-ttl
    \'cache-files-maxsize\' => \'300MiB\',
    \'cache-read-only\' => false,
    \'bin-compat\' => \'auto\',
    \'discard-changes\' => false,
    \'autoloader-suffix\' => null,
    \'sort-packages\' => false,
    \'optimize-autoloader\' => false,
    \'classmap-authoritative\' => false,
    \'apcu-autoloader\' => false,
    \'prepend-autoloader\' => true,
    \'update-with-minimal-changes\' => false,
    \'github-domains\' => [\'github.com\'],
    \'bitbucket-expose-hostname\' => true,
    \'disable-tls\' => false,
    \'secure-http\' => true,
    \'secure-svn-domains\' => [],
    \'cafile\' => null,
    \'capath\' => null,
    \'github-expose-hostname\' => true,
    \'gitlab-domains\' => [\'gitlab.com\'],
    \'store-auths\' => \'prompt\',
    \'platform\' => [],
    \'archive-format\' => \'tar\',
    \'archive-dir\' => \'.\',
    \'htaccess-protect\' => true,
    \'use-github-api\' => true,
    \'lock\' => true,
    \'platform-check\' => \'php-only\',
    \'bitbucket-oauth\' => [],
    \'github-oauth\' => [],
    \'gitlab-oauth\' => [],
    \'gitlab-token\' => [],
    \'http-basic\' => [],
    \'bearer\' => [],
    \'custom-headers\' => [],
    \'bump-after-update\' => false,
    \'allow-missing-requirements\' => false,
    \'client-certificate\' => [],
    \'forgejo-domains\' => [\'codeberg.org\'],
    \'forgejo-token\' => [],
]',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 94,
            'startTokenPos' => 113,
            'startFilePos' => 866,
            'endTokenPos' => 567,
            'endFilePos' => 3071,
          ),
        ),
        'docComment' => '/** @var array<string, mixed> */',
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaultRepositories' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'defaultRepositories',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'packagist.org\' => [\'type\' => \'composer\', \'url\' => \'https://repo.packagist.org\']]',
          'attributes' => 
          array (
            'startLine' => 97,
            'endLine' => 102,
            'startTokenPos' => 580,
            'startFilePos' => 3153,
            'endTokenPos' => 605,
            'endFilePos' => 3284,
          ),
        ),
        'docComment' => '/** @var array<string, mixed> */',
        'attributes' => 
        array (
        ),
        'startLine' => 97,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'config' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'config',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var array<string, mixed> */',
        'attributes' => 
        array (
        ),
        'startLine' => 105,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'baseDir' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'baseDir',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var ?non-empty-string */',
        'attributes' => 
        array (
        ),
        'startLine' => 107,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'repositories' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'repositories',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var array<int|string, mixed> */',
        'attributes' => 
        array (
        ),
        'startLine' => 109,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'configSource' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'configSource',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var ConfigSourceInterface */',
        'attributes' => 
        array (
        ),
        'startLine' => 111,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'authConfigSource' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'authConfigSource',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var ConfigSourceInterface */',
        'attributes' => 
        array (
        ),
        'startLine' => 113,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'localAuthConfigSource' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'localAuthConfigSource',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 115,
            'endLine' => 115,
            'startTokenPos' => 651,
            'startFilePos' => 3684,
            'endTokenPos' => 651,
            'endFilePos' => 3687,
          ),
        ),
        'docComment' => '/** @var ConfigSourceInterface|null */',
        'attributes' => 
        array (
        ),
        'startLine' => 115,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'useEnvironment' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'useEnvironment',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var bool */',
        'attributes' => 
        array (
        ),
        'startLine' => 117,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'warnedHosts' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'warnedHosts',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 119,
            'endLine' => 119,
            'startTokenPos' => 669,
            'startFilePos' => 3803,
            'endTokenPos' => 670,
            'endFilePos' => 3804,
          ),
        ),
        'docComment' => '/** @var array<string, true> */',
        'attributes' => 
        array (
        ),
        'startLine' => 119,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'sslVerifyWarnedHosts' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'sslVerifyWarnedHosts',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 121,
            'endLine' => 121,
            'startTokenPos' => 681,
            'startFilePos' => 3879,
            'endTokenPos' => 682,
            'endFilePos' => 3880,
          ),
        ),
        'docComment' => '/** @var array<string, true> */',
        'attributes' => 
        array (
        ),
        'startLine' => 121,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'sourceOfConfigValue' => 
      array (
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'name' => 'sourceOfConfigValue',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 123,
            'endLine' => 123,
            'startTokenPos' => 693,
            'startFilePos' => 3956,
            'endTokenPos' => 694,
            'endFilePos' => 3957,
          ),
        ),
        'docComment' => '/** @var array<string, string> */',
        'attributes' => 
        array (
        ),
        'startLine' => 123,
        'endLine' => 123,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'useEnvironment' => 
          array (
            'name' => 'useEnvironment',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 129,
                'endLine' => 129,
                'startTokenPos' => 711,
                'startFilePos' => 4209,
                'endTokenPos' => 711,
                'endFilePos' => 4212,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
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
            'startColumn' => 33,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'baseDir' => 
          array (
            'name' => 'baseDir',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 129,
                'endLine' => 129,
                'startTokenPos' => 721,
                'startFilePos' => 4234,
                'endTokenPos' => 721,
                'endFilePos' => 4237,
              ),
            ),
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 62,
            'endColumn' => 84,
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
 * @param bool    $useEnvironment Use COMPOSER_ environment variables to replace config settings
 * @param ?string $baseDir        Optional base directory of the config
 */',
        'startLine' => 129,
        'endLine' => 145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'setBaseDir' => 
      array (
        'name' => 'setBaseDir',
        'parameters' => 
        array (
          'baseDir' => 
          array (
            'name' => 'baseDir',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 154,
            'endLine' => 154,
            'startColumn' => 32,
            'endColumn' => 47,
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
 * Changing this can break path resolution for relative config paths so do not call this without knowing what you are doing
 *
 * The $baseDir should be an absolute path and without trailing slash
 *
 * @param non-empty-string|null $baseDir
 */',
        'startLine' => 154,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'setConfigSource' => 
      array (
        'name' => 'setConfigSource',
        'parameters' => 
        array (
          'source' => 
          array (
            'name' => 'source',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Composer\\Config\\ConfigSourceInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 159,
            'endLine' => 159,
            'startColumn' => 37,
            'endColumn' => 65,
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
        'docComment' => NULL,
        'startLine' => 159,
        'endLine' => 162,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'getConfigSource' => 
      array (
        'name' => 'getConfigSource',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Composer\\Config\\ConfigSourceInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 164,
        'endLine' => 167,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'setAuthConfigSource' => 
      array (
        'name' => 'setAuthConfigSource',
        'parameters' => 
        array (
          'source' => 
          array (
            'name' => 'source',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Composer\\Config\\ConfigSourceInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 169,
            'endLine' => 169,
            'startColumn' => 41,
            'endColumn' => 69,
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
        'docComment' => NULL,
        'startLine' => 169,
        'endLine' => 172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'getAuthConfigSource' => 
      array (
        'name' => 'getAuthConfigSource',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Composer\\Config\\ConfigSourceInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 174,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'setLocalAuthConfigSource' => 
      array (
        'name' => 'setLocalAuthConfigSource',
        'parameters' => 
        array (
          'source' => 
          array (
            'name' => 'source',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Composer\\Config\\ConfigSourceInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 179,
            'endLine' => 179,
            'startColumn' => 46,
            'endColumn' => 74,
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
        'docComment' => NULL,
        'startLine' => 179,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'getLocalAuthConfigSource' => 
      array (
        'name' => 'getLocalAuthConfigSource',
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
                  'name' => 'Composer\\Config\\ConfigSourceInterface',
                  'isIdentifier' => false,
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
        'docComment' => NULL,
        'startLine' => 184,
        'endLine' => 187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'merge' => 
      array (
        'name' => 'merge',
        'parameters' => 
        array (
          'config' => 
          array (
            'name' => 'config',
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
            'startLine' => 194,
            'endLine' => 194,
            'startColumn' => 27,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'source' => 
          array (
            'name' => 'source',
            'default' => 
            array (
              'code' => 'self::SOURCE_UNKNOWN',
              'attributes' => 
              array (
                'startLine' => 194,
                'endLine' => 194,
                'startTokenPos' => 1062,
                'startFilePos' => 6250,
                'endTokenPos' => 1064,
                'endFilePos' => 6269,
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
            'startLine' => 194,
            'endLine' => 194,
            'startColumn' => 42,
            'endColumn' => 78,
            'parameterIndex' => 1,
            'isOptional' => true,
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
 * Merges new config values with the existing ones (overriding)
 *
 * @param array{config?: array<string, mixed>, repositories?: array<mixed>} $config
 */',
        'startLine' => 194,
        'endLine' => 285,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'getRepositories' => 
      array (
        'name' => 'getRepositories',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array<int|string, mixed>
 */',
        'startLine' => 290,
        'endLine' => 293,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
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
            'startLine' => 303,
            'endLine' => 303,
            'startColumn' => 25,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'flags' => 
          array (
            'name' => 'flags',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 303,
                'endLine' => 303,
                'startTokenPos' => 2252,
                'startFilePos' => 12217,
                'endTokenPos' => 2252,
                'endFilePos' => 12217,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 303,
            'endLine' => 303,
            'startColumn' => 38,
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
 * Returns a setting
 *
 * @param  int               $flags Options (see class constants)
 * @throws \\RuntimeException
 *
 * @return mixed
 */',
        'startLine' => 303,
        'endLine' => 494,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'all' => 
      array (
        'name' => 'all',
        'parameters' => 
        array (
          'flags' => 
          array (
            'name' => 'flags',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 499,
                'endLine' => 499,
                'startTokenPos' => 3693,
                'startFilePos' => 20205,
                'endTokenPos' => 3693,
                'endFilePos' => 20205,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 499,
            'endLine' => 499,
            'startColumn' => 25,
            'endColumn' => 38,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array<string, mixed[]>
 */',
        'startLine' => 499,
        'endLine' => 509,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'getSourceOfValue' => 
      array (
        'name' => 'getSourceOfValue',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
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
            'startLine' => 511,
            'endLine' => 511,
            'startColumn' => 38,
            'endColumn' => 48,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 511,
        'endLine' => 516,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'setSourceOfConfigValue' => 
      array (
        'name' => 'setSourceOfConfigValue',
        'parameters' => 
        array (
          'configValue' => 
          array (
            'name' => 'configValue',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 521,
            'endLine' => 521,
            'startColumn' => 45,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
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
            'startLine' => 521,
            'endLine' => 521,
            'startColumn' => 59,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'source' => 
          array (
            'name' => 'source',
            'default' => NULL,
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
            'startLine' => 521,
            'endLine' => 521,
            'startColumn' => 73,
            'endColumn' => 86,
            'parameterIndex' => 2,
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
 * @param mixed  $configValue
 */',
        'startLine' => 521,
        'endLine' => 530,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'raw' => 
      array (
        'name' => 'raw',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array<string, mixed[]>
 */',
        'startLine' => 535,
        'endLine' => 541,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'has' => 
      array (
        'name' => 'has',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
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
            'startLine' => 546,
            'endLine' => 546,
            'startColumn' => 25,
            'endColumn' => 35,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Checks whether a setting exists
 */',
        'startLine' => 546,
        'endLine' => 549,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'process' => 
      array (
        'name' => 'process',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 559,
            'endLine' => 559,
            'startColumn' => 30,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'flags' => 
          array (
            'name' => 'flags',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 559,
            'endLine' => 559,
            'startColumn' => 38,
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
 * Replaces {$refs} inside a config string
 *
 * @param  string|mixed $value a config string that can contain {$refs-to-other-config}
 * @param  int          $flags Options (see class constants)
 *
 * @return string|mixed
 */',
        'startLine' => 559,
        'endLine' => 568,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'realpath' => 
      array (
        'name' => 'realpath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
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
            'startLine' => 575,
            'endLine' => 575,
            'startColumn' => 31,
            'endColumn' => 42,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Turns relative paths in absolute paths without realpath()
 *
 * Since the dirs might not exist yet we can not call realpath or it will fail.
 */',
        'startLine' => 575,
        'endLine' => 582,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'getComposerEnv' => 
      array (
        'name' => 'getComposerEnv',
        'parameters' => 
        array (
          'var' => 
          array (
            'name' => 'var',
            'default' => NULL,
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
            'startLine' => 594,
            'endLine' => 594,
            'startColumn' => 37,
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
 * Reads the value of a Composer environment variable
 *
 * This should be used to read COMPOSER_ environment variables
 * that overload config values.
 *
 * @param non-empty-string $var
 *
 * @return string|false
 */',
        'startLine' => 594,
        'endLine' => 601,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'disableRepoByName' => 
      array (
        'name' => 'disableRepoByName',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
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
            'startLine' => 603,
            'endLine' => 603,
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
        'docComment' => NULL,
        'startLine' => 603,
        'endLine' => 610,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'prohibitUrlByConfig' => 
      array (
        'name' => 'prohibitUrlByConfig',
        'parameters' => 
        array (
          'url' => 
          array (
            'name' => 'url',
            'default' => NULL,
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
            'startLine' => 617,
            'endLine' => 617,
            'startColumn' => 41,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'io' => 
          array (
            'name' => 'io',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 617,
                'endLine' => 617,
                'startTokenPos' => 4275,
                'startFilePos' => 23414,
                'endTokenPos' => 4275,
                'endFilePos' => 23417,
              ),
            ),
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
                      'name' => 'Composer\\IO\\IOInterface',
                      'isIdentifier' => false,
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 617,
            'endLine' => 617,
            'startColumn' => 54,
            'endColumn' => 76,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'repoOptions' => 
          array (
            'name' => 'repoOptions',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 617,
                'endLine' => 617,
                'startTokenPos' => 4284,
                'startFilePos' => 23441,
                'endTokenPos' => 4285,
                'endFilePos' => 23442,
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
            'startLine' => 617,
            'endLine' => 617,
            'startColumn' => 79,
            'endColumn' => 101,
            'parameterIndex' => 2,
            'isOptional' => true,
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
 * Validates that the passed URL is allowed to be used by current config, or throws an exception.
 *
 * @param mixed[]     $repoOptions
 */',
        'startLine' => 617,
        'endLine' => 664,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
        'aliasName' => NULL,
      ),
      'disableProcessTimeout' => 
      array (
        'name' => 'disableProcessTimeout',
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
        'docComment' => '/**
 * Used by long-running custom scripts in composer.json
 *
 * "scripts": {
 *   "watch": [
 *     "Composer\\\\Config::disableProcessTimeout",
 *     "vendor/bin/long-running-script --watch"
 *   ]
 * }
 */',
        'startLine' => 676,
        'endLine' => 680,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\Config',
        'implementingClassName' => 'Composer\\Config',
        'currentClassName' => 'Composer\\Config',
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