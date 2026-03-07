<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../microweber-deps/seo-helper/src/Contracts/Entities/Webmasters.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Arcanedev\SeoHelper\Contracts\Entities\Webmasters
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-2c363c057c1427f685bee317e329c755d70fe925e10a21da405aee690f6f2187-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../microweber-deps/seo-helper/src/Contracts/Entities/Webmasters.php',
      ),
    ),
    'namespace' => 'Arcanedev\\SeoHelper\\Contracts\\Entities',
    'name' => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
    'shortName' => 'Webmasters',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Interface  Webmasters
 *
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 58,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Arcanedev\\SeoHelper\\Contracts\\Renderable',
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
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 26,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Contracts\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
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
                'startLine' => 40,
                'endLine' => 40,
                'startTokenPos' => 63,
                'startFilePos' => 945,
                'endTokenPos' => 64,
                'endFilePos' => 946,
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
            'startLine' => 40,
            'endLine' => 40,
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
 * @return $this
 */',
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 56,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Arcanedev\\SeoHelper\\Contracts\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
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
            'startLine' => 50,
            'endLine' => 50,
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
            'startLine' => 50,
            'endLine' => 50,
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
 * @return $this
 */',
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 46,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Contracts\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
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
 * @return $this
 */',
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 28,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Contracts\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Webmasters',
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