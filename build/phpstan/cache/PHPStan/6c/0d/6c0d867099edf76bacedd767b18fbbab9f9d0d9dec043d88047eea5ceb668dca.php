<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../nesbot/carbon/src/Carbon/Traits/Week.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Carbon\Traits\Week
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-4383ba3a1e433349741853035ab0996c59c5227a51a657725389b92831183198-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Carbon\\Traits\\Week',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../nesbot/carbon/src/Carbon/Traits/Week.php',
      ),
    ),
    'namespace' => 'Carbon\\Traits',
    'name' => 'Carbon\\Traits\\Week',
    'shortName' => 'Week',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Trait Week.
 *
 * week and ISO week number, year and count in year.
 *
 * Depends on the following properties:
 *
 * @property int $daysInYear
 * @property int $dayOfWeek
 * @property int $dayOfYear
 * @property int $year
 *
 * Depends on the following methods:
 *
 * @method static addWeeks(int $weeks = 1)
 * @method static copy()
 * @method static dayOfYear(int $dayOfYear)
 * @method string getTranslationMessage(string $key, ?string $locale = null, ?string $default = null, $translator = null)
 * @method static next(int|string $modifier = null)
 * @method static startOfWeek(int $day = null)
 * @method static subWeeks(int $weeks = 1)
 * @method static year(int $year = null)
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 41,
    'endLine' => 223,
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
      'isoWeekYear' => 
      array (
        'name' => 'isoWeekYear',
        'parameters' => 
        array (
          'year' => 
          array (
            'name' => 'year',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 54,
                'endLine' => 54,
                'startTokenPos' => 42,
                'startFilePos' => 1564,
                'endTokenPos' => 42,
                'endFilePos' => 1567,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 54,
                'endLine' => 54,
                'startTokenPos' => 49,
                'startFilePos' => 1583,
                'endTokenPos' => 49,
                'endFilePos' => 1586,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 47,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'dayOfYear' => 
          array (
            'name' => 'dayOfYear',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 54,
                'endLine' => 54,
                'startTokenPos' => 56,
                'startFilePos' => 1602,
                'endTokenPos' => 56,
                'endFilePos' => 1605,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 66,
            'endColumn' => 82,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set/get the week number of year using given first day of week and first
 * day of year included in the first week. Or use ISO format if no settings
 * given.
 *
 * @param int|null $year      if null, act as a getter, if not null, set the year and return current instance.
 * @param int|null $dayOfWeek first date of week from 0 (Sunday) to 6 (Saturday)
 * @param int|null $dayOfYear first day of year included in the week #1
 *
 * @return int|static
 */',
        'startLine' => 54,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Week',
        'implementingClassName' => 'Carbon\\Traits\\Week',
        'currentClassName' => 'Carbon\\Traits\\Week',
        'aliasName' => NULL,
      ),
      'weekYear' => 
      array (
        'name' => 'weekYear',
        'parameters' => 
        array (
          'year' => 
          array (
            'name' => 'year',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 74,
                'endLine' => 74,
                'startTokenPos' => 106,
                'startFilePos' => 2327,
                'endTokenPos' => 106,
                'endFilePos' => 2330,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 74,
            'endLine' => 74,
            'startColumn' => 30,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 74,
                'endLine' => 74,
                'startTokenPos' => 113,
                'startFilePos' => 2346,
                'endTokenPos' => 113,
                'endFilePos' => 2349,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 74,
            'endLine' => 74,
            'startColumn' => 44,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'dayOfYear' => 
          array (
            'name' => 'dayOfYear',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 74,
                'endLine' => 74,
                'startTokenPos' => 120,
                'startFilePos' => 2365,
                'endTokenPos' => 120,
                'endFilePos' => 2368,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 74,
            'endLine' => 74,
            'startColumn' => 63,
            'endColumn' => 79,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set/get the week number of year using given first day of week and first
 * day of year included in the first week. Or use US format if no settings
 * given (Sunday / Jan 6).
 *
 * @param int|null $year      if null, act as a getter, if not null, set the year and return current instance.
 * @param int|null $dayOfWeek first date of week from 0 (Sunday) to 6 (Saturday)
 * @param int|null $dayOfYear first day of year included in the week #1
 *
 * @return int|static
 */',
        'startLine' => 74,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Week',
        'implementingClassName' => 'Carbon\\Traits\\Week',
        'currentClassName' => 'Carbon\\Traits\\Week',
        'aliasName' => NULL,
      ),
      'isoWeeksInYear' => 
      array (
        'name' => 'isoWeeksInYear',
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
                'startLine' => 134,
                'endLine' => 134,
                'startTokenPos' => 561,
                'startFilePos' => 4468,
                'endTokenPos' => 561,
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
            'startLine' => 134,
            'endLine' => 134,
            'startColumn' => 36,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'dayOfYear' => 
          array (
            'name' => 'dayOfYear',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 134,
                'endLine' => 134,
                'startTokenPos' => 568,
                'startFilePos' => 4487,
                'endTokenPos' => 568,
                'endFilePos' => 4490,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 134,
            'endLine' => 134,
            'startColumn' => 55,
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
 * Get the number of weeks of the current week-year using given first day of week and first
 * day of year included in the first week. Or use ISO format if no settings
 * given.
 *
 * @param int|null $dayOfWeek first date of week from 0 (Sunday) to 6 (Saturday)
 * @param int|null $dayOfYear first day of year included in the week #1
 *
 * @return int
 */',
        'startLine' => 134,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Week',
        'implementingClassName' => 'Carbon\\Traits\\Week',
        'currentClassName' => 'Carbon\\Traits\\Week',
        'aliasName' => NULL,
      ),
      'weeksInYear' => 
      array (
        'name' => 'weeksInYear',
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
                'startLine' => 152,
                'endLine' => 152,
                'startTokenPos' => 615,
                'startFilePos' => 5099,
                'endTokenPos' => 615,
                'endFilePos' => 5102,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 33,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'dayOfYear' => 
          array (
            'name' => 'dayOfYear',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 152,
                'endLine' => 152,
                'startTokenPos' => 622,
                'startFilePos' => 5118,
                'endTokenPos' => 622,
                'endFilePos' => 5121,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 52,
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
 * Get the number of weeks of the current week-year using given first day of week and first
 * day of year included in the first week. Or use US format if no settings
 * given (Sunday / Jan 6).
 *
 * @param int|null $dayOfWeek first date of week from 0 (Sunday) to 6 (Saturday)
 * @param int|null $dayOfYear first day of year included in the week #1
 *
 * @return int
 */',
        'startLine' => 152,
        'endLine' => 169,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Week',
        'implementingClassName' => 'Carbon\\Traits\\Week',
        'currentClassName' => 'Carbon\\Traits\\Week',
        'aliasName' => NULL,
      ),
      'week' => 
      array (
        'name' => 'week',
        'parameters' => 
        array (
          'week' => 
          array (
            'name' => 'week',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 182,
                'endLine' => 182,
                'startTokenPos' => 828,
                'startFilePos' => 6254,
                'endTokenPos' => 828,
                'endFilePos' => 6257,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 182,
            'endLine' => 182,
            'startColumn' => 26,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 182,
                'endLine' => 182,
                'startTokenPos' => 835,
                'startFilePos' => 6273,
                'endTokenPos' => 835,
                'endFilePos' => 6276,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 182,
            'endLine' => 182,
            'startColumn' => 40,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'dayOfYear' => 
          array (
            'name' => 'dayOfYear',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 182,
                'endLine' => 182,
                'startTokenPos' => 842,
                'startFilePos' => 6292,
                'endTokenPos' => 842,
                'endFilePos' => 6295,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 182,
            'endLine' => 182,
            'startColumn' => 59,
            'endColumn' => 75,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get/set the week number using given first day of week and first
 * day of year included in the first week. Or use US format if no settings
 * given (Sunday / Jan 6).
 *
 * @param int|null $week
 * @param int|null $dayOfWeek
 * @param int|null $dayOfYear
 *
 * @return int|static
 */',
        'startLine' => 182,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Week',
        'implementingClassName' => 'Carbon\\Traits\\Week',
        'currentClassName' => 'Carbon\\Traits\\Week',
        'aliasName' => NULL,
      ),
      'isoWeek' => 
      array (
        'name' => 'isoWeek',
        'parameters' => 
        array (
          'week' => 
          array (
            'name' => 'week',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 215,
                'endLine' => 215,
                'startTokenPos' => 1090,
                'startFilePos' => 7524,
                'endTokenPos' => 1090,
                'endFilePos' => 7527,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 215,
            'endLine' => 215,
            'startColumn' => 29,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 215,
                'endLine' => 215,
                'startTokenPos' => 1097,
                'startFilePos' => 7543,
                'endTokenPos' => 1097,
                'endFilePos' => 7546,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 215,
            'endLine' => 215,
            'startColumn' => 43,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'dayOfYear' => 
          array (
            'name' => 'dayOfYear',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 215,
                'endLine' => 215,
                'startTokenPos' => 1104,
                'startFilePos' => 7562,
                'endTokenPos' => 1104,
                'endFilePos' => 7565,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 215,
            'endLine' => 215,
            'startColumn' => 62,
            'endColumn' => 78,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get/set the week number using given first day of week and first
 * day of year included in the first week. Or use ISO format if no settings
 * given.
 *
 * @param int|null $week
 * @param int|null $dayOfWeek
 * @param int|null $dayOfYear
 *
 * @return int|static
 */',
        'startLine' => 215,
        'endLine' => 222,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon\\Traits',
        'declaringClassName' => 'Carbon\\Traits\\Week',
        'implementingClassName' => 'Carbon\\Traits\\Week',
        'currentClassName' => 'Carbon\\Traits\\Week',
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