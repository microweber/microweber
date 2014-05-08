<?php

class SimpleXmlStreamer extends \Prewk\XmlStreamer {
	public $allChildTexts = array();
	public $allElementNames = array();
	public $chunkCount = 0;
	public $initCalled = false;

	public function init() {
		$this->initCalled = true;
	}

	public function processNode($xmlString, $elementName, $nodeIndex) {
		$xml = simplexml_load_string($xmlString);

		$this->allChildTexts[] = (string)$xml->child;
		$this->allElementNames[] = $elementName;

		return true;
	}

	public function chunkCompleted() {
		$this->chunkCount++;
	}
}

class XmlStreamerTest extends PHPUnit_Framework_TestCase {
	private function getRandomLetters($length) {
		return substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", $length)), 0, $length);
	}

	private function getRandomNodeName() {
		return $this->getRandomLetters(5) . "-" . $this->getRandomLetters(5);
	}

	private function getRandomAttribute() {
		return $this->getRandomLetters(3) . "=\"" . $this->getRandomLetters(5) . "\"";
	}

	public function test1000XmlNodesInMemory() {
		$xmlStr = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><rootNode>";

		$testAllChildTexts = array();
		$testAllElementNames = array();
		for ($i = 0; $i < 1000; $i++) {
			$childText = "Lorem ipsum $i";
			
			$testAllChildTexts[] = $childText;
			$parentNode = $this->getRandomNodeName();
			$testAllElementNames[] = $parentNode;

			$xmlStr .= "<$parentNode " . $this->getRandomAttribute() . " " . $this->getRandomAttribute() . "><child>$childText</child></$parentNode>";
		}
		$xmlStr .= "</rootNode>";

		$handle = fopen("php://memory", "rw+");
		fwrite($handle, $xmlStr);
		fseek($handle, 0);

		$streamer = new SimpleXmlStreamer($handle, 100, null, strlen($xmlStr), null);

		$this->assertTrue($streamer->initCalled, "The init method seems to not have been called at all.");
		
		$this->assertTrue($streamer->parse(), "Failed to bootstrap with parse() method.");
		
		$this->assertEquals($testAllChildTexts, $streamer->allChildTexts, "The text in the XML nodes parsed weren't equal to the text in the XML nodes inserted as test XML.");
		
		$this->assertEquals($testAllElementNames, $streamer->allElementNames, "The XML node names parsed weren't equal to the XML node names inserted as test XML.");

		$this->assertGreaterThan(0, $streamer->chunkCount, "The chunkCompleted method seems to not have been called at all.");

		fclose($handle);
	}
}