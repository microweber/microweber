<?php

namespace Sabberworm\CSS;

use Sabberworm\CSS\CSSList\CSSList;
use Sabberworm\CSS\CSSList\Document;
use Sabberworm\CSS\CSSList\KeyFrame;
use Sabberworm\CSS\Property\AtRule;
use Sabberworm\CSS\Property\Import;
use Sabberworm\CSS\Property\Charset;
use Sabberworm\CSS\Property\CSSNamespace;
use Sabberworm\CSS\RuleSet\AtRuleSet;
use Sabberworm\CSS\CSSList\AtRuleBlockList;
use Sabberworm\CSS\RuleSet\DeclarationBlock;
use Sabberworm\CSS\Value\CSSFunction;
use Sabberworm\CSS\Value\RuleValueList;
use Sabberworm\CSS\Value\Size;
use Sabberworm\CSS\Value\Color;
use Sabberworm\CSS\Value\URL;
use Sabberworm\CSS\Value\String;
use Sabberworm\CSS\Rule\Rule;
use Sabberworm\CSS\Parsing\UnexpectedTokenException;

/**
 * Parser class parses CSS from text into a data structure.
 */
class Parser {

	private $sText;
	private $iCurrentPosition;
	private $oParserSettings;
	private $sCharset;
	private $iLength;

	public function __construct($sText, Settings $oParserSettings = null) {
		$this->sText = $sText;
		$this->iCurrentPosition = 0;
		if ($oParserSettings === null) {
			$oParserSettings = Settings::create();
		}
		$this->oParserSettings = $oParserSettings;
	}

	public function setCharset($sCharset) {
		$this->sCharset = $sCharset;
		$this->iLength = $this->strlen($this->sText);
	}

	public function getCharset() {
		return $this->sCharset;
	}

	public function parse() {
		$this->setCharset($this->oParserSettings->sDefaultCharset);
		$oResult = new Document();
		$this->parseDocument($oResult);
		return $oResult;
	}

	private function parseDocument(Document $oDocument) {
		$this->consumeWhiteSpace();
		$this->parseList($oDocument, true);
	}

	private function parseList(CSSList $oList, $bIsRoot = false) {
		while (!$this->isEnd()) {
			if ($this->comes('@')) {
				$oList->append($this->parseAtRule());
			} else if ($this->comes('}')) {
				$this->consume('}');
				if ($bIsRoot) {
					throw new \Exception("Unopened {");
				} else {
					return;
				}
			} else {
				$oList->append($this->parseSelector());
			}
			$this->consumeWhiteSpace();
		}
		if (!$bIsRoot) {
			throw new \Exception("Unexpected end of document");
		}
	}

	private function parseAtRule() {
		$this->consume('@');
		$sIdentifier = $this->parseIdentifier();
		$this->consumeWhiteSpace();
		if ($sIdentifier === 'import') {
			$oLocation = $this->parseURLValue();
			$this->consumeWhiteSpace();
			$sMediaQuery = null;
			if (!$this->comes(';')) {
				$sMediaQuery = $this->consumeUntil(';');
			}
			$this->consume(';');
			return new Import($oLocation, $sMediaQuery);
		} else if ($sIdentifier === 'charset') {
			$sCharset = $this->parseStringValue();
			$this->consumeWhiteSpace();
			$this->consume(';');
			$this->setCharset($sCharset->getString());
			return new Charset($sCharset);
		} else if (self::identifierIs($sIdentifier, 'keyframes')) {
			$oResult = new KeyFrame();
			$oResult->setVendorKeyFrame($sIdentifier);
			$oResult->setAnimationName(trim($this->consumeUntil('{')));
			$this->consume('{');
			$this->consumeWhiteSpace();
			$this->parseList($oResult);
			return $oResult;
		} else if ($sIdentifier === 'namespace') {
			$sPrefix = null;
			$mUrl = $this->parsePrimitiveValue();
			if (!$this->comes(';')) {
				$sPrefix = $mUrl;
				$mUrl = $this->parsePrimitiveValue();
			}
			$this->consume(';');
			if ($sPrefix !== null && !is_string($sPrefix)) {
				throw new \Exception('Wrong namespace prefix '.$sPrefix);
			}
			if (!($mUrl instanceof String || $mUrl instanceof URL)) {
				throw new \Exception('Wrong namespace url of invalid type '.$mUrl);
			}
			return new CSSNamespace($mUrl, $sPrefix);
		} else {
			//Unknown other at rule (font-face or such)
			$sArgs = $this->consumeUntil('{');
			$this->consume('{');
			$this->consumeWhiteSpace();
			$bUseRuleSet = true;
			foreach(explode('/', AtRule::BLOCK_RULES) as $sBlockRuleName) {
				if(self::identifierIs($sIdentifier, $sBlockRuleName)) {
					$bUseRuleSet = false;
					break;
				}
			}
			if($bUseRuleSet) {
				$oAtRule = new AtRuleSet($sIdentifier, $sArgs);
				$this->parseRuleSet($oAtRule);
			} else {
				$oAtRule = new AtRuleBlockList($sIdentifier, $sArgs);
				$this->parseList($oAtRule);
			}
			return $oAtRule;
		}
	}

