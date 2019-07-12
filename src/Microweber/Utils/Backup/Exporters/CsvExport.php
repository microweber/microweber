<?php
namespace Microweber\Utils\Backup\Exporters;

class CsvExport extends DefaultExport
{

	public function start()
	{
		
		$contents = array();
		
		$readyContent = array();
		$readyContent['id'] = 'id';
		$readyContent['content_type'] = 'content_type';
		$readyContent['title'] = 'title';
		$readyContent['url'] = 'url';
		$readyContent['content_body'] = 'content_body';
		$contents[] = $readyContent;
		
		if (isset($this->data['content'])) {
			foreach ($this->data['content'] as $content) {
				
				$readyContent = array();
				$readyContent['id'] = $content['id'];
				$readyContent['content_type'] = $content['content_type'];
				$readyContent['title'] = $content['title'];
				$readyContent['url'] = $content['url'];
				$readyContent['content_body'] = trim($content['content']);
				
				$contents[] = $readyContent;
				
			}
		}
		
		echo $this->array2csv($contents);
		
		//var_dump($contents);
		die();
	}

	public function array2csv($data, $delimiter = ',', $enclosure = '"', $escape_char = "\\")
	{
		
		$f = fopen('php://memory', 'r+');
		foreach ($data as $item) {
			fputcsv($f, $item, $delimiter, $enclosure, $escape_char);
		}
		rewind($f);
		return stream_get_contents($f);
		
	}
}