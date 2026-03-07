<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/./semver/src/Comparator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Composer\Semver\Comparator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-1dbe4098f833b303a83ee97b0e55877e01caae763f5e990da8ee360944d4cb84-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Composer\\Semver\\Comparator',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/./semver/src/Comparator.php',
      ),
    ),
    'namespace' => 'Composer\\Semver',
    'name' => 'Composer\\Semver\\Comparator',
    'shortName' => 'Comparator',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 113,
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
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'greaterThan' => 
      array (
        'name' => 'greaterThan',
        'parameters' => 
        array (
          'version1' => 
          array (
            'name' => 'version1',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 40,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'version2' => 
          array (
            'name' => 'version2',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 51,
            'endColumn' => 59,
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
 * Evaluates the expression: $version1 > $version2.
 *
 * @param string $version1
 * @param string $version2
 *
 * @return bool
 */',
        'startLine' => 26,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer\\Semver',
        'declaringClassName' => 'Composer\\Semver\\Comparator',
        'implementingClassName' => 'Composer\\Semver\\Comparator',
        'currentClassName' => 'Composer\\Semver\\Comparator',
        'aliasName' => NULL,
      ),
      'greaterThanOrEqualTo' => 
      array (
        'name' => 'greaterThanOrEqualTo',
        'parameters' => 
        array (
          'version1' => 
          array (
            'name' => 'version1',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 49,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'version2' => 
          array (
            'name' => 'version2',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 60,
            'endColumn' => 68,
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
 * Evaluates the expression: $version1 >= $version2.
 *
 * @param string $version1
 * @param string $version2
 *
 * @return bool
 */',
        'startLine' => 39,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer\\Semver',
        'declaringClassName' => 'Composer\\Semver\\Comparator',
        'implementingClassName' => 'Composer\\Semver\\Comparator',
        'currentClassName' => 'Composer\\Semver\\Comparator',
        'aliasName' => NULL,
      ),
      'lessThan' => 
      array (
        'name' => 'lessThan',
        'parameters' => 
        array (
          'version1' => 
          array (
            'name' => 'version1',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 37,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'version2' => 
          array (
            'name' => 'version2',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 48,
            'endColumn' => 56,
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
 * Evaluates the expression: $version1 < $version2.
 *
 * @param string $version1
 * @param string $version2
 *
 * @return bool
 */',
        'startLine' => 52,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer\\Semver',
        'declaringClassName' => 'Composer\\Semver\\Comparator',
        'implementingClassName' => 'Composer\\Semver\\Comparator',
        'currentClassName' => 'Composer\\Semver\\Comparator',
        'aliasName' => NULL,
      ),
      'lessThanOrEqualTo' => 
      array (
        'name' => 'lessThanOrEqualTo',
        'parameters' => 
        array (
          'version1' => 
          array (
            'name' => 'version1',
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
            'startColumn' => 46,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'version2' => 
          array (
            'name' => 'version2',
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
            'startColumn' => 57,
            'endColumn' => 65,
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
 * Evaluates the expression: $version1 <= $version2.
 *
 * @param string $version1
 * @param string $version2
 *
 * @return bool
 */',
        'startLine' => 65,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer\\Semver',
        'declaringClassName' => 'Composer\\Semver\\Comparator',
        'implementingClassName' => 'Composer\\Semver\\Comparator',
        'currentClassName' => 'Composer\\Semver\\Comparator',
        'aliasName' => NULL,
      ),
      'equalTo' => 
      array (
        'name' => 'equalTo',
        'parameters' => 
        array (
          'version1' => 
          array (
            'name' => 'version1',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 78,
            'endLine' => 78,
            'startColumn' => 36,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'version2' => 
          array (
            'name' => 'version2',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 78,
            'endLine' => 78,
            'startColumn' => 47,
            'endColumn' => 55,
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
 * Evaluates the expression: $version1 == $version2.
 *
 * @param string $version1
 * @param string $version2
 *
 * @return bool
 */',
        'startLine' => 78,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer\\Semver',
        'declaringClassName' => 'Composer\\Semver\\Comparator',
        'implementingClassName' => 'Composer\\Semver\\Comparator',
        'currentClassName' => 'Composer\\Semver\\Comparator',
        'aliasName' => NULL,
      ),
      'notEqualTo' => 
      array (
        'name' => 'notEqualTo',
        'parameters' => 
        array (
          'version1' => 
          array (
            'name' => 'version1',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 91,
            'endLine' => 91,
            'startColumn' => 39,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'version2' => 
          array (
            'name' => 'version2',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 91,
            'endLine' => 91,
            'startColumn' => 50,
            'endColumn' => 58,
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
 * Evaluates the expression: $version1 != $version2.
 *
 * @param string $version1
 * @param string $version2
 *
 * @return bool
 */',
        'startLine' => 91,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer\\Semver',
        'declaringClassName' => 'Composer\\Semver\\Comparator',
        'implementingClassName' => 'Composer\\Semver\\Comparator',
        'currentClassName' => 'Composer\\Semver\\Comparator',
        'aliasName' => NULL,
      ),
      'compare' => 
      array (
        'name' => 'compare',
        'parameters' => 
        array (
          'version1' => 
          array (
            'name' => 'version1',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 107,
            'endLine' => 107,
            'startColumn' => 36,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 107,
            'endLine' => 107,
            'startColumn' => 47,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'version2' => 
          array (
            'name' => 'version2',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 107,
            'endLine' => 107,
            'startColumn' => 58,
            'endColumn' => 66,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Evaluates the expression: $version1 $operator $version2.
 *
 * @param string $version1
 * @param string $operator
 * @param string $version2
 *
 * @return bool
 *
 * @phpstan-param Constraint::STR_OP_*  $operator
 */',
        'startLine' => 107,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer\\Semver',
        'declaringClassName' => 'Composer\\Semver\\Comparator',
        'implementingClassName' => 'Composer\\Semver\\Comparator',
        'currentClassName' => 'Composer\\Semver\\Comparator',
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