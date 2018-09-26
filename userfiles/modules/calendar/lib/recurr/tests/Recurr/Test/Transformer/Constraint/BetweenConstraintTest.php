<?php

namespace Recurr\Test\Transformer\Filter;

use Recurr\Transformer\Constraint\BetweenConstraint;

class BetweenConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testBetween()
    {
        $after  = new \DateTime('2014-06-10');
        $before = new \DateTime('2014-06-17');

        $constraint = new BetweenConstraint($after, $before, false);
        $testResult = $constraint->test(new \DateTime('2014-06-17'));

        $this->assertFalse($testResult);
    }

    public function testBetweenInc()
    {
        $after  = new \DateTime('2014-06-10');
        $before = new \DateTime('2014-06-17');

        $constraint = new BetweenConstraint($after, $before, true);
        $testResult = $constraint->test(new \DateTime('2014-06-17'));

        $this->assertTrue($testResult);
    }
}
