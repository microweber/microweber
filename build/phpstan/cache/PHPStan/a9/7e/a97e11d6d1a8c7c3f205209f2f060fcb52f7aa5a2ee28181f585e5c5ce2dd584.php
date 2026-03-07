<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../fakerphp/faker/src/Faker/Factory.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Faker\Factory
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-fed3e4ca2896ea96325d52b956a49894b982618d091dba4dd8761b9b5fa9cb7f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Faker\\Factory',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../fakerphp/faker/src/Faker/Factory.php',
      ),
    ),
    'namespace' => 'Faker',
    'name' => 'Faker\\Factory',
    'shortName' => 'Factory',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 71,
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
      'DEFAULT_LOCALE' => 
      array (
        'declaringClassName' => 'Faker\\Factory',
        'implementingClassName' => 'Faker\\Factory',
        'name' => 'DEFAULT_LOCALE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'en_US\'',
          'attributes' => 
          array (
            'startLine' => 7,
            'endLine' => 7,
            'startTokenPos' => 21,
            'startFilePos' => 75,
            'endTokenPos' => 21,
            'endFilePos' => 81,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 7,
        'endLine' => 7,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
    ),
    'immediateProperties' => 
    array (
      'defaultProviders' => 
      array (
        'declaringClassName' => 'Faker\\Factory',
        'implementingClassName' => 'Faker\\Factory',
        'name' => 'defaultProviders',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'Address\', \'Barcode\', \'Biased\', \'Color\', \'Company\', \'DateTime\', \'File\', \'HtmlLorem\', \'Image\', \'Internet\', \'Lorem\', \'Medical\', \'Miscellaneous\', \'Payment\', \'Person\', \'PhoneNumber\', \'Text\', \'UserAgent\', \'Uuid\']',
          'attributes' => 
          array (
            'startLine' => 9,
            'endLine' => 9,
            'startTokenPos' => 32,
            'startFilePos' => 126,
            'endTokenPos' => 88,
            'endFilePos' => 333,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 9,
        'endLine' => 9,
        'startColumn' => 5,
        'endColumn' => 250,
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
      'create' => 
      array (
        'name' => 'create',
        'parameters' => 
        array (
          'locale' => 
          array (
            'name' => 'locale',
            'default' => 
            array (
              'code' => 'self::DEFAULT_LOCALE',
              'attributes' => 
              array (
                'startLine' => 18,
                'endLine' => 18,
                'startTokenPos' => 105,
                'startFilePos' => 495,
                'endTokenPos' => 107,
                'endFilePos' => 514,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 18,
            'endLine' => 18,
            'startColumn' => 35,
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
 * Create a new generator
 *
 * @param string $locale
 *
 * @return Generator
 */',
        'startLine' => 18,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Factory',
        'implementingClassName' => 'Faker\\Factory',
        'currentClassName' => 'Faker\\Factory',
        'aliasName' => NULL,
      ),
      'getProviderClassname' => 
      array (
        'name' => 'getProviderClassname',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 52,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'locale' => 
          array (
            'name' => 'locale',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 36,
                'endLine' => 36,
                'startTokenPos' => 191,
                'startFilePos' => 997,
                'endTokenPos' => 191,
                'endFilePos' => 998,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 63,
            'endColumn' => 74,
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
 * @param string $provider
 * @param string $locale
 *
 * @return string
 */',
        'startLine' => 36,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Factory',
        'implementingClassName' => 'Faker\\Factory',
        'currentClassName' => 'Faker\\Factory',
        'aliasName' => NULL,
      ),
      'findProviderClassname' => 
      array (
        'name' => 'findProviderClassname',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 61,
            'endLine' => 61,
            'startColumn' => 53,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'locale' => 
          array (
            'name' => 'locale',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 61,
                'endLine' => 61,
                'startTokenPos' => 318,
                'startFilePos' => 1775,
                'endTokenPos' => 318,
                'endFilePos' => 1776,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 61,
            'endLine' => 61,
            'startColumn' => 64,
            'endColumn' => 75,
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
 * @param string $provider
 * @param string $locale
 *
 * @return string|null
 */',
        'startLine' => 61,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Factory',
        'implementingClassName' => 'Faker\\Factory',
        'currentClassName' => 'Faker\\Factory',
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