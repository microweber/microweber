<?php

namespace Recurr\Test\Transformer;

use Recurr\Frequency;
use Recurr\Rule;
use Recurr\Transformer\Constraint\AfterConstraint;
use Recurr\Transformer\Constraint\BeforeConstraint;
use Recurr\Transformer\Constraint\BetweenConstraint;

class ArrayTransformerConstraintTest extends ArrayTransformerBase
{
    /**
     * @param array $testCases
     * @return array
     */
    private function prependDateTimeClassNames($testCases)
    {
        $data = [];

        foreach ($testCases as $n => $testCase) {
            $immutable = $testCases[$n];
            array_unshift($immutable, \DateTimeImmutable::class);

            $mutable = $testCases[$n];
            array_unshift($mutable, \DateTime::class);

            array_push($data, $immutable, $mutable);
        }

        return $data;
    }

    /**
     * @dataProvider beforeProvider
     *
     * @param string $dateTimeClassName \DateTimeImmutable or \DateTime
     * @param int    $frequency
     * @param int    $count
     * @param string $start
     * @param string $before
     * @param bool   $inc
     * @param array  $expected
     */
    public function testBefore($dateTimeClassName, $frequency, $count, $start, $before, $inc, $expected)
    {
        $rule = new Rule();
        $rule
            ->setFreq($frequency)
            ->setCount($count)
            ->setStartDate(new $dateTimeClassName($start))
        ;

        $constraint = new BeforeConstraint(new $dateTimeClassName($before), $inc);
        $computed = $this->transformer->transform($rule, $constraint);

        self::assertCount(count($expected), $computed);

        foreach ($expected as $n => $expectedDate) {
            self::assertEquals(new $dateTimeClassName($expectedDate), $computed[$n]->getStart());
        }
    }

    public function beforeProvider()
    {
        return $this->prependDateTimeClassNames([
            [Frequency::YEARLY, 20, '2014-03-16 04:00:00', '2017-03-16 23:59:59', true, [
                '2014-03-16 04:00:00',
                '2015-03-16 04:00:00',
                '2016-03-16 04:00:00',
                '2017-03-16 04:00:00',
            ]],
            [Frequency::MONTHLY, 5, '2014-03-16 04:00:00', '2014-05-16 04:00:00', false, [
                '2014-03-16 04:00:00',
                '2014-04-16 04:00:00',
            ]],
            [Frequency::MONTHLY, 5, '2014-03-16 04:00:00', '2014-05-16 04:00:00', true, [
                '2014-03-16 04:00:00',
                '2014-04-16 04:00:00',
                '2014-05-16 04:00:00',
            ]],
        ]);
    }

    /**
     * @dataProvider afterProvider
     *
     * @param string $dateTimeClassName \DateTimeImmutable or \DateTime
     * @param int    $frequency
     * @param int    $count
     * @param string $start
     * @param string $after
     * @param bool   $inc
     * @param bool   $countConstraintFailures
     * @param array  $expected
     */
    public function testAfter($dateTimeClassName, $frequency, $count, $start, $after, $inc, $countConstraintFailures, $expected)
    {
        $rule = new Rule();
        $rule
            ->setFreq($frequency)
            ->setCount($count)
            ->setStartDate(new $dateTimeClassName($start))
        ;

        $constraint = new AfterConstraint(new $dateTimeClassName($after), $inc);
        $computed = $this->transformer->transform($rule, $constraint, $countConstraintFailures);

        self::assertCount(count($expected), $computed);

        foreach ($expected as $n => $expectedDate) {
            self::assertEquals(new $dateTimeClassName($expectedDate), $computed[$n]->getStart());
        }
    }

