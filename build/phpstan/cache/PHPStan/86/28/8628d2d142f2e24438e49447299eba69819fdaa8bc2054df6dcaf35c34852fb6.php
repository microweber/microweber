<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../nesbot/carbon/src/Carbon/CarbonInterval.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Carbon\CarbonInterval
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-35f7b01ce1604762b8d7c8affbacae306fa438eacaadb6743e15960fcf9de0fb-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Carbon\\CarbonInterval',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../nesbot/carbon/src/Carbon/CarbonInterval.php',
      ),
    ),
    'namespace' => 'Carbon',
    'name' => 'Carbon\\CarbonInterval',
    'shortName' => 'CarbonInterval',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A simple API extension for DateInterval.
 * The implementation provides helpers to handle weeks but only days are saved.
 * Weeks are calculated based on the total days of the current instance.
 *
 * @property int $years Year component of the current interval. (For P2Y6M, the value will be 2)
 * @property int $months Month component of the current interval. (For P1Y6M10D, the value will be 6)
 * @property int $weeks Week component of the current interval calculated from the days. (For P1Y6M17D, the value will be 2)
 * @property int $dayz Day component of the current interval (weeks * 7 + days). (For P6M17DT20H, the value will be 17)
 * @property int $hours Hour component of the current interval. (For P7DT20H5M, the value will be 20)
 * @property int $minutes Minute component of the current interval. (For PT20H5M30S, the value will be 5)
 * @property int $seconds Second component of the current interval. (CarbonInterval::minutes(2)->seconds(34)->microseconds(567_890)->seconds = 34)
 * @property int $milliseconds Milliseconds component of the current interval. (CarbonInterval::seconds(34)->microseconds(567_890)->milliseconds = 567)
 * @property int $microseconds Microseconds component of the current interval. (CarbonInterval::seconds(34)->microseconds(567_890)->microseconds = 567_890)
 * @property int $microExcludeMilli Remaining microseconds without the milliseconds.
 * @property int $dayzExcludeWeeks Total days remaining in the final week of the current instance (days % 7).
 * @property int $daysExcludeWeeks alias of dayzExcludeWeeks
 * @property-read float $totalYears Number of years equivalent to the interval. (For P1Y6M, the value will be 1.5)
 * @property-read float $totalMonths Number of months equivalent to the interval. (For P1Y6M10D, the value will be ~12.357)
 * @property-read float $totalWeeks Number of weeks equivalent to the interval. (For P6M17DT20H, the value will be ~26.548)
 * @property-read float $totalDays Number of days equivalent to the interval. (For P17DT20H, the value will be ~17.833)
 * @property-read float $totalDayz Alias for totalDays.
 * @property-read float $totalHours Number of hours equivalent to the interval. (For P1DT20H5M, the value will be ~44.083)
 * @property-read float $totalMinutes Number of minutes equivalent to the interval. (For PT20H5M30S, the value will be 1205.5)
 * @property-read float $totalSeconds Number of seconds equivalent to the interval. (CarbonInterval::minutes(2)->seconds(34)->microseconds(567_890)->totalSeconds = 154.567_890)
 * @property-read float $totalMilliseconds Number of milliseconds equivalent to the interval. (CarbonInterval::seconds(34)->microseconds(567_890)->totalMilliseconds = 34567.890)
 * @property-read float $totalMicroseconds Number of microseconds equivalent to the interval. (CarbonInterval::seconds(34)->microseconds(567_890)->totalMicroseconds = 34567890)
 * @property-read string $locale locale of the current instance
 *
 * @method static CarbonInterval years($years = 1) Create instance specifying a number of years or modify the number of years if called on an instance.
 * @method static CarbonInterval year($years = 1) Alias for years()
 * @method static CarbonInterval months($months = 1) Create instance specifying a number of months or modify the number of months if called on an instance.
 * @method static CarbonInterval month($months = 1) Alias for months()
 * @method static CarbonInterval weeks($weeks = 1) Create instance specifying a number of weeks or modify the number of weeks if called on an instance.
 * @method static CarbonInterval week($weeks = 1) Alias for weeks()
 * @method static CarbonInterval days($days = 1) Create instance specifying a number of days or modify the number of days if called on an instance.
 * @method static CarbonInterval dayz($days = 1) Alias for days()
 * @method static CarbonInterval daysExcludeWeeks($days = 1) Create instance specifying a number of days or modify the number of days (keeping the current number of weeks) if called on an instance.
 * @method static CarbonInterval dayzExcludeWeeks($days = 1) Alias for daysExcludeWeeks()
 * @method static CarbonInterval day($days = 1) Alias for days()
 * @method static CarbonInterval hours($hours = 1) Create instance specifying a number of hours or modify the number of hours if called on an instance.
 * @method static CarbonInterval hour($hours = 1) Alias for hours()
 * @method static CarbonInterval minutes($minutes = 1) Create instance specifying a number of minutes or modify the number of minutes if called on an instance.
 * @method static CarbonInterval minute($minutes = 1) Alias for minutes()
 * @method static CarbonInterval seconds($seconds = 1) Create instance specifying a number of seconds or modify the number of seconds if called on an instance.
 * @method static CarbonInterval second($seconds = 1) Alias for seconds()
 * @method static CarbonInterval milliseconds($milliseconds = 1) Create instance specifying a number of milliseconds or modify the number of milliseconds if called on an instance.
 * @method static CarbonInterval millisecond($milliseconds = 1) Alias for milliseconds()
 * @method static CarbonInterval microseconds($microseconds = 1) Create instance specifying a number of microseconds or modify the number of microseconds if called on an instance.
 * @method static CarbonInterval microsecond($microseconds = 1) Alias for microseconds()
 * @method $this addYears(int $years) Add given number of years to the current interval
 * @method $this subYears(int $years) Subtract given number of years to the current interval
 * @method $this addMonths(int $months) Add given number of months to the current interval
 * @method $this subMonths(int $months) Subtract given number of months to the current interval
 * @method $this addWeeks(int|float $weeks) Add given number of weeks to the current interval
 * @method $this subWeeks(int|float $weeks) Subtract given number of weeks to the current interval
 * @method $this addDays(int|float $days) Add given number of days to the current interval
 * @method $this subDays(int|float $days) Subtract given number of days to the current interval
 * @method $this addHours(int|float $hours) Add given number of hours to the current interval
 * @method $this subHours(int|float $hours) Subtract given number of hours to the current interval
 * @method $this addMinutes(int|float $minutes) Add given number of minutes to the current interval
 * @method $this subMinutes(int|float $minutes) Subtract given number of minutes to the current interval
 * @method $this addSeconds(int|float $seconds) Add given number of seconds to the current interval
 * @method $this subSeconds(int|float $seconds) Subtract given number of seconds to the current interval
 * @method $this addMilliseconds(int|float $milliseconds) Add given number of milliseconds to the current interval
 * @method $this subMilliseconds(int|float $milliseconds) Subtract given number of milliseconds to the current interval
 * @method $this addMicroseconds(int|float $microseconds) Add given number of microseconds to the current interval
 * @method $this subMicroseconds(int|float $microseconds) Subtract given number of microseconds to the current interval
 * @method $this roundYear(int|float $precision = 1, string $function = "round") Round the current instance year with given precision using the given function.
 * @method $this roundYears(int|float $precision = 1, string $function = "round") Round the current instance year with given precision using the given function.
 * @method $this floorYear(int|float $precision = 1) Truncate the current instance year with given precision.
 * @method $this floorYears(int|float $precision = 1) Truncate the current instance year with given precision.
 * @method $this ceilYear(int|float $precision = 1) Ceil the current instance year with given precision.
 * @method $this ceilYears(int|float $precision = 1) Ceil the current instance year with given precision.
 * @method $this roundMonth(int|float $precision = 1, string $function = "round") Round the current instance month with given precision using the given function.
 * @method $this roundMonths(int|float $precision = 1, string $function = "round") Round the current instance month with given precision using the given function.
 * @method $this floorMonth(int|float $precision = 1) Truncate the current instance month with given precision.
 * @method $this floorMonths(int|float $precision = 1) Truncate the current instance month with given precision.
 * @method $this ceilMonth(int|float $precision = 1) Ceil the current instance month with given precision.
 * @method $this ceilMonths(int|float $precision = 1) Ceil the current instance month with given precision.
 * @method $this roundWeek(int|float $precision = 1, string $function = "round") Round the current instance day with given precision using the given function.
 * @method $this roundWeeks(int|float $precision = 1, string $function = "round") Round the current instance day with given precision using the given function.
 * @method $this floorWeek(int|float $precision = 1) Truncate the current instance day with given precision.
 * @method $this floorWeeks(int|float $precision = 1) Truncate the current instance day with given precision.
 * @method $this ceilWeek(int|float $precision = 1) Ceil the current instance day with given precision.
 * @method $this ceilWeeks(int|float $precision = 1) Ceil the current instance day with given precision.
 * @method $this roundDay(int|float $precision = 1, string $function = "round") Round the current instance day with given precision using the given function.
 * @method $this roundDays(int|float $precision = 1, string $function = "round") Round the current instance day with given precision using the given function.
 * @method $this floorDay(int|float $precision = 1) Truncate the current instance day with given precision.
 * @method $this floorDays(int|float $precision = 1) Truncate the current instance day with given precision.
 * @method $this ceilDay(int|float $precision = 1) Ceil the current instance day with given precision.
 * @method $this ceilDays(int|float $precision = 1) Ceil the current instance day with given precision.
 * @method $this roundHour(int|float $precision = 1, string $function = "round") Round the current instance hour with given precision using the given function.
 * @method $this roundHours(int|float $precision = 1, string $function = "round") Round the current instance hour with given precision using the given function.
 * @method $this floorHour(int|float $precision = 1) Truncate the current instance hour with given precision.
 * @method $this floorHours(int|float $precision = 1) Truncate the current instance hour with given precision.
 * @method $this ceilHour(int|float $precision = 1) Ceil the current instance hour with given precision.
 * @method $this ceilHours(int|float $precision = 1) Ceil the current instance hour with given precision.
 * @method $this roundMinute(int|float $precision = 1, string $function = "round") Round the current instance minute with given precision using the given function.
 * @method $this roundMinutes(int|float $precision = 1, string $function = "round") Round the current instance minute with given precision using the given function.
 * @method $this floorMinute(int|float $precision = 1) Truncate the current instance minute with given precision.
 * @method $this floorMinutes(int|float $precision = 1) Truncate the current instance minute with given precision.
 * @method $this ceilMinute(int|float $precision = 1) Ceil the current instance minute with given precision.
 * @method $this ceilMinutes(int|float $precision = 1) Ceil the current instance minute with given precision.
 * @method $this roundSecond(int|float $precision = 1, string $function = "round") Round the current instance second with given precision using the given function.
 * @method $this roundSeconds(int|float $precision = 1, string $function = "round") Round the current instance second with given precision using the given function.
 * @method $this floorSecond(int|float $precision = 1) Truncate the current instance second with given precision.
 * @method $this floorSeconds(int|float $precision = 1) Truncate the current instance second with given precision.
 * @method $this ceilSecond(int|float $precision = 1) Ceil the current instance second with given precision.
 * @method $this ceilSeconds(int|float $precision = 1) Ceil the current instance second with given precision.
 * @method $this roundMillennium(int|float $precision = 1, string $function = "round") Round the current instance millennium with given precision using the given function.
 * @method $this roundMillennia(int|float $precision = 1, string $function = "round") Round the current instance millennium with given precision using the given function.
 * @method $this floorMillennium(int|float $precision = 1) Truncate the current instance millennium with given precision.
 * @method $this floorMillennia(int|float $precision = 1) Truncate the current instance millennium with given precision.
 * @method $this ceilMillennium(int|float $precision = 1) Ceil the current instance millennium with given precision.
 * @method $this ceilMillennia(int|float $precision = 1) Ceil the current instance millennium with given precision.
 * @method $this roundCentury(int|float $precision = 1, string $function = "round") Round the current instance century with given precision using the given function.
 * @method $this roundCenturies(int|float $precision = 1, string $function = "round") Round the current instance century with given precision using the given function.
 * @method $this floorCentury(int|float $precision = 1) Truncate the current instance century with given precision.
 * @method $this floorCenturies(int|float $precision = 1) Truncate the current instance century with given precision.
 * @method $this ceilCentury(int|float $precision = 1) Ceil the current instance century with given precision.
 * @method $this ceilCenturies(int|float $precision = 1) Ceil the current instance century with given precision.
 * @method $this roundDecade(int|float $precision = 1, string $function = "round") Round the current instance decade with given precision using the given function.
 * @method $this roundDecades(int|float $precision = 1, string $function = "round") Round the current instance decade with given precision using the given function.
 * @method $this floorDecade(int|float $precision = 1) Truncate the current instance decade with given precision.
 * @method $this floorDecades(int|float $precision = 1) Truncate the current instance decade with given precision.
 * @method $this ceilDecade(int|float $precision = 1) Ceil the current instance decade with given precision.
 * @method $this ceilDecades(int|float $precision = 1) Ceil the current instance decade with given precision.
 * @method $this roundQuarter(int|float $precision = 1, string $function = "round") Round the current instance quarter with given precision using the given function.
 * @method $this roundQuarters(int|float $precision = 1, string $function = "round") Round the current instance quarter with given precision using the given function.
 * @method $this floorQuarter(int|float $precision = 1) Truncate the current instance quarter with given precision.
 * @method $this floorQuarters(int|float $precision = 1) Truncate the current instance quarter with given precision.
 * @method $this ceilQuarter(int|float $precision = 1) Ceil the current instance quarter with given precision.
 * @method $this ceilQuarters(int|float $precision = 1) Ceil the current instance quarter with given precision.
 * @method $this roundMillisecond(int|float $precision = 1, string $function = "round") Round the current instance millisecond with given precision using the given function.
 * @method $this roundMilliseconds(int|float $precision = 1, string $function = "round") Round the current instance millisecond with given precision using the given function.
 * @method $this floorMillisecond(int|float $precision = 1) Truncate the current instance millisecond with given precision.
 * @method $this floorMilliseconds(int|float $precision = 1) Truncate the current instance millisecond with given precision.
 * @method $this ceilMillisecond(int|float $precision = 1) Ceil the current instance millisecond with given precision.
 * @method $this ceilMilliseconds(int|float $precision = 1) Ceil the current instance millisecond with given precision.
 * @method $this roundMicrosecond(int|float $precision = 1, string $function = "round") Round the current instance microsecond with given precision using the given function.
 * @method $this roundMicroseconds(int|float $precision = 1, string $function = "round") Round the current instance microsecond with given precision using the given function.
 * @method $this floorMicrosecond(int|float $precision = 1) Truncate the current instance microsecond with given precision.
 * @method $this floorMicroseconds(int|float $precision = 1) Truncate the current instance microsecond with given precision.
 * @method $this ceilMicrosecond(int|float $precision = 1) Ceil the current instance microsecond with given precision.
 * @method $this ceilMicroseconds(int|float $precision = 1) Ceil the current instance microsecond with given precision.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 195,
    'endLine' => 3573,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'DateInterval',
    'implementsClassNames' => 
    array (
      0 => 'Carbon\\CarbonConverterInterface',
      1 => 'Carbon\\Constants\\UnitValue',
    ),
    'traitClassNames' => 
    array (
      0 => 'Carbon\\Traits\\LocalFactory',
      1 => 'Carbon\\Traits\\IntervalRounding',
      2 => 'Carbon\\Traits\\IntervalStep',
      3 => 'Carbon\\Traits\\MagicParameter',
      4 => 'Carbon\\Traits\\Mixin',
      5 => 'Carbon\\Traits\\Options',
      6 => 'Carbon\\Traits\\ToStringFormat',
    ),
    'immediateConstants' => 
    array (
      'NO_LIMIT' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'NO_LIMIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '-1',
          'attributes' => 
          array (
            'startLine' => 212,
            'endLine' => 212,
            'startTokenPos' => 248,
            'startFilePos' => 19143,
            'endTokenPos' => 249,
            'endFilePos' => 19144,
          ),
        ),
        'docComment' => '/**
 * Unlimited parts for forHumans() method.
 *
 * INF constant can be used instead.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 212,
        'endLine' => 212,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'POSITIVE' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'POSITIVE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '1',
          'attributes' => 
          array (
            'startLine' => 214,
            'endLine' => 214,
            'startTokenPos' => 260,
            'startFilePos' => 19176,
            'endTokenPos' => 260,
            'endFilePos' => 19176,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 214,
        'endLine' => 214,
        'startColumn' => 5,
        'endColumn' => 30,
      ),
      'NEGATIVE' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'NEGATIVE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '-1',
          'attributes' => 
          array (
            'startLine' => 215,
            'endLine' => 215,
            'startTokenPos' => 271,
            'startFilePos' => 19207,
            'endTokenPos' => 272,
            'endFilePos' => 19208,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 215,
        'endLine' => 215,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'PERIOD_PREFIX' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'PERIOD_PREFIX',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'P\'',
          'attributes' => 
          array (
            'startLine' => 220,
            'endLine' => 220,
            'startTokenPos' => 285,
            'startFilePos' => 19301,
            'endTokenPos' => 285,
            'endFilePos' => 19303,
          ),
        ),
        'docComment' => '/**
 * Interval spec period designators
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 220,
        'endLine' => 220,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'PERIOD_YEARS' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'PERIOD_YEARS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'Y\'',
          'attributes' => 
          array (
            'startLine' => 221,
            'endLine' => 221,
            'startTokenPos' => 296,
            'startFilePos' => 19338,
            'endTokenPos' => 296,
            'endFilePos' => 19340,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 221,
        'endLine' => 221,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'PERIOD_MONTHS' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'PERIOD_MONTHS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'M\'',
          'attributes' => 
          array (
            'startLine' => 222,
            'endLine' => 222,
            'startTokenPos' => 307,
            'startFilePos' => 19376,
            'endTokenPos' => 307,
            'endFilePos' => 19378,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 222,
        'endLine' => 222,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'PERIOD_DAYS' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'PERIOD_DAYS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'D\'',
          'attributes' => 
          array (
            'startLine' => 223,
            'endLine' => 223,
            'startTokenPos' => 318,
            'startFilePos' => 19412,
            'endTokenPos' => 318,
            'endFilePos' => 19414,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 223,
        'endLine' => 223,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'PERIOD_TIME_PREFIX' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'PERIOD_TIME_PREFIX',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'T\'',
          'attributes' => 
          array (
            'startLine' => 224,
            'endLine' => 224,
            'startTokenPos' => 329,
            'startFilePos' => 19455,
            'endTokenPos' => 329,
            'endFilePos' => 19457,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 224,
        'endLine' => 224,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
      'PERIOD_HOURS' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'PERIOD_HOURS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'H\'',
          'attributes' => 
          array (
            'startLine' => 225,
            'endLine' => 225,
            'startTokenPos' => 340,
            'startFilePos' => 19492,
            'endTokenPos' => 340,
            'endFilePos' => 19494,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 225,
        'endLine' => 225,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'PERIOD_MINUTES' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'PERIOD_MINUTES',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'M\'',
          'attributes' => 
          array (
            'startLine' => 226,
            'endLine' => 226,
            'startTokenPos' => 351,
            'startFilePos' => 19531,
            'endTokenPos' => 351,
            'endFilePos' => 19533,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 226,
        'endLine' => 226,
        'startColumn' => 5,
        'endColumn' => 38,
      ),
      'PERIOD_SECONDS' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'PERIOD_SECONDS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'S\'',
          'attributes' => 
          array (
            'startLine' => 227,
            'endLine' => 227,
            'startTokenPos' => 362,
            'startFilePos' => 19570,
            'endTokenPos' => 362,
            'endFilePos' => 19572,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 227,
        'endLine' => 227,
        'startColumn' => 5,
        'endColumn' => 38,
      ),
      'SPECIAL_TRANSLATIONS' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'SPECIAL_TRANSLATIONS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[1 => [\'option\' => \\Carbon\\CarbonInterface::ONE_DAY_WORDS, \'future\' => \'diff_tomorrow\', \'past\' => \'diff_yesterday\'], 2 => [\'option\' => \\Carbon\\CarbonInterface::TWO_DAY_WORDS, \'future\' => \'diff_after_tomorrow\', \'past\' => \'diff_before_yesterday\']]',
          'attributes' => 
          array (
            'startLine' => 229,
            'endLine' => 240,
            'startTokenPos' => 373,
            'startFilePos' => 19616,
            'endTokenPos' => 439,
            'endFilePos' => 19961,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 229,
        'endLine' => 240,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
      'cascadeFactors' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'cascadeFactors',
        'modifiers' => 18,
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
                  'name' => 'array',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 242,
            'endLine' => 242,
            'startTokenPos' => 453,
            'startFilePos' => 20011,
            'endTokenPos' => 453,
            'endFilePos' => 20014,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 242,
        'endLine' => 242,
        'startColumn' => 5,
        'endColumn' => 51,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'formats' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'formats',
        'modifiers' => 18,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[\'y\' => \'y\', \'Y\' => \'y\', \'o\' => \'y\', \'m\' => \'m\', \'n\' => \'m\', \'W\' => \'weeks\', \'d\' => \'d\', \'j\' => \'d\', \'z\' => \'d\', \'h\' => \'h\', \'g\' => \'h\', \'H\' => \'h\', \'G\' => \'h\', \'i\' => \'i\', \'s\' => \'s\', \'u\' => \'micro\', \'v\' => \'milli\']',
          'attributes' => 
          array (
            'startLine' => 244,
            'endLine' => 262,
            'startTokenPos' => 466,
            'startFilePos' => 20056,
            'endTokenPos' => 587,
            'endFilePos' => 20414,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 244,
        'endLine' => 262,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'flipCascadeFactors' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'flipCascadeFactors',
        'modifiers' => 20,
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
                  'name' => 'array',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 264,
            'endLine' => 264,
            'startTokenPos' => 601,
            'startFilePos' => 20466,
            'endTokenPos' => 601,
            'endFilePos' => 20469,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 264,
        'endLine' => 264,
        'startColumn' => 5,
        'endColumn' => 53,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'floatSettersEnabled' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'floatSettersEnabled',
        'modifiers' => 20,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 266,
            'endLine' => 266,
            'startTokenPos' => 614,
            'startFilePos' => 20520,
            'endTokenPos' => 614,
            'endFilePos' => 20524,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 266,
        'endLine' => 266,
        'startColumn' => 5,
        'endColumn' => 53,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'macros' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'macros',
        'modifiers' => 18,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 271,
            'endLine' => 271,
            'startTokenPos' => 629,
            'startFilePos' => 20611,
            'endTokenPos' => 630,
            'endFilePos' => 20612,
          ),
        ),
        'docComment' => '/**
 * The registered macros.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 271,
        'endLine' => 271,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'timezoneSetting' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'timezoneSetting',
        'modifiers' => 2,
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
                  'name' => 'DateTimeZone',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              2 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'int',
                  'isIdentifier' => true,
                ),
              ),
              3 => 
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 276,
            'endLine' => 276,
            'startTokenPos' => 649,
            'startFilePos' => 20741,
            'endTokenPos' => 649,
            'endFilePos' => 20744,
          ),
        ),
        'docComment' => '/**
 * Timezone handler for settings() method.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 276,
        'endLine' => 276,
        'startColumn' => 5,
        'endColumn' => 67,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'originalInput' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'originalInput',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 281,
            'endLine' => 281,
            'startTokenPos' => 662,
            'startFilePos' => 20847,
            'endTokenPos' => 662,
            'endFilePos' => 20850,
          ),
        ),
        'docComment' => '/**
 * The input used to create the interval.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 281,
        'endLine' => 281,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'startDate' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'startDate',
        'modifiers' => 2,
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
                  'name' => 'Carbon\\CarbonInterface',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 286,
            'endLine' => 286,
            'startTokenPos' => 676,
            'startFilePos' => 20991,
            'endTokenPos' => 676,
            'endFilePos' => 20994,
          ),
        ),
        'docComment' => '/**
 * Start date if interval was created from a difference between 2 dates.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 286,
        'endLine' => 286,
        'startColumn' => 5,
        'endColumn' => 49,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'endDate' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'endDate',
        'modifiers' => 2,
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
                  'name' => 'Carbon\\CarbonInterface',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 291,
            'endLine' => 291,
            'startTokenPos' => 690,
            'startFilePos' => 21131,
            'endTokenPos' => 690,
            'endFilePos' => 21134,
          ),
        ),
        'docComment' => '/**
 * End date if interval was created from a difference between 2 dates.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 291,
        'endLine' => 291,
        'startColumn' => 5,
        'endColumn' => 47,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'rawInterval' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'rawInterval',
        'modifiers' => 2,
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
                  'name' => 'DateInterval',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 296,
            'endLine' => 296,
            'startTokenPos' => 704,
            'startFilePos' => 21272,
            'endTokenPos' => 704,
            'endFilePos' => 21275,
          ),
        ),
        'docComment' => '/**
 * End date if interval was created from a difference between 2 dates.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 296,
        'endLine' => 296,
        'startColumn' => 5,
        'endColumn' => 48,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'absolute' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'absolute',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 301,
            'endLine' => 301,
            'startTokenPos' => 717,
            'startFilePos' => 21398,
            'endTokenPos' => 717,
            'endFilePos' => 21402,
          ),
        ),
        'docComment' => '/**
 * Flag if the interval was made from a diff with absolute flag on.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 301,
        'endLine' => 301,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'initialValues' => 
      array (
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'name' => 'initialValues',
        'modifiers' => 2,
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
                  'name' => 'array',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 303,
            'endLine' => 303,
            'startTokenPos' => 729,
            'startFilePos' => 21444,
            'endTokenPos' => 729,
            'endFilePos' => 21447,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 303,
        'endLine' => 303,
        'startColumn' => 5,
        'endColumn' => 43,
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
      'setTimezone' => 
      array (
        'name' => 'setTimezone',
        'parameters' => 
        array (
          'timezone' => 
          array (
            'name' => 'timezone',
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
                      'name' => 'DateTimeZone',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
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
            'startLine' => 308,
            'endLine' => 308,
            'startColumn' => 33,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the instance\'s timezone from a string or object.
 */',
        'startLine' => 308,
        'endLine' => 328,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'shiftTimezone' => 
      array (
        'name' => 'shiftTimezone',
        'parameters' => 
        array (
          'timezone' => 
          array (
            'name' => 'timezone',
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
                      'name' => 'DateTimeZone',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
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
            'startLine' => 333,
            'endLine' => 333,
            'startColumn' => 35,
            'endColumn' => 67,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the instance\'s timezone from a string or object and add/subtract the offset difference.
 */',
        'startLine' => 333,
        'endLine' => 353,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getCascadeFactors' => 
      array (
        'name' => 'getCascadeFactors',
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
 * Mapping of units and factors for cascading.
 *
 * Should only be modified by changing the factors or referenced constants.
 */',
        'startLine' => 360,
        'endLine' => 363,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getDefaultCascadeFactors' => 
      array (
        'name' => 'getDefaultCascadeFactors',
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
        'docComment' => NULL,
        'startLine' => 365,
        'endLine' => 377,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'setCascadeFactors' => 
      array (
        'name' => 'setCascadeFactors',
        'parameters' => 
        array (
          'cascadeFactors' => 
          array (
            'name' => 'cascadeFactors',
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
            'startLine' => 384,
            'endLine' => 384,
            'startColumn' => 46,
            'endColumn' => 66,
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
 * Set default cascading factors for ->cascade() method.
 *
 * @param array $cascadeFactors
 */',
        'startLine' => 384,
        'endLine' => 388,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'enableFloatSetters' => 
      array (
        'name' => 'enableFloatSetters',
        'parameters' => 
        array (
          'floatSettersEnabled' => 
          array (
            'name' => 'floatSettersEnabled',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 398,
                'endLine' => 398,
                'startTokenPos' => 1218,
                'startFilePos' => 24570,
                'endTokenPos' => 1218,
                'endFilePos' => 24573,
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
            'startLine' => 398,
            'endLine' => 398,
            'startColumn' => 47,
            'endColumn' => 78,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * This option allow you to opt-in for the Carbon 3 behavior where float
 * values will no longer be cast to integer (so truncated).
 *
 * ⚠️ This settings will be applied globally, which mean your whole application
 * code including the third-party dependencies that also may use Carbon will
 * adopt the new behavior.
 */',
        'startLine' => 398,
        'endLine' => 401,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'years' => 
          array (
            'name' => 'years',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 421,
                'endLine' => 421,
                'startTokenPos' => 1255,
                'startFilePos' => 25562,
                'endTokenPos' => 1255,
                'endFilePos' => 25565,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 421,
            'endLine' => 421,
            'startColumn' => 33,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'months' => 
          array (
            'name' => 'months',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 421,
                'endLine' => 421,
                'startTokenPos' => 1262,
                'startFilePos' => 25578,
                'endTokenPos' => 1262,
                'endFilePos' => 25581,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 421,
            'endLine' => 421,
            'startColumn' => 48,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'weeks' => 
          array (
            'name' => 'weeks',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 421,
                'endLine' => 421,
                'startTokenPos' => 1269,
                'startFilePos' => 25593,
                'endTokenPos' => 1269,
                'endFilePos' => 25596,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 421,
            'endLine' => 421,
            'startColumn' => 64,
            'endColumn' => 76,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'days' => 
          array (
            'name' => 'days',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 421,
                'endLine' => 421,
                'startTokenPos' => 1276,
                'startFilePos' => 25607,
                'endTokenPos' => 1276,
                'endFilePos' => 25610,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 421,
            'endLine' => 421,
            'startColumn' => 79,
            'endColumn' => 90,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'hours' => 
          array (
            'name' => 'hours',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 421,
                'endLine' => 421,
                'startTokenPos' => 1283,
                'startFilePos' => 25622,
                'endTokenPos' => 1283,
                'endFilePos' => 25625,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 421,
            'endLine' => 421,
            'startColumn' => 93,
            'endColumn' => 105,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'minutes' => 
          array (
            'name' => 'minutes',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 421,
                'endLine' => 421,
                'startTokenPos' => 1290,
                'startFilePos' => 25639,
                'endTokenPos' => 1290,
                'endFilePos' => 25642,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 421,
            'endLine' => 421,
            'startColumn' => 108,
            'endColumn' => 122,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 421,
                'endLine' => 421,
                'startTokenPos' => 1297,
                'startFilePos' => 25656,
                'endTokenPos' => 1297,
                'endFilePos' => 25659,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 421,
            'endLine' => 421,
            'startColumn' => 125,
            'endColumn' => 139,
            'parameterIndex' => 6,
            'isOptional' => true,
          ),
          'microseconds' => 
          array (
            'name' => 'microseconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 421,
                'endLine' => 421,
                'startTokenPos' => 1304,
                'startFilePos' => 25678,
                'endTokenPos' => 1304,
                'endFilePos' => 25681,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 421,
            'endLine' => 421,
            'startColumn' => 142,
            'endColumn' => 161,
            'parameterIndex' => 7,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new CarbonInterval instance.
 *
 * @param Closure|DateInterval|string|int|null $years
 * @param int|float|null                       $months
 * @param int|float|null                       $weeks
 * @param int|float|null                       $days
 * @param int|float|null                       $hours
 * @param int|float|null                       $minutes
 * @param int|float|null                       $seconds
 * @param int|float|null                       $microseconds
 *
 * @throws Exception when the interval_spec (passed as $years) cannot be parsed as an interval.
 */',
        'startLine' => 421,
        'endLine' => 555,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getFactor' => 
      array (
        'name' => 'getFactor',
        'parameters' => 
        array (
          'source' => 
          array (
            'name' => 'source',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 565,
            'endLine' => 565,
            'startColumn' => 38,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'target' => 
          array (
            'name' => 'target',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 565,
            'endLine' => 565,
            'startColumn' => 47,
            'endColumn' => 53,
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
 * Returns the factor for a given source-to-target couple.
 *
 * @param string $source
 * @param string $target
 *
 * @return int|float|null
 */',
        'startLine' => 565,
        'endLine' => 582,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getFactorWithDefault' => 
      array (
        'name' => 'getFactorWithDefault',
        'parameters' => 
        array (
          'source' => 
          array (
            'name' => 'source',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 593,
            'endLine' => 593,
            'startColumn' => 49,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'target' => 
          array (
            'name' => 'target',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 593,
            'endLine' => 593,
            'startColumn' => 58,
            'endColumn' => 64,
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
 * Returns the factor for a given source-to-target couple if set,
 * else try to find the appropriate constant as the factor, such as Carbon::DAYS_PER_WEEK.
 *
 * @param string $source
 * @param string $target
 *
 * @return int|float|null
 */',
        'startLine' => 593,
        'endLine' => 613,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getDaysPerWeek' => 
      array (
        'name' => 'getDaysPerWeek',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns current config for days per week.
 *
 * @return int|float
 */',
        'startLine' => 620,
        'endLine' => 623,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getHoursPerDay' => 
      array (
        'name' => 'getHoursPerDay',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns current config for hours per day.
 *
 * @return int|float
 */',
        'startLine' => 630,
        'endLine' => 633,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getMinutesPerHour' => 
      array (
        'name' => 'getMinutesPerHour',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns current config for minutes per hour.
 *
 * @return int|float
 */',
        'startLine' => 640,
        'endLine' => 643,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getSecondsPerMinute' => 
      array (
        'name' => 'getSecondsPerMinute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns current config for seconds per minute.
 *
 * @return int|float
 */',
        'startLine' => 650,
        'endLine' => 653,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getMillisecondsPerSecond' => 
      array (
        'name' => 'getMillisecondsPerSecond',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns current config for microseconds per second.
 *
 * @return int|float
 */',
        'startLine' => 660,
        'endLine' => 663,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getMicrosecondsPerMillisecond' => 
      array (
        'name' => 'getMicrosecondsPerMillisecond',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns current config for microseconds per second.
 *
 * @return int|float
 */',
        'startLine' => 670,
        'endLine' => 673,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'create' => 
      array (
        'name' => 'create',
        'parameters' => 
        array (
          'years' => 
          array (
            'name' => 'years',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 694,
                'endLine' => 694,
                'startTokenPos' => 3113,
                'startFilePos' => 35384,
                'endTokenPos' => 3113,
                'endFilePos' => 35387,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 694,
            'endLine' => 694,
            'startColumn' => 35,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'months' => 
          array (
            'name' => 'months',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 694,
                'endLine' => 694,
                'startTokenPos' => 3120,
                'startFilePos' => 35400,
                'endTokenPos' => 3120,
                'endFilePos' => 35403,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 694,
            'endLine' => 694,
            'startColumn' => 50,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'weeks' => 
          array (
            'name' => 'weeks',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 694,
                'endLine' => 694,
                'startTokenPos' => 3127,
                'startFilePos' => 35415,
                'endTokenPos' => 3127,
                'endFilePos' => 35418,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 694,
            'endLine' => 694,
            'startColumn' => 66,
            'endColumn' => 78,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'days' => 
          array (
            'name' => 'days',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 694,
                'endLine' => 694,
                'startTokenPos' => 3134,
                'startFilePos' => 35429,
                'endTokenPos' => 3134,
                'endFilePos' => 35432,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 694,
            'endLine' => 694,
            'startColumn' => 81,
            'endColumn' => 92,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'hours' => 
          array (
            'name' => 'hours',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 694,
                'endLine' => 694,
                'startTokenPos' => 3141,
                'startFilePos' => 35444,
                'endTokenPos' => 3141,
                'endFilePos' => 35447,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 694,
            'endLine' => 694,
            'startColumn' => 95,
            'endColumn' => 107,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'minutes' => 
          array (
            'name' => 'minutes',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 694,
                'endLine' => 694,
                'startTokenPos' => 3148,
                'startFilePos' => 35461,
                'endTokenPos' => 3148,
                'endFilePos' => 35464,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 694,
            'endLine' => 694,
            'startColumn' => 110,
            'endColumn' => 124,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 694,
                'endLine' => 694,
                'startTokenPos' => 3155,
                'startFilePos' => 35478,
                'endTokenPos' => 3155,
                'endFilePos' => 35481,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 694,
            'endLine' => 694,
            'startColumn' => 127,
            'endColumn' => 141,
            'parameterIndex' => 6,
            'isOptional' => true,
          ),
          'microseconds' => 
          array (
            'name' => 'microseconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 694,
                'endLine' => 694,
                'startTokenPos' => 3162,
                'startFilePos' => 35500,
                'endTokenPos' => 3162,
                'endFilePos' => 35503,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 694,
            'endLine' => 694,
            'startColumn' => 144,
            'endColumn' => 163,
            'parameterIndex' => 7,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new CarbonInterval instance from specific values.
 * This is an alias for the constructor that allows better fluent
 * syntax as it allows you to do CarbonInterval::create(1)->fn() rather than
 * (new CarbonInterval(1))->fn().
 *
 * @param int $years
 * @param int $months
 * @param int $weeks
 * @param int $days
 * @param int $hours
 * @param int $minutes
 * @param int $seconds
 * @param int $microseconds
 *
 * @throws Exception when the interval_spec (passed as $years) cannot be parsed as an interval.
 *
 * @return static
 */',
        'startLine' => 694,
        'endLine' => 697,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'createFromFormat' => 
      array (
        'name' => 'createFromFormat',
        'parameters' => 
        array (
          'format' => 
          array (
            'name' => 'format',
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
            'startLine' => 714,
            'endLine' => 714,
            'startColumn' => 45,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'interval' => 
          array (
            'name' => 'interval',
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
            'startLine' => 714,
            'endLine' => 714,
            'startColumn' => 61,
            'endColumn' => 77,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Parse a string into a new CarbonInterval object according to the specified format.
 *
 * @example
 * ```
 * echo Carboninterval::createFromFormat(\'H:i\', \'1:30\');
 * ```
 *
 * @param string      $format   Format of the $interval input string
 * @param string|null $interval Input string to convert into an interval
 *
 * @throws \\Carbon\\Exceptions\\ParseErrorException when the $interval cannot be parsed as an interval.
 *
 * @return static
 */',
        'startLine' => 714,
        'endLine' => 764,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'original' => 
      array (
        'name' => 'original',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return the original source used to create the current interval.
 *
 * @return array|int|string|DateInterval|mixed|null
 */',
        'startLine' => 771,
        'endLine' => 774,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'start' => 
      array (
        'name' => 'start',
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
                  'name' => 'Carbon\\CarbonInterface',
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
        'docComment' => '/**
 * Return the start date if interval was created from a difference between 2 dates.
 *
 * @return CarbonInterface|null
 */',
        'startLine' => 781,
        'endLine' => 786,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'end' => 
      array (
        'name' => 'end',
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
                  'name' => 'Carbon\\CarbonInterface',
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
        'docComment' => '/**
 * Return the end date if interval was created from a difference between 2 dates.
 *
 * @return CarbonInterface|null
 */',
        'startLine' => 793,
        'endLine' => 798,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'optimize' => 
      array (
        'name' => 'optimize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get rid of the original input, start date and end date that may be kept in memory.
 *
 * @return $this
 */',
        'startLine' => 805,
        'endLine' => 814,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'copy' => 
      array (
        'name' => 'copy',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a copy of the instance.
 *
 * @return static
 */',
        'startLine' => 821,
        'endLine' => 828,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'clone' => 
      array (
        'name' => 'clone',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a copy of the instance.
 *
 * @return static
 */',
        'startLine' => 835,
        'endLine' => 838,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      '__callStatic' => 
      array (
        'name' => '__callStatic',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
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
            'startLine' => 851,
            'endLine' => 851,
            'startColumn' => 41,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
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
            'startLine' => 851,
            'endLine' => 851,
            'startColumn' => 57,
            'endColumn' => 73,
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
 * Provide static helpers to create instances.  Allows CarbonInterval::years(3).
 *
 * Note: This is done using the magic method to allow static and instance methods to
 *       have the same names.
 *
 * @param string $method     magic method name called
 * @param array  $parameters parameters list
 *
 * @return static|mixed|null
 */',
        'startLine' => 851,
        'endLine' => 874,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      '__set_state' => 
      array (
        'name' => '__set_state',
        'parameters' => 
        array (
          'dump' => 
          array (
            'name' => 'dump',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 884,
            'endLine' => 884,
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
 * Evaluate the PHP generated by var_export() and recreate the exported CarbonInterval instance.
 *
 * @param array $dump data as exported by var_export()
 *
 * @return static
 */',
        'startLine' => 883,
        'endLine' => 891,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'this' => 
      array (
        'name' => 'this',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return the current context from inside a macro callee or a new one if static.
 *
 * @return static
 */',
        'startLine' => 898,
        'endLine' => 901,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'fromString' => 
      array (
        'name' => 'fromString',
        'parameters' => 
        array (
          'intervalDefinition' => 
          array (
            'name' => 'intervalDefinition',
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
            'startLine' => 931,
            'endLine' => 931,
            'startColumn' => 39,
            'endColumn' => 64,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a CarbonInterval from string.
 *
 * Format:
 *
 * Suffix | Unit    | Example | DateInterval expression
 * -------|---------|---------|------------------------
 * y      | years   |   1y    | P1Y
 * mo     | months  |   3mo   | P3M
 * w      | weeks   |   2w    | P2W
 * d      | days    |  28d    | P28D
 * h      | hours   |   4h    | PT4H
 * m      | minutes |  12m    | PT12M
 * s      | seconds |  59s    | PT59S
 *
 * e. g. `1w 3d 4h 32m 23s` is converted to 10 days 4 hours 32 minutes and 23 seconds.
 *
 * Special cases:
 *  - An empty string will return a zero interval
 *  - Fractions are allowed for weeks, days, hours and minutes and will be converted
 *    and rounded to the next smaller value (caution: 0.5w = 4d)
 *
 * @param string $intervalDefinition
 *
 * @throws InvalidIntervalException
 *
 * @return static
 */',
        'startLine' => 931,
        'endLine' => 1095,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'parseFromLocale' => 
      array (
        'name' => 'parseFromLocale',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
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
            'startLine' => 1105,
            'endLine' => 1105,
            'startColumn' => 44,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'locale' => 
          array (
            'name' => 'locale',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1105,
                'endLine' => 1105,
                'startTokenPos' => 5042,
                'startFilePos' => 47626,
                'endTokenPos' => 5042,
                'endFilePos' => 47629,
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
            'startLine' => 1105,
            'endLine' => 1105,
            'startColumn' => 62,
            'endColumn' => 83,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a CarbonInterval from string using a different locale.
 *
 * @param string      $interval interval string in the given language (may also contain English).
 * @param string|null $locale   if locale is null or not specified, current global locale will be used instead.
 *
 * @return static
 */',
        'startLine' => 1105,
        'endLine' => 1108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'diff' => 
      array (
        'name' => 'diff',
        'parameters' => 
        array (
          'start' => 
          array (
            'name' => 'start',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1118,
            'endLine' => 1118,
            'startColumn' => 33,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'end' => 
          array (
            'name' => 'end',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1118,
                'endLine' => 1118,
                'startTokenPos' => 5101,
                'startFilePos' => 48075,
                'endTokenPos' => 5101,
                'endFilePos' => 48078,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1118,
            'endLine' => 1118,
            'startColumn' => 41,
            'endColumn' => 51,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'absolute' => 
          array (
            'name' => 'absolute',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1118,
                'endLine' => 1118,
                'startTokenPos' => 5110,
                'startFilePos' => 48098,
                'endTokenPos' => 5110,
                'endFilePos' => 48102,
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
            'startLine' => 1118,
            'endLine' => 1118,
            'startColumn' => 54,
            'endColumn' => 75,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'skip' => 
          array (
            'name' => 'skip',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1118,
                'endLine' => 1118,
                'startTokenPos' => 5119,
                'startFilePos' => 48119,
                'endTokenPos' => 5120,
                'endFilePos' => 48120,
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
            'startLine' => 1118,
            'endLine' => 1118,
            'startColumn' => 78,
            'endColumn' => 93,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an interval from the difference between 2 dates.
 *
 * @param \\Carbon\\Carbon|\\DateTimeInterface|mixed $start
 * @param \\Carbon\\Carbon|\\DateTimeInterface|mixed $end
 *
 * @return static
 */',
        'startLine' => 1118,
        'endLine' => 1132,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'abs' => 
      array (
        'name' => 'abs',
        'parameters' => 
        array (
          'absolute' => 
          array (
            'name' => 'absolute',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1141,
                'endLine' => 1141,
                'startTokenPos' => 5276,
                'startFilePos' => 48878,
                'endTokenPos' => 5276,
                'endFilePos' => 48882,
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
            'startLine' => 1141,
            'endLine' => 1141,
            'startColumn' => 25,
            'endColumn' => 46,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Invert the interval if it\'s inverted.
 *
 * @param bool $absolute do nothing if set to false
 *
 * @return $this
 */',
        'startLine' => 1141,
        'endLine' => 1148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'absolute' => 
      array (
        'name' => 'absolute',
        'parameters' => 
        array (
          'absolute' => 
          array (
            'name' => 'absolute',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 1159,
                'endLine' => 1159,
                'startTokenPos' => 5328,
                'startFilePos' => 49233,
                'endTokenPos' => 5328,
                'endFilePos' => 49236,
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
            'startLine' => 1159,
            'endLine' => 1159,
            'startColumn' => 30,
            'endColumn' => 50,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @alias abs
 *
 * Invert the interval if it\'s inverted.
 *
 * @param bool $absolute do nothing if set to false
 *
 * @return $this
 */',
        'startLine' => 1159,
        'endLine' => 1162,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'cast' => 
      array (
        'name' => 'cast',
        'parameters' => 
        array (
          'className' => 
          array (
            'name' => 'className',
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
            'startLine' => 1173,
            'endLine' => 1173,
            'startColumn' => 26,
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
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Cast the current instance into the given class.
 *
 * @template T of DateInterval
 *
 * @psalm-param class-string<T> $className The $className::instance() method will be called to cast the current object.
 *
 * @return T
 */',
        'startLine' => 1173,
        'endLine' => 1176,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'instance' => 
      array (
        'name' => 'instance',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateInterval',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1190,
            'endLine' => 1190,
            'startColumn' => 37,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'skip' => 
          array (
            'name' => 'skip',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1190,
                'endLine' => 1190,
                'startTokenPos' => 5402,
                'startFilePos' => 50256,
                'endTokenPos' => 5403,
                'endFilePos' => 50257,
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
            'startLine' => 1190,
            'endLine' => 1190,
            'startColumn' => 61,
            'endColumn' => 76,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'skipCopy' => 
          array (
            'name' => 'skipCopy',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1190,
                'endLine' => 1190,
                'startTokenPos' => 5412,
                'startFilePos' => 50277,
                'endTokenPos' => 5412,
                'endFilePos' => 50281,
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
            'startLine' => 1190,
            'endLine' => 1190,
            'startColumn' => 79,
            'endColumn' => 100,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a CarbonInterval instance from a DateInterval one.  Can not instance
 * DateInterval objects created from DateTime::diff() as you can\'t externally
 * set the $days field.
 *
 * @param DateInterval $interval
 * @param bool         $skipCopy set to true to return the passed object
 *                               (without copying it) if it\'s already of the
 *                               current class
 *
 * @return static
 */',
        'startLine' => 1190,
        'endLine' => 1197,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'make' => 
      array (
        'name' => 'make',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1213,
            'endLine' => 1213,
            'startColumn' => 33,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'unit' => 
          array (
            'name' => 'unit',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1213,
                'endLine' => 1213,
                'startTokenPos' => 5480,
                'startFilePos' => 51399,
                'endTokenPos' => 5480,
                'endFilePos' => 51402,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1213,
            'endLine' => 1213,
            'startColumn' => 44,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'skipCopy' => 
          array (
            'name' => 'skipCopy',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1213,
                'endLine' => 1213,
                'startTokenPos' => 5489,
                'startFilePos' => 51422,
                'endTokenPos' => 5489,
                'endFilePos' => 51426,
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
            'startLine' => 1213,
            'endLine' => 1213,
            'startColumn' => 58,
            'endColumn' => 79,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
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
                  'name' => 'self',
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
        'docComment' => '/**
 * Make a CarbonInterval instance from given variable if possible.
 *
 * Always return a new instance. Parse only strings and only these likely to be intervals (skip dates
 * and recurrences). Throw an exception for invalid format, but otherwise return null.
 *
 * @param mixed|int|DateInterval|string|Closure|Unit|null $interval interval or number of the given $unit
 * @param Unit|string|null                                $unit     if specified, $interval must be an integer
 * @param bool                                            $skipCopy set to true to return the passed object
 *                                                                  (without copying it) if it\'s already of the
 *                                                                  current class
 *
 * @return static|null
 */',
        'startLine' => 1213,
        'endLine' => 1240,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'makeFromString' => 
      array (
        'name' => 'makeFromString',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
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
            'startLine' => 1242,
            'endLine' => 1242,
            'startColumn' => 46,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
                  'name' => 'self',
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
        'startLine' => 1242,
        'endLine' => 1257,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'resolveInterval' => 
      array (
        'name' => 'resolveInterval',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1259,
            'endLine' => 1259,
            'startColumn' => 40,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
                  'name' => 'self',
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
        'startLine' => 1259,
        'endLine' => 1266,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'createFromDateString' => 
      array (
        'name' => 'createFromDateString',
        'parameters' => 
        array (
          'datetime' => 
          array (
            'name' => 'datetime',
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
            'startLine' => 1277,
            'endLine' => 1277,
            'startColumn' => 49,
            'endColumn' => 64,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sets up a DateInterval from the relative parts of the string.
 *
 * @param string $datetime
 *
 * @return static
 *
 * @link https://php.net/manual/en/dateinterval.createfromdatestring.php
 */',
        'startLine' => 1277,
        'endLine' => 1302,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
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
                      'name' => 'Carbon\\Unit',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
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
            'startLine' => 1311,
            'endLine' => 1311,
            'startColumn' => 25,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
                  'name' => 'int',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'float',
                  'isIdentifier' => true,
                ),
              ),
              2 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              3 => 
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
        'docComment' => '/**
 * Get a part of the CarbonInterval object.
 */',
        'startLine' => 1311,
        'endLine' => 1344,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      '__get' => 
      array (
        'name' => '__get',
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
            'startLine' => 1349,
            'endLine' => 1349,
            'startColumn' => 27,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
                  'name' => 'int',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'float',
                  'isIdentifier' => true,
                ),
              ),
              2 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              3 => 
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
        'docComment' => '/**
 * Get a part of the CarbonInterval object.
 */',
        'startLine' => 1349,
        'endLine' => 1352,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'set' => 
      array (
        'name' => 'set',
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
            'startLine' => 1364,
            'endLine' => 1364,
            'startColumn' => 25,
            'endColumn' => 29,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1364,
                'endLine' => 1364,
                'startTokenPos' => 6468,
                'startFilePos' => 56202,
                'endTokenPos' => 6468,
                'endFilePos' => 56205,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1364,
            'endLine' => 1364,
            'startColumn' => 32,
            'endColumn' => 44,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set a part of the CarbonInterval object.
 *
 * @param Unit|string|array $name
 * @param int               $value
 *
 * @throws UnknownSetterException
 *
 * @return $this
 */',
        'startLine' => 1364,
        'endLine' => 1461,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      '__set' => 
      array (
        'name' => '__set',
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
            'startLine' => 1471,
            'endLine' => 1471,
            'startColumn' => 27,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 1471,
            'endLine' => 1471,
            'startColumn' => 41,
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
 * Set a part of the CarbonInterval object.
 *
 * @param string $name
 * @param int    $value
 *
 * @throws UnknownSetterException
 */',
        'startLine' => 1471,
        'endLine' => 1474,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'weeksAndDays' => 
      array (
        'name' => 'weeksAndDays',
        'parameters' => 
        array (
          'weeks' => 
          array (
            'name' => 'weeks',
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
            'startLine' => 1484,
            'endLine' => 1484,
            'startColumn' => 34,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'days' => 
          array (
            'name' => 'days',
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
            'startLine' => 1484,
            'endLine' => 1484,
            'startColumn' => 46,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Allow setting of weeks and days to be cumulative.
 *
 * @param int $weeks Number of weeks to set
 * @param int $days  Number of days to set
 *
 * @return static
 */',
        'startLine' => 1484,
        'endLine' => 1489,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'isEmpty' => 
      array (
        'name' => 'isEmpty',
        'parameters' => 
        array (
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
 * Returns true if the interval is empty for each unit.
 *
 * @return bool
 */',
        'startLine' => 1496,
        'endLine' => 1506,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'macro' => 
      array (
        'name' => 'macro',
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
            'startLine' => 1523,
            'endLine' => 1523,
            'startColumn' => 34,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'macro' => 
          array (
            'name' => 'macro',
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
                      'name' => 'callable',
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
            'startLine' => 1523,
            'endLine' => 1523,
            'startColumn' => 48,
            'endColumn' => 63,
            'parameterIndex' => 1,
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
 * Register a custom macro.
 *
 * Pass null macro to remove it.
 *
 * @example
 * ```
 * CarbonInterval::macro(\'twice\', function () {
 *   return $this->times(2);
 * });
 * echo CarbonInterval::hours(2)->twice();
 * ```
 *
 * @param-closure-this static $macro
 */',
        'startLine' => 1523,
        'endLine' => 1526,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'mixin' => 
      array (
        'name' => 'mixin',
        'parameters' => 
        array (
          'mixin' => 
          array (
            'name' => 'mixin',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1561,
            'endLine' => 1561,
            'startColumn' => 34,
            'endColumn' => 39,
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
 * Register macros from a mixin object.
 *
 * @example
 * ```
 * CarbonInterval::mixin(new class {
 *   public function daysToHours() {
 *     return function () {
 *       $this->hours += $this->days;
 *       $this->days = 0;
 *
 *       return $this;
 *     };
 *   }
 *   public function hoursToDays() {
 *     return function () {
 *       $this->days += $this->hours;
 *       $this->hours = 0;
 *
 *       return $this;
 *     };
 *   }
 * });
 * echo CarbonInterval::hours(5)->hoursToDays() . "\\n";
 * echo CarbonInterval::days(5)->daysToHours() . "\\n";
 * ```
 *
 * @param object|string $mixin
 *
 * @throws ReflectionException
 *
 * @return void
 */',
        'startLine' => 1561,
        'endLine' => 1564,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'hasMacro' => 
      array (
        'name' => 'hasMacro',
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
            'startLine' => 1573,
            'endLine' => 1573,
            'startColumn' => 37,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Check if macro is registered.
 *
 * @param string $name
 *
 * @return bool
 */',
        'startLine' => 1573,
        'endLine' => 1576,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'callMacro' => 
      array (
        'name' => 'callMacro',
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
            'startLine' => 1586,
            'endLine' => 1586,
            'startColumn' => 34,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
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
            'startLine' => 1586,
            'endLine' => 1586,
            'startColumn' => 48,
            'endColumn' => 64,
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
 * Call given macro.
 *
 * @param string $name
 * @param array  $parameters
 *
 * @return mixed
 */',
        'startLine' => 1586,
        'endLine' => 1597,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      '__call' => 
      array (
        'name' => '__call',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
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
            'startLine' => 1612,
            'endLine' => 1612,
            'startColumn' => 28,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
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
            'startLine' => 1612,
            'endLine' => 1612,
            'startColumn' => 44,
            'endColumn' => 60,
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
 * Allow fluent calls on the setters... CarbonInterval::years(3)->months(5)->day().
 *
 * Note: This is done using the magic method to allow static and instance methods to
 *       have the same names.
 *
 * @param string $method     magic method name called
 * @param array  $parameters parameters list
 *
 * @throws BadFluentSetterException|Throwable
 *
 * @return static|int|float|string
 */',
        'startLine' => 1612,
        'endLine' => 1643,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getForHumansInitialVariables' => 
      array (
        'name' => 'getForHumansInitialVariables',
        'parameters' => 
        array (
          'syntax' => 
          array (
            'name' => 'syntax',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1645,
            'endLine' => 1645,
            'startColumn' => 53,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'short' => 
          array (
            'name' => 'short',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1645,
            'endLine' => 1645,
            'startColumn' => 62,
            'endColumn' => 67,
            'parameterIndex' => 1,
            'isOptional' => false,
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
        'docComment' => NULL,
        'startLine' => 1645,
        'endLine' => 1666,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getForHumansParameters' => 
      array (
        'name' => 'getForHumansParameters',
        'parameters' => 
        array (
          'syntax' => 
          array (
            'name' => 'syntax',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1676,
                'endLine' => 1676,
                'startTokenPos' => 7937,
                'startFilePos' => 65076,
                'endTokenPos' => 7937,
                'endFilePos' => 65079,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1676,
            'endLine' => 1676,
            'startColumn' => 47,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'short' => 
          array (
            'name' => 'short',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1676,
                'endLine' => 1676,
                'startTokenPos' => 7944,
                'startFilePos' => 65091,
                'endTokenPos' => 7944,
                'endFilePos' => 65095,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1676,
            'endLine' => 1676,
            'startColumn' => 63,
            'endColumn' => 76,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'parts' => 
          array (
            'name' => 'parts',
            'default' => 
            array (
              'code' => 'self::NO_LIMIT',
              'attributes' => 
              array (
                'startLine' => 1676,
                'endLine' => 1676,
                'startTokenPos' => 7951,
                'startFilePos' => 65107,
                'endTokenPos' => 7953,
                'endFilePos' => 65120,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1676,
            'endLine' => 1676,
            'startColumn' => 79,
            'endColumn' => 101,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1676,
                'endLine' => 1676,
                'startTokenPos' => 7960,
                'startFilePos' => 65134,
                'endTokenPos' => 7960,
                'endFilePos' => 65137,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1676,
            'endLine' => 1676,
            'startColumn' => 104,
            'endColumn' => 118,
            'parameterIndex' => 3,
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
 * @param mixed $syntax
 * @param mixed $short
 * @param mixed $parts
 * @param mixed $options
 *
 * @return array
 */',
        'startLine' => 1676,
        'endLine' => 1753,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getRoundingMethodFromOptions' => 
      array (
        'name' => 'getRoundingMethodFromOptions',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
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
            'startLine' => 1755,
            'endLine' => 1755,
            'startColumn' => 60,
            'endColumn' => 71,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1755,
        'endLine' => 1770,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'toArray' => 
      array (
        'name' => 'toArray',
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
 * Returns interval values as an array where key are the unit names and values the counts.
 *
 * @return int[]
 */',
        'startLine' => 1777,
        'endLine' => 1789,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getNonZeroValues' => 
      array (
        'name' => 'getNonZeroValues',
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
 * Returns interval non-zero values as an array where key are the unit names and values the counts.
 *
 * @return int[]
 */',
        'startLine' => 1796,
        'endLine' => 1799,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getValuesSequence' => 
      array (
        'name' => 'getValuesSequence',
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
 * Returns interval values as an array where key are the unit names and values the counts
 * from the biggest non-zero one the the smallest non-zero one.
 *
 * @return int[]
 */',
        'startLine' => 1807,
        'endLine' => 1836,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'forHumans' => 
      array (
        'name' => 'forHumans',
        'parameters' => 
        array (
          'syntax' => 
          array (
            'name' => 'syntax',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1888,
                'endLine' => 1888,
                'startTokenPos' => 8991,
                'startFilePos' => 73260,
                'endTokenPos' => 8991,
                'endFilePos' => 73263,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1888,
            'endLine' => 1888,
            'startColumn' => 31,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'short' => 
          array (
            'name' => 'short',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1888,
                'endLine' => 1888,
                'startTokenPos' => 8998,
                'startFilePos' => 73275,
                'endTokenPos' => 8998,
                'endFilePos' => 73279,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1888,
            'endLine' => 1888,
            'startColumn' => 47,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'parts' => 
          array (
            'name' => 'parts',
            'default' => 
            array (
              'code' => 'self::NO_LIMIT',
              'attributes' => 
              array (
                'startLine' => 1888,
                'endLine' => 1888,
                'startTokenPos' => 9005,
                'startFilePos' => 73291,
                'endTokenPos' => 9007,
                'endFilePos' => 73304,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1888,
            'endLine' => 1888,
            'startColumn' => 63,
            'endColumn' => 85,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1888,
                'endLine' => 1888,
                'startTokenPos' => 9014,
                'startFilePos' => 73318,
                'endTokenPos' => 9014,
                'endFilePos' => 73321,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1888,
            'endLine' => 1888,
            'startColumn' => 88,
            'endColumn' => 102,
            'parameterIndex' => 3,
            'isOptional' => true,
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
 * Get the current interval in a human readable format in the current locale.
 *
 * @example
 * ```
 * echo CarbonInterval::fromString(\'4d 3h 40m\')->forHumans() . "\\n";
 * echo CarbonInterval::fromString(\'4d 3h 40m\')->forHumans([\'parts\' => 2]) . "\\n";
 * echo CarbonInterval::fromString(\'4d 3h 40m\')->forHumans([\'parts\' => 3, \'join\' => true]) . "\\n";
 * echo CarbonInterval::fromString(\'4d 3h 40m\')->forHumans([\'short\' => true]) . "\\n";
 * echo CarbonInterval::fromString(\'1d 24h\')->forHumans([\'join\' => \' or \']) . "\\n";
 * echo CarbonInterval::fromString(\'1d 24h\')->forHumans([\'minimumUnit\' => \'hour\']) . "\\n";
 * ```
 *
 * @param int|array $syntax  if array passed, parameters will be extracted from it, the array may contain:
 *                           ⦿ \'syntax\' entry (see below)
 *                           ⦿ \'short\' entry (see below)
 *                           ⦿ \'parts\' entry (see below)
 *                           ⦿ \'options\' entry (see below)
 *                           ⦿ \'skip\' entry, list of units to skip (array of strings or a single string,
 *                           ` it can be the unit name (singular or plural) or its shortcut
 *                           ` (y, m, w, d, h, min, s, ms, µs).
 *                           ⦿ \'aUnit\' entry, prefer "an hour" over "1 hour" if true
 *                           ⦿ \'altNumbers\' entry, use alternative numbers if available
 *                           ` (from the current language if true is passed, from the given language(s)
 *                           ` if array or string is passed)
 *                           ⦿ \'join\' entry determines how to join multiple parts of the string
 *                           `  - if $join is a string, it\'s used as a joiner glue
 *                           `  - if $join is a callable/closure, it get the list of string and should return a string
 *                           `  - if $join is an array, the first item will be the default glue, and the second item
 *                           `    will be used instead of the glue for the last item
 *                           `  - if $join is true, it will be guessed from the locale (\'list\' translation file entry)
 *                           `  - if $join is missing, a space will be used as glue
 *                           ⦿ \'minimumUnit\' entry determines the smallest unit of time to display can be long or
 *                           `  short form of the units, e.g. \'hour\' or \'h\' (default value: s)
 *                           ⦿ \'locale\' language in which the diff should be output (has no effect if \'translator\' key is set)
 *                           ⦿ \'translator\' a custom translator to use to translator the output.
 *                           if int passed, it adds modifiers:
 *                           Possible values:
 *                           - CarbonInterface::DIFF_ABSOLUTE          no modifiers
 *                           - CarbonInterface::DIFF_RELATIVE_TO_NOW   add ago/from now modifier
 *                           - CarbonInterface::DIFF_RELATIVE_TO_OTHER add before/after modifier
 *                           Default value: CarbonInterface::DIFF_ABSOLUTE
 * @param bool      $short   displays short format of time units
 * @param int       $parts   maximum number of parts to display (default value: -1: no limits)
 * @param int       $options human diff options
 *
 * @throws Exception
 *
 * @return string
 */',
        'startLine' => 1888,
        'endLine' => 2087,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'format' => 
      array (
        'name' => 'format',
        'parameters' => 
        array (
          'format' => 
          array (
            'name' => 'format',
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
            'startLine' => 2089,
            'endLine' => 2089,
            'startColumn' => 28,
            'endColumn' => 41,
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
        'startLine' => 2089,
        'endLine' => 2100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      '__toString' => 
      array (
        'name' => '__toString',
        'parameters' => 
        array (
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
 * Format the instance as a string using the forHumans() function.
 *
 * @throws Exception
 *
 * @return string
 */',
        'startLine' => 2109,
        'endLine' => 2124,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'toDateInterval' => 
      array (
        'name' => 'toDateInterval',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'DateInterval',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return native DateInterval PHP object matching the current instance.
 *
 * @example
 * ```
 * var_dump(CarbonInterval::hours(2)->toDateInterval());
 * ```
 *
 * @return DateInterval
 */',
        'startLine' => 2136,
        'endLine' => 2139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'toPeriod' => 
      array (
        'name' => 'toPeriod',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2148,
            'endLine' => 2148,
            'startColumn' => 30,
            'endColumn' => 39,
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
            'name' => 'Carbon\\CarbonPeriod',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convert the interval to a CarbonPeriod.
 *
 * @param DateTimeInterface|string|int ...$params Start date, [end date or recurrences] and optional settings.
 *
 * @return CarbonPeriod
 */',
        'startLine' => 2148,
        'endLine' => 2163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'stepBy' => 
      array (
        'name' => 'stepBy',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2173,
            'endLine' => 2173,
            'startColumn' => 28,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'unit' => 
          array (
            'name' => 'unit',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 2173,
                'endLine' => 2173,
                'startTokenPos' => 11262,
                'startFilePos' => 83864,
                'endTokenPos' => 11262,
                'endFilePos' => 83867,
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
                      'name' => 'Carbon\\Unit',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
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
            'startLine' => 2173,
            'endLine' => 2173,
            'startColumn' => 39,
            'endColumn' => 67,
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
            'name' => 'Carbon\\CarbonPeriod',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Decompose the current interval into
 *
 * @param mixed|int|DateInterval|string|Closure|Unit|null $interval interval or number of the given $unit
 * @param Unit|string|null                                $unit     if specified, $interval must be an integer
 *
 * @return CarbonPeriod
 */',
        'startLine' => 2173,
        'endLine' => 2192,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'invert' => 
      array (
        'name' => 'invert',
        'parameters' => 
        array (
          'inverted' => 
          array (
            'name' => 'inverted',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 2202,
                'endLine' => 2202,
                'startTokenPos' => 11463,
                'startFilePos' => 84853,
                'endTokenPos' => 11463,
                'endFilePos' => 84856,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2202,
            'endLine' => 2202,
            'startColumn' => 28,
            'endColumn' => 43,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Invert the interval.
 *
 * @param bool|int $inverted if a parameter is passed, the passed value cast as 1 or 0 is used
 *                           as the new value of the ->invert property.
 *
 * @return $this
 */',
        'startLine' => 2202,
        'endLine' => 2207,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'solveNegativeInterval' => 
      array (
        'name' => 'solveNegativeInterval',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 2209,
        'endLine' => 2223,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'add' => 
      array (
        'name' => 'add',
        'parameters' => 
        array (
          'unit' => 
          array (
            'name' => 'unit',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2233,
            'endLine' => 2233,
            'startColumn' => 25,
            'endColumn' => 29,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 2233,
                'endLine' => 2233,
                'startTokenPos' => 11718,
                'startFilePos' => 85858,
                'endTokenPos' => 11718,
                'endFilePos' => 85858,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2233,
            'endLine' => 2233,
            'startColumn' => 32,
            'endColumn' => 41,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add the passed interval to the current instance.
 *
 * @param string|DateInterval $unit
 * @param int|float           $value
 *
 * @return $this
 */',
        'startLine' => 2233,
        'endLine' => 2266,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'sub' => 
      array (
        'name' => 'sub',
        'parameters' => 
        array (
          'unit' => 
          array (
            'name' => 'unit',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2276,
            'endLine' => 2276,
            'startColumn' => 25,
            'endColumn' => 29,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 2276,
                'endLine' => 2276,
                'startTokenPos' => 12044,
                'startFilePos' => 87158,
                'endTokenPos' => 12044,
                'endFilePos' => 87158,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2276,
            'endLine' => 2276,
            'startColumn' => 32,
            'endColumn' => 41,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Subtract the passed interval to the current instance.
 *
 * @param string|DateInterval $unit
 * @param int|float           $value
 *
 * @return $this
 */',
        'startLine' => 2276,
        'endLine' => 2283,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'subtract' => 
      array (
        'name' => 'subtract',
        'parameters' => 
        array (
          'unit' => 
          array (
            'name' => 'unit',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2293,
            'endLine' => 2293,
            'startColumn' => 30,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 2293,
                'endLine' => 2293,
                'startTokenPos' => 12115,
                'startFilePos' => 87562,
                'endTokenPos' => 12115,
                'endFilePos' => 87562,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2293,
            'endLine' => 2293,
            'startColumn' => 37,
            'endColumn' => 46,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Subtract the passed interval to the current instance.
 *
 * @param string|DateInterval $unit
 * @param int|float           $value
 *
 * @return $this
 */',
        'startLine' => 2293,
        'endLine' => 2296,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'plus' => 
      array (
        'name' => 'plus',
        'parameters' => 
        array (
          'years' => 
          array (
            'name' => 'years',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2313,
                'endLine' => 2313,
                'startTokenPos' => 12151,
                'startFilePos' => 88034,
                'endTokenPos' => 12151,
                'endFilePos' => 88034,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2313,
            'endLine' => 2313,
            'startColumn' => 9,
            'endColumn' => 18,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'months' => 
          array (
            'name' => 'months',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2314,
                'endLine' => 2314,
                'startTokenPos' => 12158,
                'startFilePos' => 88055,
                'endTokenPos' => 12158,
                'endFilePos' => 88055,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2314,
            'endLine' => 2314,
            'startColumn' => 9,
            'endColumn' => 19,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'weeks' => 
          array (
            'name' => 'weeks',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2315,
                'endLine' => 2315,
                'startTokenPos' => 12165,
                'startFilePos' => 88075,
                'endTokenPos' => 12165,
                'endFilePos' => 88075,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2315,
            'endLine' => 2315,
            'startColumn' => 9,
            'endColumn' => 18,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'days' => 
          array (
            'name' => 'days',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2316,
                'endLine' => 2316,
                'startTokenPos' => 12172,
                'startFilePos' => 88094,
                'endTokenPos' => 12172,
                'endFilePos' => 88094,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2316,
            'endLine' => 2316,
            'startColumn' => 9,
            'endColumn' => 17,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'hours' => 
          array (
            'name' => 'hours',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2317,
                'endLine' => 2317,
                'startTokenPos' => 12179,
                'startFilePos' => 88114,
                'endTokenPos' => 12179,
                'endFilePos' => 88114,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2317,
            'endLine' => 2317,
            'startColumn' => 9,
            'endColumn' => 18,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'minutes' => 
          array (
            'name' => 'minutes',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2318,
                'endLine' => 2318,
                'startTokenPos' => 12186,
                'startFilePos' => 88136,
                'endTokenPos' => 12186,
                'endFilePos' => 88136,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2318,
            'endLine' => 2318,
            'startColumn' => 9,
            'endColumn' => 20,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2319,
                'endLine' => 2319,
                'startTokenPos' => 12193,
                'startFilePos' => 88158,
                'endTokenPos' => 12193,
                'endFilePos' => 88158,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2319,
            'endLine' => 2319,
            'startColumn' => 9,
            'endColumn' => 20,
            'parameterIndex' => 6,
            'isOptional' => true,
          ),
          'microseconds' => 
          array (
            'name' => 'microseconds',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2320,
                'endLine' => 2320,
                'startTokenPos' => 12200,
                'startFilePos' => 88185,
                'endTokenPos' => 12200,
                'endFilePos' => 88185,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2320,
            'endLine' => 2320,
            'startColumn' => 9,
            'endColumn' => 25,
            'parameterIndex' => 7,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add given parameters to the current interval.
 *
 * @param int       $years
 * @param int       $months
 * @param int|float $weeks
 * @param int|float $days
 * @param int|float $hours
 * @param int|float $minutes
 * @param int|float $seconds
 * @param int|float $microseconds
 *
 * @return $this
 */',
        'startLine' => 2312,
        'endLine' => 2326,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'minus' => 
      array (
        'name' => 'minus',
        'parameters' => 
        array (
          'years' => 
          array (
            'name' => 'years',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2343,
                'endLine' => 2343,
                'startTokenPos' => 12252,
                'startFilePos' => 88807,
                'endTokenPos' => 12252,
                'endFilePos' => 88807,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2343,
            'endLine' => 2343,
            'startColumn' => 9,
            'endColumn' => 18,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'months' => 
          array (
            'name' => 'months',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2344,
                'endLine' => 2344,
                'startTokenPos' => 12259,
                'startFilePos' => 88828,
                'endTokenPos' => 12259,
                'endFilePos' => 88828,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2344,
            'endLine' => 2344,
            'startColumn' => 9,
            'endColumn' => 19,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'weeks' => 
          array (
            'name' => 'weeks',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2345,
                'endLine' => 2345,
                'startTokenPos' => 12266,
                'startFilePos' => 88848,
                'endTokenPos' => 12266,
                'endFilePos' => 88848,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2345,
            'endLine' => 2345,
            'startColumn' => 9,
            'endColumn' => 18,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'days' => 
          array (
            'name' => 'days',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2346,
                'endLine' => 2346,
                'startTokenPos' => 12273,
                'startFilePos' => 88867,
                'endTokenPos' => 12273,
                'endFilePos' => 88867,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2346,
            'endLine' => 2346,
            'startColumn' => 9,
            'endColumn' => 17,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'hours' => 
          array (
            'name' => 'hours',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2347,
                'endLine' => 2347,
                'startTokenPos' => 12280,
                'startFilePos' => 88887,
                'endTokenPos' => 12280,
                'endFilePos' => 88887,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2347,
            'endLine' => 2347,
            'startColumn' => 9,
            'endColumn' => 18,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'minutes' => 
          array (
            'name' => 'minutes',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2348,
                'endLine' => 2348,
                'startTokenPos' => 12287,
                'startFilePos' => 88909,
                'endTokenPos' => 12287,
                'endFilePos' => 88909,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2348,
            'endLine' => 2348,
            'startColumn' => 9,
            'endColumn' => 20,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2349,
                'endLine' => 2349,
                'startTokenPos' => 12294,
                'startFilePos' => 88931,
                'endTokenPos' => 12294,
                'endFilePos' => 88931,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2349,
            'endLine' => 2349,
            'startColumn' => 9,
            'endColumn' => 20,
            'parameterIndex' => 6,
            'isOptional' => true,
          ),
          'microseconds' => 
          array (
            'name' => 'microseconds',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 2350,
                'endLine' => 2350,
                'startTokenPos' => 12301,
                'startFilePos' => 88958,
                'endTokenPos' => 12301,
                'endFilePos' => 88958,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2350,
            'endLine' => 2350,
            'startColumn' => 9,
            'endColumn' => 25,
            'parameterIndex' => 7,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add given parameters to the current interval.
 *
 * @param int       $years
 * @param int       $months
 * @param int|float $weeks
 * @param int|float $days
 * @param int|float $hours
 * @param int|float $minutes
 * @param int|float $seconds
 * @param int|float $microseconds
 *
 * @return $this
 */',
        'startLine' => 2342,
        'endLine' => 2356,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'times' => 
      array (
        'name' => 'times',
        'parameters' => 
        array (
          'factor' => 
          array (
            'name' => 'factor',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2373,
            'endLine' => 2373,
            'startColumn' => 27,
            'endColumn' => 33,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Multiply current instance given number of times. times() is naive, it multiplies each unit
 * (so day can be greater than 31, hour can be greater than 23, etc.) and the result is rounded
 * separately for each unit.
 *
 * Use times() when you want a fast and approximated calculation that does not cascade units.
 *
 * For a precise and cascaded calculation,
 *
 * @see multiply()
 *
 * @param float|int $factor
 *
 * @return $this
 */',
        'startLine' => 2373,
        'endLine' => 2389,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'shares' => 
      array (
        'name' => 'shares',
        'parameters' => 
        array (
          'divider' => 
          array (
            'name' => 'divider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2406,
            'endLine' => 2406,
            'startColumn' => 28,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Divide current instance by a given divider. shares() is naive, it divides each unit separately
 * and the result is rounded for each unit. So 5 hours and 20 minutes shared by 3 becomes 2 hours
 * and 7 minutes.
 *
 * Use shares() when you want a fast and approximated calculation that does not cascade units.
 *
 * For a precise and cascaded calculation,
 *
 * @see divide()
 *
 * @param float|int $divider
 *
 * @return $this
 */',
        'startLine' => 2406,
        'endLine' => 2409,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'copyProperties' => 
      array (
        'name' => 'copyProperties',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'self',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2411,
            'endLine' => 2411,
            'startColumn' => 39,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'ignoreSign' => 
          array (
            'name' => 'ignoreSign',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 2411,
                'endLine' => 2411,
                'startTokenPos' => 12591,
                'startFilePos' => 90985,
                'endTokenPos' => 12591,
                'endFilePos' => 90989,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2411,
            'endLine' => 2411,
            'startColumn' => 55,
            'endColumn' => 73,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 2411,
        'endLine' => 2426,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'multiply' => 
      array (
        'name' => 'multiply',
        'parameters' => 
        array (
          'factor' => 
          array (
            'name' => 'factor',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2435,
            'endLine' => 2435,
            'startColumn' => 30,
            'endColumn' => 36,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Multiply and cascade current instance by a given factor.
 *
 * @param float|int $factor
 *
 * @return $this
 */',
        'startLine' => 2435,
        'endLine' => 2454,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'divide' => 
      array (
        'name' => 'divide',
        'parameters' => 
        array (
          'divider' => 
          array (
            'name' => 'divider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2463,
            'endLine' => 2463,
            'startColumn' => 28,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Divide and cascade current instance by a given divider.
 *
 * @param float|int $divider
 *
 * @return $this
 */',
        'startLine' => 2463,
        'endLine' => 2466,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getDateIntervalSpec' => 
      array (
        'name' => 'getDateIntervalSpec',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateInterval',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2475,
            'endLine' => 2475,
            'startColumn' => 48,
            'endColumn' => 69,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'microseconds' => 
          array (
            'name' => 'microseconds',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 2475,
                'endLine' => 2475,
                'startTokenPos' => 12901,
                'startFilePos' => 92639,
                'endTokenPos' => 12901,
                'endFilePos' => 92643,
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
            'startLine' => 2475,
            'endLine' => 2475,
            'startColumn' => 72,
            'endColumn' => 97,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'skip' => 
          array (
            'name' => 'skip',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 2475,
                'endLine' => 2475,
                'startTokenPos' => 12910,
                'startFilePos' => 92660,
                'endTokenPos' => 12911,
                'endFilePos' => 92661,
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
            'startLine' => 2475,
            'endLine' => 2475,
            'startColumn' => 100,
            'endColumn' => 115,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the interval_spec string of a date interval.
 *
 * @param DateInterval $interval
 *
 * @return string
 */',
        'startLine' => 2475,
        'endLine' => 2520,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'spec' => 
      array (
        'name' => 'spec',
        'parameters' => 
        array (
          'microseconds' => 
          array (
            'name' => 'microseconds',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 2527,
                'endLine' => 2527,
                'startTokenPos' => 13344,
                'startFilePos' => 94352,
                'endTokenPos' => 13344,
                'endFilePos' => 94356,
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
            'startLine' => 2527,
            'endLine' => 2527,
            'startColumn' => 26,
            'endColumn' => 51,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the interval_spec string.
 *
 * @return string
 */',
        'startLine' => 2527,
        'endLine' => 2530,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'compareDateIntervals' => 
      array (
        'name' => 'compareDateIntervals',
        'parameters' => 
        array (
          'first' => 
          array (
            'name' => 'first',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateInterval',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2540,
            'endLine' => 2540,
            'startColumn' => 49,
            'endColumn' => 67,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateInterval',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2540,
            'endLine' => 2540,
            'startColumn' => 70,
            'endColumn' => 89,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Comparing 2 date intervals.
 *
 * @param DateInterval $first
 * @param DateInterval $second
 *
 * @return int 0, 1 or -1
 */',
        'startLine' => 2540,
        'endLine' => 2547,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'compare' => 
      array (
        'name' => 'compare',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateInterval',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2556,
            'endLine' => 2556,
            'startColumn' => 29,
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
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Comparing with passed interval.
 *
 * @param DateInterval $interval
 *
 * @return int 0, 1 or -1
 */',
        'startLine' => 2556,
        'endLine' => 2559,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'cascade' => 
      array (
        'name' => 'cascade',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convert overflowed values into bigger units.
 *
 * @return $this
 */',
        'startLine' => 2566,
        'endLine' => 2569,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'hasNegativeValues' => 
      array (
        'name' => 'hasNegativeValues',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 2571,
        'endLine' => 2580,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'hasPositiveValues' => 
      array (
        'name' => 'hasPositiveValues',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 2582,
        'endLine' => 2591,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'total' => 
      array (
        'name' => 'total',
        'parameters' => 
        array (
          'unit' => 
          array (
            'name' => 'unit',
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
            'startLine' => 2602,
            'endLine' => 2602,
            'startColumn' => 27,
            'endColumn' => 38,
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
            'name' => 'float',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get amount of given unit equivalent to the interval.
 *
 * @param string $unit
 *
 * @throws UnknownUnitException|UnitNotConfiguredException
 *
 * @return float
 */',
        'startLine' => 2602,
        'endLine' => 2696,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'eq' => 
      array (
        'name' => 'eq',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2707,
            'endLine' => 2707,
            'startColumn' => 24,
            'endColumn' => 32,
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
 * Determines if the instance is equal to another
 *
 * @param CarbonInterval|DateInterval|mixed $interval
 *
 * @see equalTo()
 *
 * @return bool
 */',
        'startLine' => 2707,
        'endLine' => 2710,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'equalTo' => 
      array (
        'name' => 'equalTo',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2719,
            'endLine' => 2719,
            'startColumn' => 29,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determines if the instance is equal to another
 *
 * @param CarbonInterval|DateInterval|mixed $interval
 *
 * @return bool
 */',
        'startLine' => 2719,
        'endLine' => 2742,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'ne' => 
      array (
        'name' => 'ne',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2753,
            'endLine' => 2753,
            'startColumn' => 24,
            'endColumn' => 32,
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
 * Determines if the instance is not equal to another
 *
 * @param CarbonInterval|DateInterval|mixed $interval
 *
 * @see notEqualTo()
 *
 * @return bool
 */',
        'startLine' => 2753,
        'endLine' => 2756,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'notEqualTo' => 
      array (
        'name' => 'notEqualTo',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2765,
            'endLine' => 2765,
            'startColumn' => 32,
            'endColumn' => 40,
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
 * Determines if the instance is not equal to another
 *
 * @param CarbonInterval|DateInterval|mixed $interval
 *
 * @return bool
 */',
        'startLine' => 2765,
        'endLine' => 2768,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'gt' => 
      array (
        'name' => 'gt',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2779,
            'endLine' => 2779,
            'startColumn' => 24,
            'endColumn' => 32,
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
 * Determines if the instance is greater (longer) than another
 *
 * @param CarbonInterval|DateInterval|mixed $interval
 *
 * @see greaterThan()
 *
 * @return bool
 */',
        'startLine' => 2779,
        'endLine' => 2782,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'greaterThan' => 
      array (
        'name' => 'greaterThan',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2791,
            'endLine' => 2791,
            'startColumn' => 33,
            'endColumn' => 41,
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
 * Determines if the instance is greater (longer) than another
 *
 * @param CarbonInterval|DateInterval|mixed $interval
 *
 * @return bool
 */',
        'startLine' => 2791,
        'endLine' => 2796,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'gte' => 
      array (
        'name' => 'gte',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2807,
            'endLine' => 2807,
            'startColumn' => 25,
            'endColumn' => 33,
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
 * Determines if the instance is greater (longer) than or equal to another
 *
 * @param CarbonInterval|DateInterval|mixed $interval
 *
 * @see greaterThanOrEqualTo()
 *
 * @return bool
 */',
        'startLine' => 2807,
        'endLine' => 2810,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'greaterThanOrEqualTo' => 
      array (
        'name' => 'greaterThanOrEqualTo',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2819,
            'endLine' => 2819,
            'startColumn' => 42,
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
 * Determines if the instance is greater (longer) than or equal to another
 *
 * @param CarbonInterval|DateInterval|mixed $interval
 *
 * @return bool
 */',
        'startLine' => 2819,
        'endLine' => 2822,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'lt' => 
      array (
        'name' => 'lt',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2833,
            'endLine' => 2833,
            'startColumn' => 24,
            'endColumn' => 32,
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
 * Determines if the instance is less (shorter) than another
 *
 * @param CarbonInterval|DateInterval|mixed $interval
 *
 * @see lessThan()
 *
 * @return bool
 */',
        'startLine' => 2833,
        'endLine' => 2836,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'lessThan' => 
      array (
        'name' => 'lessThan',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2845,
            'endLine' => 2845,
            'startColumn' => 30,
            'endColumn' => 38,
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
 * Determines if the instance is less (shorter) than another
 *
 * @param CarbonInterval|DateInterval|mixed $interval
 *
 * @return bool
 */',
        'startLine' => 2845,
        'endLine' => 2850,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'lte' => 
      array (
        'name' => 'lte',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2861,
            'endLine' => 2861,
            'startColumn' => 25,
            'endColumn' => 33,
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
 * Determines if the instance is less (shorter) than or equal to another
 *
 * @param CarbonInterval|DateInterval|mixed $interval
 *
 * @see lessThanOrEqualTo()
 *
 * @return bool
 */',
        'startLine' => 2861,
        'endLine' => 2864,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'lessThanOrEqualTo' => 
      array (
        'name' => 'lessThanOrEqualTo',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2873,
            'endLine' => 2873,
            'startColumn' => 39,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determines if the instance is less (shorter) than or equal to another
 *
 * @param CarbonInterval|DateInterval|mixed $interval
 *
 * @return bool
 */',
        'startLine' => 2873,
        'endLine' => 2876,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'between' => 
      array (
        'name' => 'between',
        'parameters' => 
        array (
          'interval1' => 
          array (
            'name' => 'interval1',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2899,
            'endLine' => 2899,
            'startColumn' => 29,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'interval2' => 
          array (
            'name' => 'interval2',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2899,
            'endLine' => 2899,
            'startColumn' => 41,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'equal' => 
          array (
            'name' => 'equal',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 2899,
                'endLine' => 2899,
                'startTokenPos' => 14878,
                'startFilePos' => 104366,
                'endTokenPos' => 14878,
                'endFilePos' => 104369,
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
            'startLine' => 2899,
            'endLine' => 2899,
            'startColumn' => 53,
            'endColumn' => 70,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determines if the instance is between two others.
 *
 * The third argument allow you to specify if bounds are included or not (true by default)
 * but for when you including/excluding bounds may produce different results in your application,
 * we recommend to use the explicit methods ->betweenIncluded() or ->betweenExcluded() instead.
 *
 * @example
 * ```
 * CarbonInterval::hours(48)->between(CarbonInterval::day(), CarbonInterval::days(3)); // true
 * CarbonInterval::hours(48)->between(CarbonInterval::day(), CarbonInterval::hours(36)); // false
 * CarbonInterval::hours(48)->between(CarbonInterval::day(), CarbonInterval::days(2)); // true
 * CarbonInterval::hours(48)->between(CarbonInterval::day(), CarbonInterval::days(2), false); // false
 * ```
 *
 * @param CarbonInterval|DateInterval|mixed $interval1
 * @param CarbonInterval|DateInterval|mixed $interval2
 * @param bool                              $equal     Indicates if an equal to comparison should be done
 *
 * @return bool
 */',
        'startLine' => 2899,
        'endLine' => 2904,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'betweenIncluded' => 
      array (
        'name' => 'betweenIncluded',
        'parameters' => 
        array (
          'interval1' => 
          array (
            'name' => 'interval1',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2921,
            'endLine' => 2921,
            'startColumn' => 37,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'interval2' => 
          array (
            'name' => 'interval2',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2921,
            'endLine' => 2921,
            'startColumn' => 49,
            'endColumn' => 58,
            'parameterIndex' => 1,
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
 * Determines if the instance is between two others, bounds excluded.
 *
 * @example
 * ```
 * CarbonInterval::hours(48)->betweenExcluded(CarbonInterval::day(), CarbonInterval::days(3)); // true
 * CarbonInterval::hours(48)->betweenExcluded(CarbonInterval::day(), CarbonInterval::hours(36)); // false
 * CarbonInterval::hours(48)->betweenExcluded(CarbonInterval::day(), CarbonInterval::days(2)); // true
 * ```
 *
 * @param CarbonInterval|DateInterval|mixed $interval1
 * @param CarbonInterval|DateInterval|mixed $interval2
 *
 * @return bool
 */',
        'startLine' => 2921,
        'endLine' => 2924,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'betweenExcluded' => 
      array (
        'name' => 'betweenExcluded',
        'parameters' => 
        array (
          'interval1' => 
          array (
            'name' => 'interval1',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2941,
            'endLine' => 2941,
            'startColumn' => 37,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'interval2' => 
          array (
            'name' => 'interval2',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2941,
            'endLine' => 2941,
            'startColumn' => 49,
            'endColumn' => 58,
            'parameterIndex' => 1,
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
 * Determines if the instance is between two others, bounds excluded.
 *
 * @example
 * ```
 * CarbonInterval::hours(48)->betweenExcluded(CarbonInterval::day(), CarbonInterval::days(3)); // true
 * CarbonInterval::hours(48)->betweenExcluded(CarbonInterval::day(), CarbonInterval::hours(36)); // false
 * CarbonInterval::hours(48)->betweenExcluded(CarbonInterval::day(), CarbonInterval::days(2)); // false
 * ```
 *
 * @param CarbonInterval|DateInterval|mixed $interval1
 * @param CarbonInterval|DateInterval|mixed $interval2
 *
 * @return bool
 */',
        'startLine' => 2941,
        'endLine' => 2944,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'isBetween' => 
      array (
        'name' => 'isBetween',
        'parameters' => 
        array (
          'interval1' => 
          array (
            'name' => 'interval1',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2963,
            'endLine' => 2963,
            'startColumn' => 31,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'interval2' => 
          array (
            'name' => 'interval2',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2963,
            'endLine' => 2963,
            'startColumn' => 43,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'equal' => 
          array (
            'name' => 'equal',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 2963,
                'endLine' => 2963,
                'startTokenPos' => 15023,
                'startFilePos' => 106950,
                'endTokenPos' => 15023,
                'endFilePos' => 106953,
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
            'startLine' => 2963,
            'endLine' => 2963,
            'startColumn' => 55,
            'endColumn' => 72,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determines if the instance is between two others
 *
 * @example
 * ```
 * CarbonInterval::hours(48)->isBetween(CarbonInterval::day(), CarbonInterval::days(3)); // true
 * CarbonInterval::hours(48)->isBetween(CarbonInterval::day(), CarbonInterval::hours(36)); // false
 * CarbonInterval::hours(48)->isBetween(CarbonInterval::day(), CarbonInterval::days(2)); // true
 * CarbonInterval::hours(48)->isBetween(CarbonInterval::day(), CarbonInterval::days(2), false); // false
 * ```
 *
 * @param CarbonInterval|DateInterval|mixed $interval1
 * @param CarbonInterval|DateInterval|mixed $interval2
 * @param bool                              $equal     Indicates if an equal to comparison should be done
 *
 * @return bool
 */',
        'startLine' => 2963,
        'endLine' => 2966,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'roundUnit' => 
      array (
        'name' => 'roundUnit',
        'parameters' => 
        array (
          'unit' => 
          array (
            'name' => 'unit',
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
            'startLine' => 2973,
            'endLine' => 2973,
            'startColumn' => 31,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'precision' => 
          array (
            'name' => 'precision',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 2973,
                'endLine' => 2973,
                'startTokenPos' => 15074,
                'startFilePos' => 107283,
                'endTokenPos' => 15074,
                'endFilePos' => 107283,
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
                      'name' => 'DateInterval',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
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
            'startLine' => 2973,
            'endLine' => 2973,
            'startColumn' => 45,
            'endColumn' => 88,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'function' => 
          array (
            'name' => 'function',
            'default' => 
            array (
              'code' => '\'round\'',
              'attributes' => 
              array (
                'startLine' => 2973,
                'endLine' => 2973,
                'startTokenPos' => 15083,
                'startFilePos' => 107305,
                'endTokenPos' => 15083,
                'endFilePos' => 107311,
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
            'startLine' => 2973,
            'endLine' => 2973,
            'startColumn' => 91,
            'endColumn' => 116,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Round the current instance at the given unit with given precision if specified and the given function.
 *
 * @throws Exception
 */',
        'startLine' => 2973,
        'endLine' => 3000,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'floorUnit' => 
      array (
        'name' => 'floorUnit',
        'parameters' => 
        array (
          'unit' => 
          array (
            'name' => 'unit',
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
            'startLine' => 3012,
            'endLine' => 3012,
            'startColumn' => 31,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'precision' => 
          array (
            'name' => 'precision',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 3012,
                'endLine' => 3012,
                'startTokenPos' => 15322,
                'startFilePos' => 108521,
                'endTokenPos' => 15322,
                'endFilePos' => 108521,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3012,
            'endLine' => 3012,
            'startColumn' => 45,
            'endColumn' => 58,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Truncate the current instance at the given unit with given precision if specified.
 *
 * @param string                             $unit
 * @param float|int|string|DateInterval|null $precision
 *
 * @throws Exception
 *
 * @return $this
 */',
        'startLine' => 3012,
        'endLine' => 3015,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'ceilUnit' => 
      array (
        'name' => 'ceilUnit',
        'parameters' => 
        array (
          'unit' => 
          array (
            'name' => 'unit',
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
            'startLine' => 3027,
            'endLine' => 3027,
            'startColumn' => 30,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'precision' => 
          array (
            'name' => 'precision',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 3027,
                'endLine' => 3027,
                'startTokenPos' => 15365,
                'startFilePos' => 108946,
                'endTokenPos' => 15365,
                'endFilePos' => 108946,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3027,
            'endLine' => 3027,
            'startColumn' => 44,
            'endColumn' => 57,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Ceil the current instance at the given unit with given precision if specified.
 *
 * @param string                             $unit
 * @param float|int|string|DateInterval|null $precision
 *
 * @throws Exception
 *
 * @return $this
 */',
        'startLine' => 3027,
        'endLine' => 3030,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'round' => 
      array (
        'name' => 'round',
        'parameters' => 
        array (
          'precision' => 
          array (
            'name' => 'precision',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 3042,
                'endLine' => 3042,
                'startTokenPos' => 15403,
                'startFilePos' => 109347,
                'endTokenPos' => 15403,
                'endFilePos' => 109347,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3042,
            'endLine' => 3042,
            'startColumn' => 27,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'function' => 
          array (
            'name' => 'function',
            'default' => 
            array (
              'code' => '\'round\'',
              'attributes' => 
              array (
                'startLine' => 3042,
                'endLine' => 3042,
                'startTokenPos' => 15412,
                'startFilePos' => 109369,
                'endTokenPos' => 15412,
                'endFilePos' => 109375,
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
            'startLine' => 3042,
            'endLine' => 3042,
            'startColumn' => 43,
            'endColumn' => 68,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Round the current instance second with given precision if specified.
 *
 * @param float|int|string|DateInterval|null $precision
 * @param string                             $function
 *
 * @throws Exception
 *
 * @return $this
 */',
        'startLine' => 3042,
        'endLine' => 3045,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'floor' => 
      array (
        'name' => 'floor',
        'parameters' => 
        array (
          'precision' => 
          array (
            'name' => 'precision',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 3054,
                'endLine' => 3054,
                'startTokenPos' => 15455,
                'startFilePos' => 109676,
                'endTokenPos' => 15455,
                'endFilePos' => 109676,
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
                      'name' => 'DateInterval',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
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
            'startLine' => 3054,
            'endLine' => 3054,
            'startColumn' => 27,
            'endColumn' => 70,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Round the current instance second with given precision if specified.
 *
 * @throws Exception
 *
 * @return $this
 */',
        'startLine' => 3054,
        'endLine' => 3057,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'ceil' => 
      array (
        'name' => 'ceil',
        'parameters' => 
        array (
          'precision' => 
          array (
            'name' => 'precision',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 3066,
                'endLine' => 3066,
                'startTokenPos' => 15498,
                'startFilePos' => 109969,
                'endTokenPos' => 15498,
                'endFilePos' => 109969,
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
                      'name' => 'DateInterval',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
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
            'startLine' => 3066,
            'endLine' => 3066,
            'startColumn' => 26,
            'endColumn' => 69,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Ceil the current instance second with given precision if specified.
 *
 * @throws Exception
 *
 * @return $this
 */',
        'startLine' => 3066,
        'endLine' => 3069,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      '__unserialize' => 
      array (
        'name' => '__unserialize',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
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
            'startLine' => 3071,
            'endLine' => 3071,
            'startColumn' => 35,
            'endColumn' => 45,
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
        'startLine' => 3071,
        'endLine' => 3137,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'withOriginal' => 
      array (
        'name' => 'withOriginal',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3147,
            'endLine' => 3147,
            'startColumn' => 42,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'original' => 
          array (
            'name' => 'original',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3147,
            'endLine' => 3147,
            'startColumn' => 59,
            'endColumn' => 73,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @template T
 *
 * @param T     $interval
 * @param mixed $original
 *
 * @return T
 */',
        'startLine' => 3147,
        'endLine' => 3154,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'standardizeUnit' => 
      array (
        'name' => 'standardizeUnit',
        'parameters' => 
        array (
          'unit' => 
          array (
            'name' => 'unit',
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
            'startLine' => 3156,
            'endLine' => 3156,
            'startColumn' => 45,
            'endColumn' => 56,
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
        'startLine' => 3156,
        'endLine' => 3161,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getFlipCascadeFactors' => 
      array (
        'name' => 'getFlipCascadeFactors',
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
        'docComment' => NULL,
        'startLine' => 3163,
        'endLine' => 3174,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'castIntervalToClass' => 
      array (
        'name' => 'castIntervalToClass',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateInterval',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3185,
            'endLine' => 3185,
            'startColumn' => 49,
            'endColumn' => 70,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'className' => 
          array (
            'name' => 'className',
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
            'startLine' => 3185,
            'endLine' => 3185,
            'startColumn' => 73,
            'endColumn' => 89,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'skip' => 
          array (
            'name' => 'skip',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 3185,
                'endLine' => 3185,
                'startTokenPos' => 16361,
                'startFilePos' => 113911,
                'endTokenPos' => 16362,
                'endFilePos' => 113912,
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
            'startLine' => 3185,
            'endLine' => 3185,
            'startColumn' => 92,
            'endColumn' => 107,
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
            'name' => 'object',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @template T of DateInterval
 *
 * @param DateInterval $interval
 *
 * @psalm-param class-string<T> $className
 *
 * @return T
 */',
        'startLine' => 3185,
        'endLine' => 3211,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'buildInstance' => 
      array (
        'name' => 'buildInstance',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateInterval',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3223,
            'endLine' => 3223,
            'startColumn' => 9,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'className' => 
          array (
            'name' => 'className',
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
            'startLine' => 3224,
            'endLine' => 3224,
            'startColumn' => 9,
            'endColumn' => 25,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'skip' => 
          array (
            'name' => 'skip',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 3225,
                'endLine' => 3225,
                'startTokenPos' => 16576,
                'startFilePos' => 114978,
                'endTokenPos' => 16577,
                'endFilePos' => 114979,
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
            'startLine' => 3225,
            'endLine' => 3225,
            'startColumn' => 9,
            'endColumn' => 24,
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
            'name' => 'object',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @template T of DateInterval
 *
 * @param DateInterval $interval
 *
 * @psalm-param class-string<T> $className
 *
 * @return T
 */',
        'startLine' => 3222,
        'endLine' => 3233,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'buildSerializationString' => 
      array (
        'name' => 'buildSerializationString',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateInterval',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3247,
            'endLine' => 3247,
            'startColumn' => 9,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'className' => 
          array (
            'name' => 'className',
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
            'startLine' => 3248,
            'endLine' => 3248,
            'startColumn' => 9,
            'endColumn' => 25,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'skip' => 
          array (
            'name' => 'skip',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 3249,
                'endLine' => 3249,
                'startTokenPos' => 16680,
                'startFilePos' => 115972,
                'endTokenPos' => 16681,
                'endFilePos' => 115973,
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
            'startLine' => 3249,
            'endLine' => 3249,
            'startColumn' => 9,
            'endColumn' => 24,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * As demonstrated by rlanvin (https://github.com/rlanvin) in
 * https://github.com/briannesbitt/Carbon/issues/3018#issuecomment-2888538438
 *
 * Modifying the output of serialize() to change the class name and unserializing
 * the tweaked string allows creating new interval instances where the ->days
 * property can be set. It\'s not possible neither with `new` nto with `__set_state`.
 *
 * It has a non-negligible performance cost, so we\'ll use this method only if
 * $interval->days !== false.
 */',
        'startLine' => 3246,
        'endLine' => 3288,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'copyStep' => 
      array (
        'name' => 'copyStep',
        'parameters' => 
        array (
          'from' => 
          array (
            'name' => 'from',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'self',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3290,
            'endLine' => 3290,
            'startColumn' => 38,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'to' => 
          array (
            'name' => 'to',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'self',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3290,
            'endLine' => 3290,
            'startColumn' => 50,
            'endColumn' => 57,
            'parameterIndex' => 1,
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
        'startLine' => 3290,
        'endLine' => 3293,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'copyNegativeUnits' => 
      array (
        'name' => 'copyNegativeUnits',
        'parameters' => 
        array (
          'from' => 
          array (
            'name' => 'from',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateInterval',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3295,
            'endLine' => 3295,
            'startColumn' => 47,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'to' => 
          array (
            'name' => 'to',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateInterval',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3295,
            'endLine' => 3295,
            'startColumn' => 67,
            'endColumn' => 82,
            'parameterIndex' => 1,
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
        'startLine' => 3295,
        'endLine' => 3304,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'invertCascade' => 
      array (
        'name' => 'invertCascade',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 3306,
            'endLine' => 3306,
            'startColumn' => 36,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 3306,
        'endLine' => 3311,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'doCascade' => 
      array (
        'name' => 'doCascade',
        'parameters' => 
        array (
          'deep' => 
          array (
            'name' => 'deep',
            'default' => NULL,
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
            'startLine' => 3313,
            'endLine' => 3313,
            'startColumn' => 32,
            'endColumn' => 41,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 3313,
        'endLine' => 3378,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'needsDeclension' => 
      array (
        'name' => 'needsDeclension',
        'parameters' => 
        array (
          'mode' => 
          array (
            'name' => 'mode',
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
            'startLine' => 3380,
            'endLine' => 3380,
            'startColumn' => 38,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'index' => 
          array (
            'name' => 'index',
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
            'startLine' => 3380,
            'endLine' => 3380,
            'startColumn' => 52,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'parts' => 
          array (
            'name' => 'parts',
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
            'startLine' => 3380,
            'endLine' => 3380,
            'startColumn' => 64,
            'endColumn' => 73,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 3380,
        'endLine' => 3386,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'checkIntegerValue' => 
      array (
        'name' => 'checkIntegerValue',
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
            'startLine' => 3388,
            'endLine' => 3388,
            'startColumn' => 40,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3388,
            'endLine' => 3388,
            'startColumn' => 54,
            'endColumn' => 65,
            'parameterIndex' => 1,
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
        'startLine' => 3388,
        'endLine' => 3411,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'assertSafeForInteger' => 
      array (
        'name' => 'assertSafeForInteger',
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
            'startLine' => 3416,
            'endLine' => 3416,
            'startColumn' => 43,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3416,
            'endLine' => 3416,
            'startColumn' => 57,
            'endColumn' => 68,
            'parameterIndex' => 1,
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
 * Throw an exception if precision loss when storing the given value as an integer would be >= 1.0.
 */',
        'startLine' => 3416,
        'endLine' => 3421,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'handleDecimalPart' => 
      array (
        'name' => 'handleDecimalPart',
        'parameters' => 
        array (
          'unit' => 
          array (
            'name' => 'unit',
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
            'startLine' => 3423,
            'endLine' => 3423,
            'startColumn' => 40,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3423,
            'endLine' => 3423,
            'startColumn' => 54,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'integerValue' => 
          array (
            'name' => 'integerValue',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3423,
            'endLine' => 3423,
            'startColumn' => 68,
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
        'docComment' => NULL,
        'startLine' => 3423,
        'endLine' => 3460,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'getInnerValues' => 
      array (
        'name' => 'getInnerValues',
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
        'docComment' => NULL,
        'startLine' => 3462,
        'endLine' => 3465,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'checkStartAndEnd' => 
      array (
        'name' => 'checkStartAndEnd',
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
        'docComment' => NULL,
        'startLine' => 3467,
        'endLine' => 3479,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'setSetting' => 
      array (
        'name' => 'setSetting',
        'parameters' => 
        array (
          'setting' => 
          array (
            'name' => 'setting',
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
            'startLine' => 3482,
            'endLine' => 3482,
            'startColumn' => 33,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3482,
            'endLine' => 3482,
            'startColumn' => 50,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return $this */',
        'startLine' => 3482,
        'endLine' => 3515,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'incrementUnit' => 
      array (
        'name' => 'incrementUnit',
        'parameters' => 
        array (
          'instance' => 
          array (
            'name' => 'instance',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateInterval',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3517,
            'endLine' => 3517,
            'startColumn' => 43,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'unit' => 
          array (
            'name' => 'unit',
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
            'startLine' => 3517,
            'endLine' => 3517,
            'startColumn' => 67,
            'endColumn' => 78,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
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
            'startLine' => 3517,
            'endLine' => 3517,
            'startColumn' => 81,
            'endColumn' => 90,
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
        'docComment' => NULL,
        'startLine' => 3517,
        'endLine' => 3533,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
      'setIntervalUnit' => 
      array (
        'name' => 'setIntervalUnit',
        'parameters' => 
        array (
          'instance' => 
          array (
            'name' => 'instance',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateInterval',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3536,
            'endLine' => 3536,
            'startColumn' => 45,
            'endColumn' => 66,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'unit' => 
          array (
            'name' => 'unit',
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
            'startLine' => 3536,
            'endLine' => 3536,
            'startColumn' => 69,
            'endColumn' => 80,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3536,
            'endLine' => 3536,
            'startColumn' => 83,
            'endColumn' => 94,
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
        'docComment' => '/** @codeCoverageIgnore */',
        'startLine' => 3536,
        'endLine' => 3572,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Carbon',
        'declaringClassName' => 'Carbon\\CarbonInterval',
        'implementingClassName' => 'Carbon\\CarbonInterval',
        'currentClassName' => 'Carbon\\CarbonInterval',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
        'Carbon\\Traits\\Mixin' => 
        array (
          0 => 
          array (
            'alias' => 'baseMixin',
            'method' => 'mixin',
            'hash' => 'carbon\\traits\\mixin::mixin',
          ),
        ),
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
        'carbon\\traits\\mixin::mixin' => 'Carbon\\Traits\\Mixin::mixin',
      ),
    ),
  ),
));