	private function parseIdentifier($bAllowFunctions = true, $bIgnoreCase = true) {
		$sResult = $this->parseCharacter(true);
		if ($sResult === null) {
			throw new UnexpectedTokenException($sResult, $this->peek(5), 'identifier');
		}
		$sCharacter = null;
		while (($sCharacter = $this->parseCharacter(true)) !== null) {
			$sResult .= $sCharacter;
		}
		if ($bIgnoreCase) {
			$sResult = $this->strtolower($sResult);
		}
		if ($bAllowFunctions && $this->comes('(')) {
			$this->consume('(');
			$aArguments = $this->parseValue(array('=', ' ', ','));
			$sResult = new CSSFunction($sResult, $aArguments);
			$this->consume(')');
		}
		return $sResult;
	}

	private function parseStringValue() {
		$sBegin = $this->peek();
		$sQuote = null;
		if ($sBegin === "'") {
			$sQuote = "'";
		} else if ($sBegin === '"') {
			$sQuote = '"';
		}
		if ($sQuote !== null) {
			$this->consume($sQuote);
		}
		$sResult = "";
		$sContent = null;
		if ($sQuote === null) {
			//Unquoted strings end in whitespace or with braces, brackets, parentheses
			while (!preg_match('/[\\s{}()<>\\[\\]]/isu', $this->peek())) {
				$sResult .= $this->parseCharacter(false);
			}
		} else {
			while (!$this->comes($sQuote)) {
				$sContent = $this->parseCharacter(false);
				if ($sContent === null) {
					throw new \Exception("Non-well-formed quoted string {$this->peek(3)}");
				}
				$sResult .= $sContent;
			}
			$this->consume($sQuote);
		}
		return new String($sResult);
	}

	private function parseCharacter($bIsForIdentifier) {
		if ($this->peek() === '\\') {
			$this->consume('\\');
			if ($this->comes('\n') || $this->comes('\r')) {
				return '';
			}
			$aMatches;
			if (preg_match('/[0-9a-fA-F]/Su', $this->peek()) === 0) {
				return $this->consume(1);
			}
			$sUnicode = $this->consumeExpression('/^[0-9a-fA-F]{1,6}/u');
			if ($this->strlen($sUnicode) < 6) {
				//Consume whitespace after incomplete unicode escape
				if (preg_match('/\\s/isSu', $this->peek())) {
					if ($this->comes('\r\n')) {
						$this->consume(2);
					} else {
						$this->consume(1);
					}
				}
			}
			$iUnicode = intval($sUnicode, 16);
			$sUtf32 = "";
			for ($i = 0; $i < 4; $i++) {
				$sUtf32 .= chr($iUnicode & 0xff);
				$iUnicode = $iUnicode >> 8;
			}
			return iconv('utf-32le', $this->sCharset, $sUtf32);
		}
		if ($bIsForIdentifier) {
			if (preg_match('/[a-zA-Z0-9]|-|_/u', $this->peek()) === 1) {
				return $this->consume(1);
			} else if (ord($this->peek()) > 0xa1) {
				return $this->consume(1);
			} else {
				return null;
			}
		} else {
			return $this->consume(1);
		}
		// Does not reach here
		return null;
	}

