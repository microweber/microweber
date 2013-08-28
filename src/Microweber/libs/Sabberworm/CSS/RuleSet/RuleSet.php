<?php

namespace Sabberworm\CSS\RuleSet;

use Sabberworm\CSS\Rule\Rule;

/**
 * RuleSet is a generic superclass denoting rules. The typical example for rule sets are declaration block.
 * However, unknown At-Rules (like @font-face) are also rule sets.
 */
abstract class RuleSet {

	private $aRules;

	public function __construct() {
		$this->aRules = array();
	}

	public function addRule(Rule $oRule) {
		$sRule = $oRule->getRule();
		if(!isset($this->aRules[$sRule])) {
			$this->aRules[$sRule] = array();
		}
		$this->aRules[$sRule][] = $oRule;
	}

	/**
	 * Returns all rules matching the given rule name
	 * @param (null|string|Rule) $mRule pattern to search for. If null, returns all rules. if the pattern ends with a dash, all rules starting with the pattern are returned as well as one matching the pattern with the dash excluded. passing a Rule behaves like calling getRules($mRule->getRule()).
	 * @example $oRuleSet->getRules('font-') //returns an array of all rules either beginning with font- or matching font.
	 * @example $oRuleSet->getRules('font') //returns array(0 => $oRule, …) or array().
	 */
	public function getRules($mRule = null) {
		if ($mRule instanceof Rule) {
			$mRule = $mRule->getRule();
		}
		$aResult = array();
		foreach($this->aRules as $sName => $aRules) {
			// Either no search rule is given or the search rule matches the found rule exactly or the search rule ends in “-” and the found rule starts with the search rule.
			if(!$mRule || $sName === $mRule || (strrpos($mRule, '-') === strlen($mRule) - strlen('-') && (strpos($sName, $mRule) === 0 || $sName === substr($mRule, 0, -1)))) {
				$aResult = array_merge($aResult, $aRules);
			}
		}
		return $aResult;
	}
	
	/**
	 * Returns all rules matching the given pattern and returns them in an associative array with the rule’s name as keys. This method exists mainly for backwards-compatibility and is really only partially useful.
	 * @param (string) $mRule pattern to search for. If null, returns all rules. if the pattern ends with a dash, all rules starting with the pattern are returned as well as one matching the pattern with the dash excluded. passing a Rule behaves like calling getRules($mRule->getRule()).
	 * Note: This method loses some information: Calling this (with an argument of 'background-') on a declaration block like { background-color: green; background-color; rgba(0, 127, 0, 0.7); } will only yield an associative array containing the rgba-valued rule while @link{getRules()} would yield an indexed array containing both.
	 */
	public function getRulesAssoc($mRule = null) {
		$aResult = array();
		foreach($this->getRules($mRule) as $oRule) {
			$aResult[$oRule->getRule()] = $oRule;
		}
		return $aResult;
	}

	/**
	* Remove a rule from this RuleSet. This accepts all the possible values that @link{getRules()} accepts. If given a Rule, it will only remove this particular rule (by identity). If given a name, it will remove all rules by that name. Note: this is different from pre-v.2.0 behaviour of PHP-CSS-Parser, where passing a Rule instance would remove all rules with the same name. To get the old behvaiour, use removeRule($oRule->getRule()).
 * @param (null|string|Rule) $mRule pattern to remove. If $mRule is null, all rules are removed. If the pattern ends in a dash, all rules starting with the pattern are removed as well as one matching the pattern with the dash excluded. Passing a Rule behaves matches by identity.
	*/
	public function removeRule($mRule) {
		if($mRule instanceof Rule) {
			$sRule = $mRule->getRule();
			if(!isset($this->aRules[$sRule])) {
				return;
			}
			foreach($this->aRules[$sRule] as $iKey => $oRule) {
				if($oRule === $mRule) {
					unset($this->aRules[$sRule][$iKey]);
				}
			}
		} else {
			foreach($this->aRules as $sName => $aRules) {
				// Either no search rule is given or the search rule matches the found rule exactly or the search rule ends in “-” and the found rule starts with the search rule or equals it (without the trailing dash).
				if(!$mRule || $sName === $mRule || (strrpos($mRule, '-') === strlen($mRule) - strlen('-') && (strpos($sName, $mRule) === 0 || $sName === substr($mRule, 0, -1)))) {
					unset($this->aRules[$sName]);
				}
			}
		}
	}

	public function __toString() {
		$sResult = '';
		foreach ($this->aRules as $aRules) {
			foreach($aRules as $oRule) {
				$sResult .= $oRule->__toString();
			}
		}
		return $sResult;
	}

}
