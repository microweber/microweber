<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;
use Symfony\Component\Yaml\Yaml;
use Recurr\Transformer\Translator;
use Recurr\Transformer\TextTransformer;

class TextTransformerTest extends \PHPUnit_Framework_TestCase
{
    private static $languages = array();

    /**
     * @dataProvider generateTests
     */
    public function testFormatting($lang, $rule, $expected)
    {
        // Sunday, March 16th is our reference start date
        $dateTime = new \DateTime('2014-03-16 04:00:00');
        $rule = new Rule($rule, $dateTime);

        $transformer = new TextTransformer(new Translator($lang));
        $this->assertEquals(self::$languages[$lang][$expected], $transformer->transform($rule));
    }

    public static function generateTests()
    {
        $baseTests = array(
            // Count
            array(
                'FREQ=YEARLY;COUNT=1',
                'yearly on March 16 for 1 time',
            ),
            // CountPlural
            array(
                'FREQ=YEARLY;COUNT=3',
                'yearly on March 16 for 3 times',
            ),
            // Until
            array(
                'FREQ=YEARLY;UNTIL=20140704T040000Z',
                'yearly on March 16 until July 4, 2014',
            ),
            // FullyConvertible
            array(
                'FREQ=YEARLY;BYHOUR=1',
                'yearly on March 16 (~ approximate)',
            ),
            array(
                'FREQ=YEARLY;BYMINUTE=1',
                'yearly on March 16 (~ approximate)',
            ),
            array(
                'FREQ=YEARLY;BYSECOND=1',
                'yearly on March 16 (~ approximate)',
            ),
            array(
                'FREQ=MONTHLY;BYWEEKNO=50',
                'monthly (~ approximate)',
            ),
            array(
                'FREQ=MONTHLY;BYYEARDAY=200',
                'monthly (~ approximate)',
            ),

            // Monthly
            array(
                'FREQ=MONTHLY',
                'monthly',
            ),
            // MonthlyPlural
            array(
                'FREQ=MONTHLY;INTERVAL=10',
                'every 10 months',
            ),
            // MonthlyByMonth
            array(
                'FREQ=MONTHLY;BYMONTH=1,8,5',
                'every January, May and August'
            ),
            array(
                'FREQ=MONTHLY;INTERVAL=2;BYMONTH=1,8,5',
                'every 2 months in January, May and August',
            ),
            // MonthlyByMonthDay
            array(
                'FREQ=MONTHLY;BYMONTHDAY=5,1,21',
                'monthly on the 1st, 5th and 21st'
            ),
            array(
                'FREQ=MONTHLY;BYMONTHDAY=5,1,21;BYDAY=TU,FR',
                'monthly on Tuesday or Friday the 1st, 5th or 21st',
            ),
            // MonthlyByNegativeMonthDay
            array(
                'FREQ=MONTHLY;BYMONTHDAY=-1,21',
                'monthly on the 21st day and on the last day'
            ),
            array(
                'FREQ=MONTHLY;BYMONTHDAY=-2,1;BYDAY=TU,FR',
                'monthly on Tuesday or Friday the 1st day or 2nd to the last day',
            ),
            // MonthlyByDay
            array(
                'FREQ=MONTHLY;BYDAY=TU,WE,FR',
                'monthly on Tuesday, Wednesday and Friday'
            ),
            array(
                'FREQ=MONTHLY;BYDAY=+4MO',
                'monthly on the 4th Monday',
            ),
            array(
                'FREQ=MONTHLY;BYDAY=+4MO,+2TU',
                'monthly on the 4th Monday and 2nd Tuesday',
            ),
            array(
                'FREQ=MONTHLY;BYDAY=+4MO,+2TU,+3WE',
                'monthly on the 4th Monday, 2nd Tuesday and 3rd Wednesday',
            ),
            array(
                'FREQ=MONTHLY;BYDAY=1MO,+1TU,+2WE,+3WE,+4WE,-1TH,-2FR,-3SA,-4SU',
                'monthly on the 1st Monday, 1st Tuesday, 2nd Wednesday, 3rd Wednesday, 4th Wednesday, last Thursday, 2nd to the last Friday, 3rd to the last Saturday and 4th to the last Sunday',
            ),

            // Daily
            array(
                'FREQ=DAILY',
                'daily',
            ),
            // DailyPlural
            array(
                'FREQ=DAILY;INTERVAL=10',
                'every 10 days',
            ),
            // DailyByMonth
            array(
                'FREQ=DAILY;BYMONTH=1,8,5',
                'daily in January, May and August',
            ),
            array(
                'FREQ=DAILY;INTERVAL=2;BYMONTH=1,8,5',
                'every 2 days in January, May and August',
            ),
            // DailyByMonthDay
            array(
                'FREQ=DAILY;BYMONTHDAY=5,1,21',
                'daily on the 1st, 5th and 21st of the month'
            ),
            array(
                'FREQ=DAILY;BYMONTHDAY=5,1,21;BYDAY=TU,FR',
                'daily on Tuesday or Friday the 1st, 5th or 21st of the month',
            ),
            // DailyByDay
            array(
                'FREQ=DAILY;BYDAY=TU,WE,FR',
                'daily on Tuesday, Wednesday and Friday',
            ),

            // Yearly
            array(
                'FREQ=YEARLY',
                'yearly on March 16',
            ),
            // YearlyPlural
            array(
                'FREQ=YEARLY;INTERVAL=10',
                'every 10 years on March 16',
            ),
            // YearlyByMonth
            array(
                'FREQ=YEARLY;BYMONTH=1,8,5',
                'every January, May and August',
            ),
            array(
                'FREQ=YEARLY;INTERVAL=2;BYMONTH=1,8,5',
                'every 2 years in January, May and August',
            ),
            // ComplexYearly (first Tuesday that comes after a Monday in November, every 4years)
            array(
                'FREQ=YEARLY;INTERVAL=4;BYMONTH=11;BYDAY=TU;BYMONTHDAY=2,3,4,5,6,7,8',
                'every 4 years in November on Tuesday the 2nd, 3rd, 4th, 5th, 6th, 7th or 8th of the month',
            ),
            // YearlyByMonthDay
            array(
                'FREQ=YEARLY;BYMONTHDAY=5,1,21',
                'yearly on the 1st, 5th and 21st of the month',
            ),
            array(
                'FREQ=YEARLY;BYMONTHDAY=5,1,21;BYDAY=TU,FR',
                'yearly on Tuesday or Friday the 1st, 5th or 21st of the month',
            ),
            // YearlyByDay
            array(
                'FREQ=YEARLY;BYDAY=TU,WE,FR',
                'yearly on Tuesday, Wednesday and Friday',
            ),
            // YearlyByYearDay
            array(
                'FREQ=YEARLY;BYYEARDAY=1,200',
                'yearly on the 1st and 200th day',
            ),
            // YearlyByWeekNumber
            array(
                'FREQ=YEARLY;BYWEEKNO=3',
                'yearly in week 3 on Sunday'
            ),
            array(
                'FREQ=YEARLY;BYWEEKNO=3,30,20',
                'yearly in weeks 3, 20 and 30 on Sunday',
            ),

            // Weekly
            array(
                'FREQ=WEEKLY',
                'weekly on Sunday',
            ),
            // WeeklyPlural
            array(
                'FREQ=WEEKLY;INTERVAL=10',
                'every 10 weeks on Sunday',
            ),
            // WeeklyByMonth
            array(
                'FREQ=WEEKLY;BYMONTH=1,8,5',
                'weekly on Sunday in January, May and August'
            ),
            array(
                'FREQ=WEEKLY;INTERVAL=2;BYMONTH=1,8,5',
                'every 2 weeks on Sunday in January, May and August',
            ),
            // WeeklyByMonthDay
            array(
                'FREQ=WEEKLY;BYMONTHDAY=5,1,21',
                'weekly on the 1st, 5th and 21st of the month'
            ),
            array(
                'FREQ=WEEKLY;BYMONTHDAY=5,1,21;BYDAY=TU,FR',
                'weekly on Tuesday or Friday the 1st, 5th or 21st of the month',
            ),
            // WeeklyByDay
            array(
                'FREQ=WEEKLY;BYDAY=TU,WE,FR',
                'weekly on Tuesday, Wednesday and Friday',
            ),

            // Check that start date impacts wording for yearly
            array(
                'FREQ=YEARLY;BYMONTH=3',
                'yearly on March 16'
            ),
            array(
                'FREQ=YEARLY;INTERVAL=2;BYMONTH=3',
                'every 2 years on March 16'
            ),
            array(
                'FREQ=YEARLY;BYMONTH=3;COUNT=5',
                'yearly on March 16 for 5 times'
            ),
            array(
                'FREQ=YEARLY;BYMONTH=3;UNTIL=20121231T235959Z',
                'yearly on March 16 until December 31, 2012'
            ),
        );

        $tests = array();
        foreach (glob(__DIR__.'/Translations/*.yml') as $file) {
            $lang = basename($file, '.yml');
            self::$languages[$lang] = Yaml::parse(file_get_contents($file));
            $tests = array_merge($tests, array_map(function ($test) use ($lang) {
                array_unshift($test, $lang);

                return $test;
            }, $baseTests));
        }

        return $tests;
    }
}