	private function parseSelector() {
		$oResult = new DeclarationBlock();
		$oResult->setSelector($this->consumeUntil('{'));
		$this->consume('{');
		$this->consumeWhiteSpace();
		$this->parseRuleSet($oResult);
		return $oResult;
	}

	private function parseRuleSet($oRuleSet) {
		while ($this->comes(';')) {
			$this->consume(';');
			$this->consumeWhiteSpace();
		}
		while (!$this->comes('}')) {
			$oRule = null;
			if($this->oParserSettings->bLenientParsing) {
				try {
					$oRule = $this->parseRule();
				} catch (UnexpectedTokenException $e) {
					try {
						$sConsume = $this->consumeUntil(array("\n", ";", '}'), true);
						// We need to “unfind” the matches to the end of the ruleSet as this will be matched later
						if($this->streql($this->substr($sConsume, $this->strlen($sConsume)-1, 1), '}')) {
							$this->iCurrentPosition--;
						} else {
							$this->consumeWhiteSpace();
							while ($this->comes(';')) {
								$this->consume(';');
							}
						}
					} catch (UnexpectedTokenException $e) {
						// We’ve reached the end of the document. Just close the RuleSet.
						return;
					}
				}
			} else {
				$oRule = $this->parseRule();
			}
			if($oRule) {
				$oRuleSet->addRule($oRule);
			}
			$this->consumeWhiteSpace();
		}
		$this->consume('}');
	}

	private function parseRule() {
		$oRule = new Rule($this->parseIdentifier());
		$this->consumeWhiteSpace();
		$this->consume(':');
		$oValue = $this->parseValue(self::listDelimiterForRule($oRule->getRule()));
		$oRule->setValue($oValue);
		if ($this->comes('!')) {
			$this->consume('!');
			$this->consumeWhiteSpace();
			$this->consume('important');
			$oRule->setIsImportant(true);
		}
		while ($this->comes(';')) {
			$this->consume(';');
			$this->consumeWhiteSpace();
		}
		return $oRule;
	}

	private function parseValue($aListDelimiters) {
		$aStack = array();
		$this->consumeWhiteSpace();
		//Build a list of delimiters and parsed values
		while (!($this->comes('}') || $this->comes(';') || $this->comes('!') || $this->comes(')'))) {
			if (count($aStack) > 0) {
				$bFoundDelimiter = false;
				foreach ($aListDelimiters as $sDelimiter) {
					if ($this->comes($sDelimiter)) {
						array_push($aStack, $this->consume($sDelimiter));
						$this->consumeWhiteSpace();
						$bFoundDelimiter = true;
						break;
					}
				}
				if (!$bFoundDelimiter) {
					//Whitespace was the list delimiter
					array_push($aStack, ' ');
				}
			}
			array_push($aStack, $this->parsePrimitiveValue());
			$this->consumeWhiteSpace();
		}
		//Convert the list to list objects
		foreach ($aListDelimiters as $sDelimiter) {
			if (count($aStack) === 1) {
				return $aStack[0];
			}
			$iStartPosition = null;
			while (($iStartPosition = array_search($sDelimiter, $aStack, true)) !== false) {
				$iLength = 2; //Number of elements to be joined
				for ($i = $iStartPosition + 2; $i < count($aStack); $i+=2) {
					if ($sDelimiter !== $aStack[$i]) {
						break;
					}
					$iLength++;
				}
				$oList = new RuleValueList($sDelimiter);
				for ($i = $iStartPosition - 1; $i - $iStartPosition + 1 < $iLength * 2; $i+=2) {
					$oList->addListComponent($aStack[$i]);
				}
				array_splice($aStack, $iStartPosition - 1, $iLength * 2 - 1, array($oList));
			}
		}
		return $aStack[0];
	}

	private static function listDelimiterForRule($sRule) {
		if (preg_match('/^font($|-)/', $sRule)) {
			return array(',', '/', ' ');
		}
		return array(',', ' ', '/');
	}

