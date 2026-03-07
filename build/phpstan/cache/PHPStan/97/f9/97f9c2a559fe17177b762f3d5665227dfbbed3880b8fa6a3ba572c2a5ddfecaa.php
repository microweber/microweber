<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../microweber-deps/seo-helper/src/Entities/Analytics.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Arcanedev\SeoHelper\Entities\Analytics
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6633082e25a7aee2ea09f1b412a83405dad3a07d6fe5b4ca3ca1c24b854bfa98-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../microweber-deps/seo-helper/src/Entities/Analytics.php',
      ),
    ),
    'namespace' => 'Arcanedev\\SeoHelper\\Entities',
    'name' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
    'shortName' => 'Analytics',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Class     Analytics
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 124,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\Analytics',
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
      'google' => 
      array (
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'name' => 'google',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'\'',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 58,
            'startFilePos' => 790,
            'endTokenPos' => 58,
            'endFilePos' => 791,
          ),
        ),
        'docComment' => '/**
 * Google Analytics code.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 27,
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
                'startLine' => 46,
                'endLine' => 46,
                'startTokenPos' => 77,
                'startFilePos' => 1108,
                'endTokenPos' => 78,
                'endFilePos' => 1109,
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
            'startLine' => 46,
            'endLine' => 46,
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
 * Make an Analytics instance.
 *
 * @param  array  $configs
 */',
        'startLine' => 46,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'aliasName' => NULL,
      ),
      'setGoogle' => 
      array (
        'name' => 'setGoogle',
        'parameters' => 
        array (
          'code' => 
          array (
            'name' => 'code',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 65,
            'endLine' => 65,
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
 * Set Google Analytics code.
 *
 * @param  string  $code
 *
 * @return $this
 */',
        'startLine' => 65,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
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
        'startLine' => 82,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
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
        'startLine' => 94,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'aliasName' => NULL,
      ),
      'renderGoogleScript' => 
      array (
        'name' => 'renderGoogleScript',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Render the Google Analytics tracking script.
 *
 * @return string
 */',
        'startLine' => 109,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Analytics',
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