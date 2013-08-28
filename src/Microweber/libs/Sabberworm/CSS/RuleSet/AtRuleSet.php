<?php

namespace Sabberworm\CSS\RuleSet;

use Sabberworm\CSS\Property\AtRule;

/**
 * A RuleSet constructed by an unknown @-rule. @font-face rules are rendered into AtRule objects.
 */
class AtRuleSet extends RuleSet implements AtRule {

	private $sType;
	private $sArgs;

	public function __construct($sType, $sArgs = '') {
		parent::__construct();
		$this->sType = $sType;
		$this->sArgs = $sArgs;
	}

	public function atRuleName() {
		return $this->sType;
	}

	public function atRuleArgs() {
		return $this->sArgs;
	}

	public function __toString() {
		$sResult = "@{$this->sType} {$this->sArgs}{";
		$sResult .= parent::__toString();
		$sResult .= '}';
		return $sResult;
	}

}