    public function afterProvider()
    {
        return $this->prependDateTimeClassNames([
            [Frequency::MONTHLY, 5, '2014-03-16 04:00:00', '2020-05-16 04:00:00', false, false, [
                '2020-06-16 04:00:00',
                '2020-07-16 04:00:00',
                '2020-08-16 04:00:00',
                '2020-09-16 04:00:00',
                '2020-10-16 04:00:00'
            ]],
            [Frequency::MONTHLY, 5, '2014-03-16 04:00:00', '2014-05-16 04:00:00', false, true, [
                '2014-06-16 04:00:00',
                '2014-07-16 04:00:00',
            ]],
            [Frequency::MONTHLY, 5, '2014-03-16 04:00:00', '2014-05-16 04:00:00', false, false, [
                '2014-06-16 04:00:00',
                '2014-07-16 04:00:00',
                '2014-08-16 04:00:00',
                '2014-09-16 04:00:00',
                '2014-10-16 04:00:00',
            ]],
            [Frequency::MONTHLY, 5, '2014-03-16 04:00:00', '2014-05-16 04:00:00', true, true, [
                '2014-05-16 04:00:00',
                '2014-06-16 04:00:00',
                '2014-07-16 04:00:00',
            ]],
            [Frequency::MONTHLY, 5, '2014-03-16 04:00:00', '2014-05-16 04:00:00', true, false, [
                '2014-05-16 04:00:00',
                '2014-06-16 04:00:00',
                '2014-07-16 04:00:00',
                '2014-08-16 04:00:00',
                '2014-09-16 04:00:00',
            ]],
        ]);
    }

    /**
     * @dataProvider betweenProvider
     *
     * @param string $dateTimeClassName \DateTimeImmutable or \DateTime
     * @param int    $frequency
     * @param string $start
     * @param string $after
     * @param string $before
     * @param bool   $inc
     * @param array  $expected
     */
    public function testBetween($dateTimeClassName, $frequency, $start, $after, $before, $inc, $expected)
    {
        $rule = new Rule();
        $rule
            ->setFreq($frequency)
            ->setStartDate(new $dateTimeClassName($start))
        ;

        $constraint = new BetweenConstraint(
            new $dateTimeClassName($after), new $dateTimeClassName($before), $inc
        );
        $computed = $this->transformer->transform($rule, $constraint);

        self::assertCount(count($expected), $computed);

        foreach ($expected as $n => $expectedDate) {
            self::assertEquals(new $dateTimeClassName($expectedDate), $computed[$n]->getStart());
        }
    }

    public function betweenProvider()
    {
        return $this->prependDateTimeClassNames([
            [Frequency::MONTHLY, '2014-03-16 04:00:00', '2014-03-16 04:00:00', '2014-07-16 04:00:00', false, [
                '2014-04-16 04:00:00',
                '2014-05-16 04:00:00',
                '2014-06-16 04:00:00',
            ]],
            [Frequency::MONTHLY, '2014-03-16 04:00:00', '2014-03-16 04:00:00', '2014-07-16 04:00:00', true, [
                '2014-03-16 04:00:00',
                '2014-04-16 04:00:00',
                '2014-05-16 04:00:00',
                '2014-06-16 04:00:00',
                '2014-07-16 04:00:00',
            ]],
            [Frequency::WEEKLY, '2017-07-03 09:30:00', '2017-07-16 23:00:00', '2017-07-21 22:59:59', true, [
                '2017-07-17 09:30:00'
            ]],
            [Frequency::DAILY, '2017-07-24 16:15:00', '2017-07-27 00:00:00', '2017-07-30 23:59:59', true, [
                '2017-07-27 16:15:00',
                '2017-07-28 16:15:00',
                '2017-07-29 16:15:00',
                '2017-07-30 16:15:00',
            ]],
            [Frequency::HOURLY, '2017-07-24 16:15:00', '2017-07-24 17:30:00', '2017-07-24 18:30:00', false, [
                '2017-07-24 18:15:00',
            ]],
            [Frequency::MINUTELY, '2017-07-24 16:15:00', '2017-07-24 17:30:00', '2017-07-24 17:40:00', false, [
                '2017-07-24 17:31:00',
                '2017-07-24 17:32:00',
                '2017-07-24 17:33:00',
                '2017-07-24 17:34:00',
                '2017-07-24 17:35:00',
                '2017-07-24 17:36:00',
                '2017-07-24 17:37:00',
                '2017-07-24 17:38:00',
                '2017-07-24 17:39:00',
            ]],
        ]);
    }
}