	private function parsePrimitiveValue() {
		$oValue = null;
		$this->consumeWhiteSpace();
		if (is_numeric($this->peek()) || ($this->comes('-.') && is_numeric($this->peek(1, 2))) || (($this->comes('-') || $this->comes('.')) && is_numeric($this->peek(1, 1)))) {
			$oValue = $this->parseNumericValue();
		} else if ($this->comes('#') || $this->comes('rgb') || $this->comes('hsl')) {
			$oValue = $this->parseColorValue();
		} else if ($this->comes('url')) {
			$oValue = $this->parseURLValue();
		} else if ($this->comes("'") || $this->comes('"')) {
			$oValue = $this->parseStringValue();
		} else {
			$oValue = $this->parseIdentifier(true, false);
		}
		$this->consumeWhiteSpace();
		return $oValue;
	}

	private function parseNumericValue($bForColor = false) {
		$sSize = '';
		if ($this->comes('-')) {
			$sSize .= $this->consume('-');
		}
		while (is_numeric($this->peek()) || $this->comes('.')) {
			if ($this->comes('.')) {
				$sSize .= $this->consume('.');
			} else {
				$sSize .= $this->consume(1);
			}
		}
		$fSize = floatval($sSize);
		$sUnit = null;
		foreach(explode('/', Size::ABSOLUTE_SIZE_UNITS.'/'.Size::RELATIVE_SIZE_UNITS.'/'.Size::NON_SIZE_UNITS) as $sDefinedUnit) {
			if ($this->comes($sDefinedUnit, 0, true)) {
				$sUnit = $sDefinedUnit;
				$this->consume($sDefinedUnit);
				break;
			}
		}
		return new Size($fSize, $sUnit, $bForColor);
	}

	private function parseColorValue() {
		$aColor = array();
		if ($this->comes('#')) {
			$this->consume('#');
			$sValue = $this->parseIdentifier(false);
			if ($this->strlen($sValue) === 3) {
				$sValue = $sValue[0] . $sValue[0] . $sValue[1] . $sValue[1] . $sValue[2] . $sValue[2];
			}
			$aColor = array('r' => new Size(intval($sValue[0] . $sValue[1], 16), null, true), 'g' => new Size(intval($sValue[2] . $sValue[3], 16), null, true), 'b' => new Size(intval($sValue[4] . $sValue[5], 16), null, true));
		} else {
			$sColorMode = $this->parseIdentifier(false);
			$this->consumeWhiteSpace();
			$this->consume('(');
			$iLength = $this->strlen($sColorMode);
			for ($i = 0; $i < $iLength; $i++) {
				$this->consumeWhiteSpace();
				$aColor[$sColorMode[$i]] = $this->parseNumericValue(true);
				$this->consumeWhiteSpace();
				if ($i < ($iLength - 1)) {
					$this->consume(',');
				}
			}
			$this->consume(')');
		}
		return new Color($aColor);
	}

	private function parseURLValue() {
		$bUseUrl = $this->comes('url');
		if ($bUseUrl) {
			$this->consume('url');
			$this->consumeWhiteSpace();
			$this->consume('(');
		}
		$this->consumeWhiteSpace();
		$oResult = new URL($this->parseStringValue());
		if ($bUseUrl) {
			$this->consumeWhiteSpace();
			$this->consume(')');
		}
		return $oResult;
	}
	
	/**
	* Tests an identifier for a given value. Since identifiers are all keywords, they can be vendor-prefixed. We need to check for these versions too.
	*/
	private static function identifierIs($sIdentifier, $sMatch, $bCaseInsensitive = true) {
	 	return preg_match("/^(-\\w+-)?$sMatch$/".($bCaseInsensitive ? 'i' : ''), $sIdentifier) === 1;
	}

	private function comes($sString, $iOffset = 0, $bCaseInsensitive = true) {
		if ($this->isEnd()) {
			return false;
		}
		$sPeek = $this->peek($sString, $iOffset);
		return $this->streql($sPeek, $sString, $bCaseInsensitive);
	}

