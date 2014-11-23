<?php

namespace Goodby\CSV\Export\Tests\Standard\Unit\Collection;

use Goodby\CSV\Export\Standard\Collection\CallbackCollection;
use Goodby\CSV\Export\Tests\Standard\Unit\Collection\SampleAggIterator;

class CallbackCollectionTest extends \PHPUnit_Framework_TestCase
{
	public function testSample()
	{
        $data = array();
        $data[] = array('user', 'name1');
        $data[] = array('user', 'name2');
        $data[] = array('user', 'name3');

        $collection = new CallbackCollection($data, function($mixed) {
            return $mixed;
        });

        $index = 1;
        foreach ($collection as $each) {
            $this->assertEquals($each[0], 'user');
            $this->assertEquals($each[1], 'name' . $index);
            $index++;
        }
	}
	
	public function testIteratorAggregate()
	{
	
		$data = array();
        $data[] = array('user', 'name1');
        $data[] = array('user', 'name2');
        $data[] = array('user', 'name3');
	
		$iterator = new SampleAggIterator($data);
		
		$collection = new CallbackCollection($iterator, function($mixed) {
            return $mixed;
        });
		
		$index = 1;
        foreach ($collection as $each) {
            $this->assertEquals($each[0], 'user');
            $this->assertEquals($each[1], 'name' . $index);
            $index++;
        }
	}
}
