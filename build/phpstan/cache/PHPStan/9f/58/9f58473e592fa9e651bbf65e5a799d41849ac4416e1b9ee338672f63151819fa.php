<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../microweber-deps/seo-helper/src/Entities/Webmasters.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Arcanedev\SeoHelper\Entities\Webmasters
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-76b115e21b9eb8e252bfb75ef4d60a6d77d861e7e0e38b94db411fd14c255b2e-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../microweber-deps/seo-helper/src/Entities/Webmasters.php',
      ),
    ),
    'namespace' => 'Arcanedev\\SeoHelper\\Entities',
    'name' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
    'shortName' => 'Webmasters',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Class     Webmasters
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 187,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
    ),
    'traitClassNames' => 
    array (
      0 => 'Arcanedev\\SeoHelper\\Traits\\Configurable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'supported' => 
      array (
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'name' => 'supported',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'google\' => \'google-site-verification\', \'bing\' => \'msvalidate.01\', \'alexa\' => \'alexaVerifyID\', \'pinterest\' => \'p:domain_verify\', \'yandex\' => \'yandex-verification\']',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 40,
            'startTokenPos' => 58,
            'startFilePos' => 800,
            'endTokenPos' => 94,
            'endFilePos' => 1024,
          ),
        ),
        'docComment' => '/**
 * The supported webmasters.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'metas' => 
      array (
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'name' => 'metas',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The meta collection.
 *
 * @var \\Arcanedev\\SeoHelper\\Contracts\\Entities\\MetaCollection
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 21,
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
          'configs' => 
          array (
            'name' => 'configs',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 59,
                'endLine' => 59,
                'startTokenPos' => 120,
                'startFilePos' => 1482,
                'endTokenPos' => 121,
                'endFilePos' => 1483,
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
            'startLine' => 59,
            'endLine' => 59,
            'startColumn' => 33,
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
 * Create Webmasters instance.
 *
 * @param  array  $configs
 */',
        'startLine' => 59,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'aliasName' => NULL,
      ),
      'init' => 
      array (
        'name' => 'init',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Start the engine.
 */',
        'startLine' => 70,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'aliasName' => NULL,
      ),
      'getWebmasterName' => 
      array (
        'name' => 'getWebmasterName',
        'parameters' => 
        array (
          'webmaster' => 
          array (
            'name' => 'webmaster',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 39,
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
 * Get the webmaster meta name.
 *
 * @param  string  $webmaster
 *
 * @return string|null
 */',
        'startLine' => 89,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'aliasName' => NULL,
      ),
      'all' => 
      array (
        'name' => 'all',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all the metas collection.
 *
 * @return \\Arcanedev\\SeoHelper\\Contracts\\Entities\\MetaCollection
 */',
        'startLine' => 101,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'aliasName' => NULL,
      ),
      'make' => 
      array (
        'name' => 'make',
        'parameters' => 
        array (
          'webmasters' => 
          array (
            'name' => 'webmasters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 117,
                'endLine' => 117,
                'startTokenPos' => 274,
                'startFilePos' => 2840,
                'endTokenPos' => 275,
                'endFilePos' => 2841,
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
            'startLine' => 117,
            'endLine' => 117,
            'startColumn' => 33,
            'endColumn' => 54,
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
 * Make Webmaster instance.
 *
 * @param  array  $webmasters
 *
 * @return \\Arcanedev\\SeoHelper\\Entities\\Webmasters
 */',
        'startLine' => 117,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'aliasName' => NULL,
      ),
      'add' => 
      array (
        'name' => 'add',
        'parameters' => 
        array (
          'webmaster' => 
          array (
            'name' => 'webmaster',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 130,
            'endLine' => 130,
            'startColumn' => 25,
            'endColumn' => 34,
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
            'startLine' => 130,
            'endLine' => 130,
            'startColumn' => 37,
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
 * Add a webmaster to collection.
 *
 * @param  string  $webmaster
 * @param  string  $content
 *
 * @return \\Arcanedev\\SeoHelper\\Entities\\Webmasters
 */',
        'startLine' => 130,
        'endLine' => 137,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'aliasName' => NULL,
      ),
      'reset' => 
      array (
        'name' => 'reset',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Reset the webmaster collection.
 *
 * @return \\Arcanedev\\SeoHelper\\Entities\\Webmasters
 */',
        'startLine' => 144,
        'endLine' => 149,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'aliasName' => NULL,
      ),
      'render' => 
      array (
        'name' => 'render',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Render the tag.
 *
 * @return string
 */',
        'startLine' => 156,
        'endLine' => 159,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'aliasName' => NULL,
      ),
      '__toString' => 
      array (
        'name' => '__toString',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Render the tag.
 *
 * @return string
 */',
        'startLine' => 166,
        'endLine' => 169,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'aliasName' => NULL,
      ),
      'isSupported' => 
      array (
        'name' => 'isSupported',
        'parameters' => 
        array (
          'webmaster' => 
          array (
            'name' => 'webmaster',
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
            'startLine' => 183,
            'endLine' => 183,
            'startColumn' => 34,
            'endColumn' => 50,
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
 * Check if the webmaster is supported.
 *
 * @param  string  $webmaster
 *
 * @return bool
 */',
        'startLine' => 183,
        'endLine' => 186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Webmasters',
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