	private function peek($iLength = 1, $iOffset = 0) {
		if ($this->isEnd()) {
			return '';
		}
		if (is_string($iLength)) {
			$iLength = $this->strlen($iLength);
		}
		if (is_string($iOffset)) {
			$iOffset = $this->strlen($iOffset);
		}
		$iOffset = $this->iCurrentPosition + $iOffset;
		if ($iOffset >= $this->iLength) {
			return '';
		}
		$iLength = min($iLength, $this->iLength-$iOffset);
		return $this->substr($this->sText, $iOffset, $iLength);
	}

	private function consume($mValue = 1) {
		if (is_string($mValue)) {
			$iLength = $this->strlen($mValue);
			if (!$this->streql($this->substr($this->sText, $this->iCurrentPosition, $iLength), $mValue)) {
				throw new UnexpectedTokenException($mValue, $this->peek(max($iLength, 5)));
			}
			$this->iCurrentPosition += $this->strlen($mValue);
			return $mValue;
		} else {
			if ($this->iCurrentPosition + $mValue > $this->iLength) {
				throw new UnexpectedTokenException($mValue, $this->peek(5), 'count');
			}
			$sResult = $this->substr($this->sText, $this->iCurrentPosition, $mValue);
			$this->iCurrentPosition += $mValue;
			return $sResult;
		}
	}

	private function consumeExpression($mExpression) {
		$aMatches;
		if (preg_match($mExpression, $this->inputLeft(), $aMatches, PREG_OFFSET_CAPTURE) === 1) {
			return $this->consume($aMatches[0][0]);
		}
		throw new UnexpectedTokenException($mExpression, $this->peek(5), 'expression');
	}

	private function consumeWhiteSpace() {
		do {
			while (preg_match('/\\s/isSu', $this->peek()) === 1) {
				$this->consume(1);
			}
		} while ($this->consumeComment());
	}

	private function consumeComment() {
		if ($this->comes('/*')) {
			$this->consumeUntil('*/');
			$this->consume('*/');
			return true;
		}
		return false;
	}

	private function isEnd() {
		return $this->iCurrentPosition >= $this->iLength;
	}

	private function consumeUntil($aEnd, $bIncludeEnd = false) {
		$aEnd = is_array($aEnd) ? $aEnd : array($aEnd);
		$iEndPos = null;
		foreach ($aEnd as $sEnd) {
			$iCurrentEndPos = $this->strpos($this->sText, $sEnd, $this->iCurrentPosition);
			if($iCurrentEndPos === false) {
				continue;
			}
			if($iEndPos === null || $iCurrentEndPos < $iEndPos) {
				$iEndPos = $iCurrentEndPos + ($bIncludeEnd ? $this->strlen($sEnd) : 0);
			}
		}
		if ($iEndPos === null) {
			throw new UnexpectedTokenException('One of ("'.implode('","', $aEnd).'")', $this->peek(5), 'search');
		}
		return $this->consume($iEndPos - $this->iCurrentPosition);
	}

	private function inputLeft() {
		return $this->substr($this->sText, $this->iCurrentPosition, -1);
	}

	private function substr($sString, $iStart, $iLength) {
		if ($this->oParserSettings->bMultibyteSupport) {
			return mb_substr($sString, $iStart, $iLength, $this->sCharset);
		} else {
			return substr($sString, $iStart, $iLength);
		}
	}

	private function strlen($sString) {
		if ($this->oParserSettings->bMultibyteSupport) {
			return mb_strlen($sString, $this->sCharset);
		} else {
			return strlen($sString);
		}
	}

	private function streql($sString1, $sString2, $bCaseInsensitive = true) {
		if($bCaseInsensitive) {
			return $this->strtolower($sString1) === $this->strtolower($sString2);
		} else {
			return $sString1 === $sString2;
		}
	}

	private function strtolower($sString) {
		if ($this->oParserSettings->bMultibyteSupport) {
			return mb_strtolower($sString, $this->sCharset);
		} else {
			return strtolower($sString);
		}
	}

	private function strpos($sString, $sNeedle, $iOffset) {
		if ($this->oParserSettings->bMultibyteSupport) {
			return mb_strpos($sString, $sNeedle, $iOffset, $this->sCharset);
		} else {
			return strpos($sString, $sNeedle, $iOffset);
		}
	}

}
