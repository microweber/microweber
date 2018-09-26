<?php

namespace Recurr\Test\Transformer\Filter;

use Recurr\Transformer\Constraint\BeforeConstraint;

class BeforeConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testBefore()
    {
        $before = new \DateTime('2014-06-17');

        $constraint = new BeforeConstraint($before, false);
        $testResult = $constraint->test(new \DateTime('2014-06-17'));

        $this->assertFalse($testResult);
    }

    public function testBeforeInc()
    {
        $before = new \DateTime('2014-06-17');

        $constraint = new BeforeConstraint($before, true);
        $testResult = $constraint->test(new \DateTime('2014-06-17'));

        $this->assertTrue($testResult);
    }
}