<?php
namespace Microweber\Utils\Backup;

use Microweber\Utils\Backup\Readers\JsonReader;
use Microweber\Utils\Backup\Readers\CsvReader;
use Microweber\Utils\Backup\Readers\XmlReader;
use Microweber\App\Providers\Illuminate\Support\Facades\Cache;

class Import
{

	public $type;
	public $file;

	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function setFile($file)
	{
		$this->file = $file;
	}
	
	public function importAsType($data)
	{
		$reader = false;
		
		switch ($this->type) {
			
			case 'json':
				$reader = new JsonReader($data);
				break;
			case 'csv':
				$reader = new CsvReader($data);
				break;
			case 'xml':
				$reader = new XmlReader($data);
				break;
				// Don't forget a break
		}
		
		if ($reader) {
			
			$readData = $reader->readData();
			
			if (!empty($readData)) {
				return array(
					'success' => count($readData, COUNT_RECURSIVE) . ' items are readed.',
					'imoport_type' => $this->type,
					'data' => $readData
				);
			}
		}
		
		return array(
			'error' => 'Import format not supported.'
		);
	}
	
	public function readContentWithCache() {
		return Cache::rememberForever(md5($this->file), function() {
			return $this->importAsType($this->file);
		});
	}
}