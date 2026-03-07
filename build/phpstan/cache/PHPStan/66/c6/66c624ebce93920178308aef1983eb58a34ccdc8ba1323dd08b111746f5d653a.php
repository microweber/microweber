<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../nesbot/carbon/src/Carbon/Traits/Modifiers.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Carbon\Traits\Modifiers
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-3fa3b46a794e51ec6c6a84090c21a84103986d34c130a36cf56d96181bff839f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Carbon\\Traits\\Modifiers',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../nesbot/carbon/src/Carbon/Traits/Modifiers.php',
      ),
    ),
    'namespace' => 'Carbon\\Traits',
    'name' => 'Carbon\\Traits\\Modifiers',
    'shortName' => 'Modifiers',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Trait Modifiers.
 *
 * Returns dates relative to current date using modifier short-hand.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 476,
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
      'midDayAt' => 
      array (
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'name' => 'midDayAt',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '12',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 50,
            'startFilePos' => 601,
            'endTokenPos' => 50,
            'endFilePos' => 602,
          ),
        ),
        'docComment' => '/**
 * Midday/noon hour.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
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
      'getMidDayAt' => 
      array (
        'name' => 'getMidDayAt',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * get midday/noon hour
 *
 * @return int
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
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'setMidDayAt' => 
      array (
        'name' => 'setMidDayAt',
        'parameters' => 
        array (
          'hour' => 
          array (
            'name' => 'hour',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 58,
            'endLine' => 58,
            'startColumn' => 40,
            'endColumn' => 44,
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
 * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *             You should rather consider mid-day is always 12pm, then if you need to test if it\'s an other
 *             hour, test it explicitly:
 *                 $date->format(\'G\') == 13
 *             or to set explicitly to a given hour:
 *                 $date->setTime(13, 0, 0, 0)
 *
 * Set midday/noon hour
 *
 * @param int $hour midday hour
 *
 * @return void
 */',
        'startLine' => 58,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'midDay' => 
      array (
        'name' => 'midDay',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Modify to midday, default to self::$midDayAt
 *
 * @return static
 */',
        'startLine' => 68,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'next' => 
      array (
        'name' => 'next',
        'parameters' => 
        array (
          'modifier' => 
          array (
            'name' => 'modifier',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 83,
                'endLine' => 83,
                'startTokenPos' => 149,
                'startFilePos' => 2009,
                'endTokenPos' => 149,
                'endFilePos' => 2012,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 83,
            'endLine' => 83,
            'startColumn' => 26,
            'endColumn' => 41,
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
 * Modify to the next occurrence of a given modifier such as a day of
 * the week. If no modifier is provided, modify to the next occurrence
 * of the current day of the week. Use the supplied constants
 * to indicate the desired dayOfWeek, ex. static::MONDAY.
 *
 * @param string|int|null $modifier
 *
 * @return static
 */',
        'startLine' => 83,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'nextOrPreviousDay' => 
      array (
        'name' => 'nextOrPreviousDay',
        'parameters' => 
        array (
          'weekday' => 
          array (
            'name' => 'weekday',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 102,
                'endLine' => 102,
                'startTokenPos' => 224,
                'startFilePos' => 2462,
                'endTokenPos' => 224,
                'endFilePos' => 2465,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 102,
            'endLine' => 102,
            'startColumn' => 40,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'forward' => 
          array (
            'name' => 'forward',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 102,
                'endLine' => 102,
                'startTokenPos' => 231,
                'startFilePos' => 2479,
                'endTokenPos' => 231,
                'endFilePos' => 2482,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 102,
            'endLine' => 102,
            'startColumn' => 57,
            'endColumn' => 71,
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
 * Go forward or backward to the next week- or weekend-day.
 *
 * @param bool $weekday
 * @param bool $forward
 *
 * @return static
 */',
        'startLine' => 102,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'nextWeekday' => 
      array (
        'name' => 'nextWeekday',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Go forward to the next weekday.
 *
 * @return static
 */',
        'startLine' => 120,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'previousWeekday' => 
      array (
        'name' => 'previousWeekday',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Go backward to the previous weekday.
 *
 * @return static
 */',
        'startLine' => 130,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'nextWeekendDay' => 
      array (
        'name' => 'nextWeekendDay',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Go forward to the next weekend day.
 *
 * @return static
 */',
        'startLine' => 140,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'previousWeekendDay' => 
      array (
        'name' => 'previousWeekendDay',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Go backward to the previous weekend day.
 *
 * @return static
 */',
        'startLine' => 150,
        'endLine' => 153,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'previous' => 
      array (
        'name' => 'previous',
        'parameters' => 
        array (
          'modifier' => 
          array (
            'name' => 'modifier',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 165,
                'endLine' => 165,
                'startTokenPos' => 422,
                'startFilePos' => 3923,
                'endTokenPos' => 422,
                'endFilePos' => 3926,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 165,
            'endLine' => 165,
            'startColumn' => 30,
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
 * Modify to the previous occurrence of a given modifier such as a day of
 * the week. If no dayOfWeek is provided, modify to the previous occurrence
 * of the current day of the week. Use the supplied constants
 * to indicate the desired dayOfWeek, ex. static::MONDAY.
 *
 * @param string|int|null $modifier
 *
 * @return static
 */',
        'startLine' => 165,
        'endLine' => 174,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'firstOfMonth' => 
      array (
        'name' => 'firstOfMonth',
        'parameters' => 
        array (
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 186,
                'endLine' => 186,
                'startTokenPos' => 497,
                'startFilePos' => 4552,
                'endTokenPos' => 497,
                'endFilePos' => 4555,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 186,
            'endLine' => 186,
            'startColumn' => 34,
            'endColumn' => 50,
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
 * Modify to the first occurrence of a given day of the week
 * in the current month. If no dayOfWeek is provided, modify to the
 * first day of the current month.  Use the supplied constants
 * to indicate the desired dayOfWeek, ex. static::MONDAY.
 *
 * @param int|null $dayOfWeek
 *
 * @return static
 */',
        'startLine' => 186,
        'endLine' => 195,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'lastOfMonth' => 
      array (
        'name' => 'lastOfMonth',
        'parameters' => 
        array (
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 207,
                'endLine' => 207,
                'startTokenPos' => 583,
                'startFilePos' => 5195,
                'endTokenPos' => 583,
                'endFilePos' => 5198,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 207,
            'endLine' => 207,
            'startColumn' => 33,
            'endColumn' => 49,
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
 * Modify to the last occurrence of a given day of the week
 * in the current month. If no dayOfWeek is provided, modify to the
 * last day of the current month.  Use the supplied constants
 * to indicate the desired dayOfWeek, ex. static::MONDAY.
 *
 * @param int|null $dayOfWeek
 *
 * @return static
 */',
        'startLine' => 207,
        'endLine' => 216,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'nthOfMonth' => 
      array (
        'name' => 'nthOfMonth',
        'parameters' => 
        array (
          'nth' => 
          array (
            'name' => 'nth',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 229,
            'endLine' => 229,
            'startColumn' => 32,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 229,
            'endLine' => 229,
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
 * Modify to the given occurrence of a given day of the week
 * in the current month. If the calculated occurrence is outside the scope
 * of the current month, then return false and no modifications are made.
 * Use the supplied constants to indicate the desired dayOfWeek, ex. static::MONDAY.
 *
 * @param int $nth
 * @param int $dayOfWeek
 *
 * @return mixed
 */',
        'startLine' => 229,
        'endLine' => 236,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'firstOfQuarter' => 
      array (
        'name' => 'firstOfQuarter',
        'parameters' => 
        array (
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 248,
                'endLine' => 248,
                'startTokenPos' => 768,
                'startFilePos' => 6629,
                'endTokenPos' => 768,
                'endFilePos' => 6632,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 248,
            'endLine' => 248,
            'startColumn' => 36,
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
 * Modify to the first occurrence of a given day of the week
 * in the current quarter. If no dayOfWeek is provided, modify to the
 * first day of the current quarter.  Use the supplied constants
 * to indicate the desired dayOfWeek, ex. static::MONDAY.
 *
 * @param int|null $dayOfWeek day of the week default null
 *
 * @return static
 */',
        'startLine' => 248,
        'endLine' => 251,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'lastOfQuarter' => 
      array (
        'name' => 'lastOfQuarter',
        'parameters' => 
        array (
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 263,
                'endLine' => 263,
                'startTokenPos' => 822,
                'startFilePos' => 7200,
                'endTokenPos' => 822,
                'endFilePos' => 7203,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 263,
            'endLine' => 263,
            'startColumn' => 35,
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
 * Modify to the last occurrence of a given day of the week
 * in the current quarter. If no dayOfWeek is provided, modify to the
 * last day of the current quarter.  Use the supplied constants
 * to indicate the desired dayOfWeek, ex. static::MONDAY.
 *
 * @param int|null $dayOfWeek day of the week default null
 *
 * @return static
 */',
        'startLine' => 263,
        'endLine' => 266,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'nthOfQuarter' => 
      array (
        'name' => 'nthOfQuarter',
        'parameters' => 
        array (
          'nth' => 
          array (
            'name' => 'nth',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 279,
            'endLine' => 279,
            'startColumn' => 34,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 279,
            'endLine' => 279,
            'startColumn' => 40,
            'endColumn' => 49,
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
 * Modify to the given occurrence of a given day of the week
 * in the current quarter. If the calculated occurrence is outside the scope
 * of the current quarter, then return false and no modifications are made.
 * Use the supplied constants to indicate the desired dayOfWeek, ex. static::MONDAY.
 *
 * @param int $nth
 * @param int $dayOfWeek
 *
 * @return mixed
 */',
        'startLine' => 279,
        'endLine' => 287,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'firstOfYear' => 
      array (
        'name' => 'firstOfYear',
        'parameters' => 
        array (
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 299,
                'endLine' => 299,
                'startTokenPos' => 1002,
                'startFilePos' => 8605,
                'endTokenPos' => 1002,
                'endFilePos' => 8608,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 299,
            'endLine' => 299,
            'startColumn' => 33,
            'endColumn' => 49,
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
 * Modify to the first occurrence of a given day of the week
 * in the current year. If no dayOfWeek is provided, modify to the
 * first day of the current year.  Use the supplied constants
 * to indicate the desired dayOfWeek, ex. static::MONDAY.
 *
 * @param int|null $dayOfWeek day of the week default null
 *
 * @return static
 */',
        'startLine' => 299,
        'endLine' => 302,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'lastOfYear' => 
      array (
        'name' => 'lastOfYear',
        'parameters' => 
        array (
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 314,
                'endLine' => 314,
                'startTokenPos' => 1036,
                'startFilePos' => 9103,
                'endTokenPos' => 1036,
                'endFilePos' => 9106,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 314,
            'endLine' => 314,
            'startColumn' => 32,
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
 * Modify to the last occurrence of a given day of the week
 * in the current year. If no dayOfWeek is provided, modify to the
 * last day of the current year.  Use the supplied constants
 * to indicate the desired dayOfWeek, ex. static::MONDAY.
 *
 * @param int|null $dayOfWeek day of the week default null
 *
 * @return static
 */',
        'startLine' => 314,
        'endLine' => 317,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'nthOfYear' => 
      array (
        'name' => 'nthOfYear',
        'parameters' => 
        array (
          'nth' => 
          array (
            'name' => 'nth',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 330,
            'endLine' => 330,
            'startColumn' => 31,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 330,
            'endLine' => 330,
            'startColumn' => 37,
            'endColumn' => 46,
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
 * Modify to the given occurrence of a given day of the week
 * in the current year. If the calculated occurrence is outside the scope
 * of the current year, then return false and no modifications are made.
 * Use the supplied constants to indicate the desired dayOfWeek, ex. static::MONDAY.
 *
 * @param int $nth
 * @param int $dayOfWeek
 *
 * @return mixed
 */',
        'startLine' => 330,
        'endLine' => 335,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'average' => 
      array (
        'name' => 'average',
        'parameters' => 
        array (
          'date' => 
          array (
            'name' => 'date',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 345,
                'endLine' => 345,
                'startTokenPos' => 1149,
                'startFilePos' => 10145,
                'endTokenPos' => 1149,
                'endFilePos' => 10148,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 345,
            'endLine' => 345,
            'startColumn' => 29,
            'endColumn' => 40,
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
 * Modify the current instance to the average of a given instance (default now) and the current instance
 * (second-precision).
 *
 * @param \\Carbon\\Carbon|\\DateTimeInterface|null $date
 *
 * @return static
 */',
        'startLine' => 345,
        'endLine' => 348,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'closest' => 
      array (
        'name' => 'closest',
        'parameters' => 
        array (
          'date1' => 
          array (
            'name' => 'date1',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 358,
            'endLine' => 358,
            'startColumn' => 29,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'date2' => 
          array (
            'name' => 'date2',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 358,
            'endLine' => 358,
            'startColumn' => 37,
            'endColumn' => 42,
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
 * Get the closest date from the instance (second-precision).
 *
 * @param \\Carbon\\Carbon|\\DateTimeInterface|mixed $date1
 * @param \\Carbon\\Carbon|\\DateTimeInterface|mixed $date2
 *
 * @return static
 */',
        'startLine' => 358,
        'endLine' => 361,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'farthest' => 
      array (
        'name' => 'farthest',
        'parameters' => 
        array (
          'date1' => 
          array (
            'name' => 'date1',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 371,
            'endLine' => 371,
            'startColumn' => 30,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'date2' => 
          array (
            'name' => 'date2',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 371,
            'endLine' => 371,
            'startColumn' => 38,
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
 * Get the farthest date from the instance (second-precision).
 *
 * @param \\Carbon\\Carbon|\\DateTimeInterface|mixed $date1
 * @param \\Carbon\\Carbon|\\DateTimeInterface|mixed $date2
 *
 * @return static
 */',
        'startLine' => 371,
        'endLine' => 374,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'min' => 
      array (
        'name' => 'min',
        'parameters' => 
        array (
          'date' => 
          array (
            'name' => 'date',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 383,
                'endLine' => 383,
                'startTokenPos' => 1301,
                'startFilePos' => 11351,
                'endTokenPos' => 1301,
                'endFilePos' => 11354,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 383,
            'endLine' => 383,
            'startColumn' => 25,
            'endColumn' => 36,
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
 * Get the minimum instance between a given instance (default now) and the current instance.
 *
 * @param \\Carbon\\Carbon|\\DateTimeInterface|mixed $date
 *
 * @return static
 */',
        'startLine' => 383,
        'endLine' => 388,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'minimum' => 
      array (
        'name' => 'minimum',
        'parameters' => 
        array (
          'date' => 
          array (
            'name' => 'date',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 399,
                'endLine' => 399,
                'startTokenPos' => 1350,
                'startFilePos' => 11735,
                'endTokenPos' => 1350,
                'endFilePos' => 11738,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 399,
            'endLine' => 399,
            'startColumn' => 29,
            'endColumn' => 40,
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
 * Get the minimum instance between a given instance (default now) and the current instance.
 *
 * @param \\Carbon\\Carbon|\\DateTimeInterface|mixed $date
 *
 * @see min()
 *
 * @return static
 */',
        'startLine' => 399,
        'endLine' => 402,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'max' => 
      array (
        'name' => 'max',
        'parameters' => 
        array (
          'date' => 
          array (
            'name' => 'date',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 411,
                'endLine' => 411,
                'startTokenPos' => 1379,
                'startFilePos' => 12029,
                'endTokenPos' => 1379,
                'endFilePos' => 12032,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 411,
            'endLine' => 411,
            'startColumn' => 25,
            'endColumn' => 36,
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
 * Get the maximum instance between a given instance (default now) and the current instance.
 *
 * @param \\Carbon\\Carbon|\\DateTimeInterface|mixed $date
 *
 * @return static
 */',
        'startLine' => 411,
        'endLine' => 416,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'maximum' => 
      array (
        'name' => 'maximum',
        'parameters' => 
        array (
          'date' => 
          array (
            'name' => 'date',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 427,
                'endLine' => 427,
                'startTokenPos' => 1428,
                'startFilePos' => 12413,
                'endTokenPos' => 1428,
                'endFilePos' => 12416,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 427,
            'endLine' => 427,
            'startColumn' => 29,
            'endColumn' => 40,
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
 * Get the maximum instance between a given instance (default now) and the current instance.
 *
 * @param \\Carbon\\Carbon|\\DateTimeInterface|mixed $date
 *
 * @see max()
 *
 * @return static
 */',
        'startLine' => 427,
        'endLine' => 430,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'modify' => 
      array (
        'name' => 'modify',
        'parameters' => 
        array (
          'modify' => 
          array (
            'name' => 'modify',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 440,
            'endLine' => 440,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'ReturnTypeWillChange',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Calls \\DateTime::modify if mutable or \\DateTimeImmutable::modify else.
 *
 * @see https://php.net/manual/en/datetime.modify.php
 *
 * @return static
 */',
        'startLine' => 439,
        'endLine' => 444,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
        'aliasName' => NULL,
      ),
      'change' => 
      array (
        'name' => 'change',
        'parameters' => 
        array (
          'modifier' => 
          array (
            'name' => 'modifier',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 460,
            'endLine' => 460,
            'startColumn' => 28,
            'endColumn' => 36,
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
 * Similar to native modify() method of DateTime but can handle more grammars.
 *
 * @example
 * ```
 * echo Carbon::now()->change(\'next 2pm\');
 * ```
 *
 * @link https://php.net/manual/en/datetime.modify.php
 *
 * @param string $modifier
 *
 * @return static
 */',
        'startLine' => 460,
        'endLine' => 475,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Modifiers',
        'implementingClassName' => 'Carbon\\Traits\\Modifiers',
        'currentClassName' => 'Carbon\\Traits\\Modifiers',
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