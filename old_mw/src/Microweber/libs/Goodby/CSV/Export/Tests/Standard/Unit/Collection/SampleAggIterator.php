<?php

namespace Goodby\CSV\Export\Tests\Standard\Unit\Collection;
use \ArrayIterator;

class SampleAggIterator implements \IteratorAggregate
{
	protected $data;
	
	public function __construct($data)
	{
		$this->data = $data;
	}
	
	public function getIterator()
	{
		return new ArrayIterator($this->data);
	}
}
