<?php

namespace Sabberworm\CSS\Value;

class RuleValueList extends ValueList {

	public function __construct($sSeparator = ',') {
		parent::__construct(array(), $sSeparator);
	}

}