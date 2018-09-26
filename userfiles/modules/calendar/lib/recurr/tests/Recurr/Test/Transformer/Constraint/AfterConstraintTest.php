<?php

namespace Recurr\Test\Transformer\Filter;

use Recurr\Transformer\Constraint\AfterConstraint;

class AfterConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testAfter()
    {
        $after = new \DateTime('2014-06-17');

        $constraint = new AfterConstraint($after, false);
        $testResult = $constraint->test(new \DateTime('2014-06-17'));

        $this->assertFalse($testResult);
    }

    public function testAfterInc()
    {
        $after = new \DateTime('2014-06-17');

        $constraint = new AfterConstraint($after, true);
        $testResult = $constraint->test(new \DateTime('2014-06-17'));

        $this->assertTrue($testResult);
    }
}