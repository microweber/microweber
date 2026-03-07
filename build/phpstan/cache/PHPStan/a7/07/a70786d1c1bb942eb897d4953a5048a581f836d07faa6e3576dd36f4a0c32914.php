<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../microweber-deps/seo-helper/src/Entities/Twitter/Card.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Arcanedev\SeoHelper\Entities\Twitter\Card
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-a448d29e46ec24792bcf8c61b231d446e708d8bee593816f52bb266be9c3c081-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../microweber-deps/seo-helper/src/Entities/Twitter/Card.php',
      ),
    ),
    'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
    'name' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
    'shortName' => 'Card',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Class     Card
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 338,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Arcanedev\\SeoHelper\\Contracts\\Entities\\TwitterCard',
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
      'type' => 
      array (
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'name' => 'type',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Card type.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'metas' => 
      array (
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'name' => 'metas',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Card meta collection.
 *
 * @var \\Arcanedev\\SeoHelper\\Contracts\\Entities\\MetaCollection
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'images' => 
      array (
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'name' => 'images',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 82,
            'startFilePos' => 1086,
            'endTokenPos' => 83,
            'endFilePos' => 1087,
          ),
        ),
        'docComment' => '/**
 * Card images.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 28,
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
                'startLine' => 62,
                'endLine' => 62,
                'startTokenPos' => 102,
                'startFilePos' => 1408,
                'endTokenPos' => 103,
                'endFilePos' => 1409,
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
            'startLine' => 62,
            'endLine' => 62,
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
 * Make the twitter card instance.
 *
 * @param  array  $configs
 */',
        'startLine' => 62,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
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
 *
 * @return $this
 */',
        'startLine' => 75,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'aliasName' => NULL,
      ),
      'setPrefix' => 
      array (
        'name' => 'setPrefix',
        'parameters' => 
        array (
          'prefix' => 
          array (
            'name' => 'prefix',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 32,
            'endColumn' => 38,
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
 * Set meta prefix name.
 *
 * @param  string  $prefix
 *
 * @return $this
 */',
        'startLine' => 98,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'aliasName' => NULL,
      ),
      'setType' => 
      array (
        'name' => 'setType',
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
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 29,
            'endColumn' => 33,
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
 * Set the card type.
 *
 * @param  string  $type
 *
 * @return $this
 */',
        'startLine' => 112,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'aliasName' => NULL,
      ),
      'setSite' => 
      array (
        'name' => 'setSite',
        'parameters' => 
        array (
          'site' => 
          array (
            'name' => 'site',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 29,
            'endColumn' => 33,
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
 * Set card site.
 *
 * @param  string  $site
 *
 * @return $this
 */',
        'startLine' => 129,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'aliasName' => NULL,
      ),
      'setTitle' => 
      array (
        'name' => 'setTitle',
        'parameters' => 
        array (
          'title' => 
          array (
            'name' => 'title',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 145,
            'endLine' => 145,
            'startColumn' => 30,
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
 * Set card title.
 *
 * @param  string  $title
 *
 * @return $this
 */',
        'startLine' => 145,
        'endLine' => 148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'aliasName' => NULL,
      ),
      'setDescription' => 
      array (
        'name' => 'setDescription',
        'parameters' => 
        array (
          'description' => 
          array (
            'name' => 'description',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 157,
            'endLine' => 157,
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
 * Set card description.
 *
 * @param  string  $description
 *
 * @return $this
 */',
        'startLine' => 157,
        'endLine' => 160,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'aliasName' => NULL,
      ),
      'addImage' => 
      array (
        'name' => 'addImage',
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
            'startLine' => 169,
            'endLine' => 169,
            'startColumn' => 30,
            'endColumn' => 33,
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
 * Add image to the card.
 *
 * @param  string  $url
 *
 * @return $this
 */',
        'startLine' => 169,
        'endLine' => 176,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'aliasName' => NULL,
      ),
      'addMetas' => 
      array (
        'name' => 'addMetas',
        'parameters' => 
        array (
          'metas' => 
          array (
            'name' => 'metas',
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
            'startLine' => 185,
            'endLine' => 185,
            'startColumn' => 30,
            'endColumn' => 41,
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
 * Add many metas to the card.
 *
 * @param  array  $metas
 *
 * @return $this
 */',
        'startLine' => 185,
        'endLine' => 190,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'aliasName' => NULL,
      ),
      'addMeta' => 
      array (
        'name' => 'addMeta',
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
            'startLine' => 200,
            'endLine' => 200,
            'startColumn' => 29,
            'endColumn' => 33,
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
            'startLine' => 200,
            'endLine' => 200,
            'startColumn' => 36,
            'endColumn' => 43,
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
 * Add a meta to the card.
 *
 * @param  string        $name
 * @param  string|array  $content
 *
 * @return $this
 */',
        'startLine' => 200,
        'endLine' => 205,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'aliasName' => NULL,
      ),
      'types' => 
      array (
        'name' => 'types',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all supported card types.
 *
 * @return array
 */',
        'startLine' => 212,
        'endLine' => 223,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
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
 * Reset the card.
 *
 * @return $this
 */',
        'startLine' => 235,
        'endLine' => 241,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
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
 * Render the twitter card.
 *
 * @return string
 */',
        'startLine' => 248,
        'endLine' => 255,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
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
        'startLine' => 262,
        'endLine' => 265,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'aliasName' => NULL,
      ),
      'checkType' => 
      array (
        'name' => 'checkType',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => true,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 279,
            'endLine' => 279,
            'startColumn' => 32,
            'endColumn' => 37,
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
 * Check the card type.
 *
 * @param  string  $type
 *
 * @throws \\Arcanedev\\SeoHelper\\Exceptions\\InvalidTwitterCardException
 */',
        'startLine' => 279,
        'endLine' => 292,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'aliasName' => NULL,
      ),
      'checkSite' => 
      array (
        'name' => 'checkSite',
        'parameters' => 
        array (
          'site' => 
          array (
            'name' => 'site',
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
            'byRef' => true,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 299,
            'endLine' => 299,
            'startColumn' => 32,
            'endColumn' => 44,
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
 * Check the card site.
 *
 * @param  string  $site
 */',
        'startLine' => 299,
        'endLine' => 302,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'aliasName' => NULL,
      ),
      'loadImages' => 
      array (
        'name' => 'loadImages',
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
 * Render card images.
 */',
        'startLine' => 312,
        'endLine' => 323,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'aliasName' => NULL,
      ),
      'prepareUsername' => 
      array (
        'name' => 'prepareUsername',
        'parameters' => 
        array (
          'username' => 
          array (
            'name' => 'username',
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
            'startLine' => 332,
            'endLine' => 332,
            'startColumn' => 38,
            'endColumn' => 53,
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
 * Prepare username.
 *
 * @param  string  $username
 *
 * @return string
 */',
        'startLine' => 332,
        'endLine' => 337,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Arcanedev\\SeoHelper\\Entities\\Twitter',
        'declaringClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'implementingClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
        'currentClassName' => 'Arcanedev\\SeoHelper\\Entities\\Twitter\\Card',
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