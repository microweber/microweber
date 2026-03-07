<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../jenssegers/agent/src/Agent.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Jenssegers\Agent\Agent
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-2fe652b47278039c22fba93c93476f873faaf4059af4baad5ea9614b157679f5-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Jenssegers\\Agent\\Agent',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../jenssegers/agent/src/Agent.php',
      ),
    ),
    'namespace' => 'Jenssegers\\Agent',
    'name' => 'Jenssegers\\Agent\\Agent',
    'shortName' => 'Agent',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 415,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Mobile_Detect',
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
      'desktopDevices' => 
      array (
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'name' => 'desktopDevices',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'Macintosh\' => \'Macintosh\']',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 17,
            'startTokenPos' => 42,
            'startFilePos' => 268,
            'endTokenPos' => 51,
            'endFilePos' => 310,
          ),
        ),
        'docComment' => '/**
 * List of desktop devices.
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'additionalOperatingSystems' => 
      array (
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'name' => 'additionalOperatingSystems',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'Windows\' => \'Windows\', \'Windows NT\' => \'Windows NT\', \'OS X\' => \'Mac OS X\', \'Debian\' => \'Debian\', \'Ubuntu\' => \'Ubuntu\', \'Macintosh\' => \'PPC\', \'OpenBSD\' => \'OpenBSD\', \'Linux\' => \'Linux\', \'ChromeOS\' => \'CrOS\']',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 33,
            'startTokenPos' => 64,
            'startFilePos' => 444,
            'endTokenPos' => 129,
            'endFilePos' => 730,
          ),
        ),
        'docComment' => '/**
 * List of additional operating systems.
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'additionalBrowsers' => 
      array (
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'name' => 'additionalBrowsers',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'Opera Mini\' => \'Opera Mini\', \'Opera\' => \'Opera|OPR\', \'Edge\' => \'Edge|Edg\', \'Coc Coc\' => \'coc_coc_browser\', \'UCBrowser\' => \'UCBrowser\', \'Vivaldi\' => \'Vivaldi\', \'Chrome\' => \'Chrome\', \'Firefox\' => \'Firefox\', \'Safari\' => \'Safari\', \'IE\' => \'MSIE|IEMobile|MSIEMobile|Trident/[.0-9]+\', \'Netscape\' => \'Netscape\', \'Mozilla\' => \'Mozilla\', \'WeChat\' => \'MicroMessenger\']',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 53,
            'startTokenPos' => 142,
            'startFilePos' => 847,
            'endTokenPos' => 235,
            'endFilePos' => 1318,
          ),
        ),
        'docComment' => '/**
 * List of additional browsers.
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'additionalProperties' => 
      array (
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'name' => 'additionalProperties',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[
    // Operating systems
    \'Windows\' => \'Windows NT [VER]\',
    \'Windows NT\' => \'Windows NT [VER]\',
    \'OS X\' => \'OS X [VER]\',
    \'BlackBerryOS\' => [\'BlackBerry[\\w]+/[VER]\', \'BlackBerry.*Version/[VER]\', \'Version/[VER]\'],
    \'AndroidOS\' => \'Android [VER]\',
    \'ChromeOS\' => \'CrOS x86_64 [VER]\',
    // Browsers
    \'Opera Mini\' => \'Opera Mini/[VER]\',
    \'Opera\' => [\' OPR/[VER]\', \'Opera Mini/[VER]\', \'Version/[VER]\', \'Opera [VER]\'],
    \'Netscape\' => \'Netscape/[VER]\',
    \'Mozilla\' => \'rv:[VER]\',
    \'IE\' => [\'IEMobile/[VER];\', \'IEMobile [VER]\', \'MSIE [VER];\', \'rv:[VER]\'],
    \'Edge\' => [\'Edge/[VER]\', \'Edg/[VER]\'],
    \'Vivaldi\' => \'Vivaldi/[VER]\',
    \'Coc Coc\' => \'coc_coc_browser/[VER]\',
]',
          'attributes' => 
          array (
            'startLine' => 59,
            'endLine' => 77,
            'startTokenPos' => 248,
            'startFilePos' => 1439,
            'endTokenPos' => 387,
            'endFilePos' => 2211,
          ),
        ),
        'docComment' => '/**
 * List of additional properties.
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'crawlerDetect' => 
      array (
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'name' => 'crawlerDetect',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var CrawlerDetect
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 82,
        'endLine' => 82,
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
      'getDetectionRulesExtended' => 
      array (
        'name' => 'getDetectionRulesExtended',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all detection rules. These rules include the additional
 * platforms and browsers and utilities.
 * @return array
 */',
        'startLine' => 89,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'getRules' => 
      array (
        'name' => 'getRules',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 109,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'getCrawlerDetect' => 
      array (
        'name' => 'getCrawlerDetect',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return CrawlerDetect
 */',
        'startLine' => 121,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'getBrowsers' => 
      array (
        'name' => 'getBrowsers',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 130,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'getOperatingSystems' => 
      array (
        'name' => 'getOperatingSystems',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 138,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'getPlatforms' => 
      array (
        'name' => 'getPlatforms',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 146,
        'endLine' => 152,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'getDesktopDevices' => 
      array (
        'name' => 'getDesktopDevices',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 154,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'getProperties' => 
      array (
        'name' => 'getProperties',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 159,
        'endLine' => 165,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'languages' => 
      array (
        'name' => 'languages',
        'parameters' => 
        array (
          'acceptLanguage' => 
          array (
            'name' => 'acceptLanguage',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 172,
                'endLine' => 172,
                'startTokenPos' => 756,
                'startFilePos' => 4468,
                'endTokenPos' => 756,
                'endFilePos' => 4471,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 172,
            'endLine' => 172,
            'startColumn' => 31,
            'endColumn' => 52,
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
 * Get accept languages.
 * @param string $acceptLanguage
 * @return array
 */',
        'startLine' => 172,
        'endLine' => 197,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'findDetectionRulesAgainstUA' => 
      array (
        'name' => 'findDetectionRulesAgainstUA',
        'parameters' => 
        array (
          'rules' => 
          array (
            'name' => 'rules',
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
            'startLine' => 205,
            'endLine' => 205,
            'startColumn' => 52,
            'endColumn' => 63,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'userAgent' => 
          array (
            'name' => 'userAgent',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 205,
                'endLine' => 205,
                'startTokenPos' => 941,
                'startFilePos' => 5400,
                'endTokenPos' => 941,
                'endFilePos' => 5403,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 205,
            'endLine' => 205,
            'startColumn' => 66,
            'endColumn' => 82,
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
 * Match a detection rule and return the matched key.
 * @param  array $rules
 * @param  string|null $userAgent
 * @return string|bool
 */',
        'startLine' => 205,
        'endLine' => 220,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'browser' => 
      array (
        'name' => 'browser',
        'parameters' => 
        array (
          'userAgent' => 
          array (
            'name' => 'userAgent',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 227,
                'endLine' => 227,
                'startTokenPos' => 1036,
                'startFilePos' => 5902,
                'endTokenPos' => 1036,
                'endFilePos' => 5905,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 227,
            'endLine' => 227,
            'startColumn' => 29,
            'endColumn' => 45,
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
 * Get the browser name.
 * @param  string|null $userAgent
 * @return string|bool
 */',
        'startLine' => 227,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'platform' => 
      array (
        'name' => 'platform',
        'parameters' => 
        array (
          'userAgent' => 
          array (
            'name' => 'userAgent',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 237,
                'endLine' => 237,
                'startTokenPos' => 1072,
                'startFilePos' => 6160,
                'endTokenPos' => 1072,
                'endFilePos' => 6163,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 237,
            'endLine' => 237,
            'startColumn' => 30,
            'endColumn' => 46,
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
 * Get the platform name.
 * @param  string|null $userAgent
 * @return string|bool
 */',
        'startLine' => 237,
        'endLine' => 240,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'device' => 
      array (
        'name' => 'device',
        'parameters' => 
        array (
          'userAgent' => 
          array (
            'name' => 'userAgent',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 247,
                'endLine' => 247,
                'startTokenPos' => 1108,
                'startFilePos' => 6415,
                'endTokenPos' => 1108,
                'endFilePos' => 6418,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 247,
            'endLine' => 247,
            'startColumn' => 28,
            'endColumn' => 44,
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
 * Get the device name.
 * @param  string|null $userAgent
 * @return string|bool
 */',
        'startLine' => 247,
        'endLine' => 257,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'isDesktop' => 
      array (
        'name' => 'isDesktop',
        'parameters' => 
        array (
          'userAgent' => 
          array (
            'name' => 'userAgent',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 265,
                'endLine' => 265,
                'startTokenPos' => 1179,
                'startFilePos' => 6932,
                'endTokenPos' => 1179,
                'endFilePos' => 6935,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 265,
            'endLine' => 265,
            'startColumn' => 31,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'httpHeaders' => 
          array (
            'name' => 'httpHeaders',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 265,
                'endLine' => 265,
                'startTokenPos' => 1186,
                'startFilePos' => 6953,
                'endTokenPos' => 1186,
                'endFilePos' => 6956,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 265,
            'endLine' => 265,
            'startColumn' => 50,
            'endColumn' => 68,
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
 * Check if the device is a desktop computer.
 * @param  string|null $userAgent deprecated
 * @param  array $httpHeaders deprecated
 * @return bool
 */',
        'startLine' => 265,
        'endLine' => 276,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'isPhone' => 
      array (
        'name' => 'isPhone',
        'parameters' => 
        array (
          'userAgent' => 
          array (
            'name' => 'userAgent',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 284,
                'endLine' => 284,
                'startTokenPos' => 1300,
                'startFilePos' => 7720,
                'endTokenPos' => 1300,
                'endFilePos' => 7723,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 284,
            'endLine' => 284,
            'startColumn' => 29,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'httpHeaders' => 
          array (
            'name' => 'httpHeaders',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 284,
                'endLine' => 284,
                'startTokenPos' => 1307,
                'startFilePos' => 7741,
                'endTokenPos' => 1307,
                'endFilePos' => 7744,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 284,
            'endLine' => 284,
            'startColumn' => 48,
            'endColumn' => 66,
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
 * Check if the device is a mobile phone.
 * @param  string|null $userAgent deprecated
 * @param  array $httpHeaders deprecated
 * @return bool
 */',
        'startLine' => 284,
        'endLine' => 287,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'robot' => 
      array (
        'name' => 'robot',
        'parameters' => 
        array (
          'userAgent' => 
          array (
            'name' => 'userAgent',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 294,
                'endLine' => 294,
                'startTokenPos' => 1352,
                'startFilePos' => 8011,
                'endTokenPos' => 1352,
                'endFilePos' => 8014,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 294,
            'endLine' => 294,
            'startColumn' => 27,
            'endColumn' => 43,
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
 * Get the robot name.
 * @param  string|null $userAgent
 * @return string|bool
 */',
        'startLine' => 294,
        'endLine' => 301,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'isRobot' => 
      array (
        'name' => 'isRobot',
        'parameters' => 
        array (
          'userAgent' => 
          array (
            'name' => 'userAgent',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 308,
                'endLine' => 308,
                'startTokenPos' => 1418,
                'startFilePos' => 8366,
                'endTokenPos' => 1418,
                'endFilePos' => 8369,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 308,
            'endLine' => 308,
            'startColumn' => 29,
            'endColumn' => 45,
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
 * Check if device is a robot.
 * @param  string|null $userAgent
 * @return bool
 */',
        'startLine' => 308,
        'endLine' => 311,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'deviceType' => 
      array (
        'name' => 'deviceType',
        'parameters' => 
        array (
          'userAgent' => 
          array (
            'name' => 'userAgent',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 319,
                'endLine' => 319,
                'startTokenPos' => 1458,
                'startFilePos' => 8641,
                'endTokenPos' => 1458,
                'endFilePos' => 8644,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 319,
            'endLine' => 319,
            'startColumn' => 32,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'httpHeaders' => 
          array (
            'name' => 'httpHeaders',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 319,
                'endLine' => 319,
                'startTokenPos' => 1465,
                'startFilePos' => 8662,
                'endTokenPos' => 1465,
                'endFilePos' => 8665,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 319,
            'endLine' => 319,
            'startColumn' => 51,
            'endColumn' => 69,
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
 * Get the device type
 * @param null $userAgent
 * @param null $httpHeaders
 * @return string
 */',
        'startLine' => 319,
        'endLine' => 332,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'version' => 
      array (
        'name' => 'version',
        'parameters' => 
        array (
          'propertyName' => 
          array (
            'name' => 'propertyName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 334,
            'endLine' => 334,
            'startColumn' => 29,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => 'self::VERSION_TYPE_STRING',
              'attributes' => 
              array (
                'startLine' => 334,
                'endLine' => 334,
                'startTokenPos' => 1579,
                'startFilePos' => 9113,
                'endTokenPos' => 1581,
                'endFilePos' => 9137,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 334,
            'endLine' => 334,
            'startColumn' => 44,
            'endColumn' => 76,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 334,
        'endLine' => 373,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      'mergeRules' => 
      array (
        'name' => 'mergeRules',
        'parameters' => 
        array (
          'all' => 
          array (
            'name' => 'all',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 380,
            'endLine' => 380,
            'startColumn' => 42,
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
 * Merge multiple rules into one array.
 * @param array $all
 * @return array
 */',
        'startLine' => 380,
        'endLine' => 397,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 18,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
        'aliasName' => NULL,
      ),
      '__call' => 
      array (
        'name' => '__call',
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
            'startLine' => 402,
            'endLine' => 402,
            'startColumn' => 28,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 402,
            'endLine' => 402,
            'startColumn' => 35,
            'endColumn' => 44,
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
 * @inheritdoc
 */',
        'startLine' => 402,
        'endLine' => 414,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Jenssegers\\Agent',
        'declaringClassName' => 'Jenssegers\\Agent\\Agent',
        'implementingClassName' => 'Jenssegers\\Agent\\Agent',
        'currentClassName' => 'Jenssegers\\Agent\\Agent',
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