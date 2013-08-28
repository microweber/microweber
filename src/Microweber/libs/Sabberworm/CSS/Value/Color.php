<?php

namespace Sabberworm\CSS\Value;

class Color extends CSSFunction {

	public function __construct($aColor) {
		parent::__construct(implode('', array_keys($aColor)), $aColor);
	}

	public function getColor() {
		return $this->aComponents;
	}

	public function setColor($aColor) {
		$this->setName(implode('', array_keys($aColor)));
		$this->aComponents = $aColor;
	}

	public function getColorDescription() {
		return $this->getName();
	}

	public function __toString() {
		// Shorthand RGB color values
		// TODO: Include in output settings (once they’re done)
		if(implode('', array_keys($this->aComponents)) === 'rgb') {
			$sResult = sprintf(
				'%02x%02x%02x',
				$this->aComponents['r']->__toString(),
				$this->aComponents['g']->__toString(),
				$this->aComponents['b']->__toString()
			);
			return '#'.(($sResult[0] == $sResult[1]) && ($sResult[2] == $sResult[3]) && ($sResult[4] == $sResult[5]) ? "$sResult[0]$sResult[2]$sResult[4]" : $sResult);
		}
		return parent::__toString();
	}
}
