<?php
namespace Microweber\Utils\Backup\Readers;

class XmlReader extends DefaultReader
{

	public function readData()
	{
		$xml = simplexml_load_file($this->file, 'SimpleXMLElement', LIBXML_NOCDATA);
		$xml = json_decode(json_encode($xml), true);
		
		if (isset($xml['channel']['item'])) { 
			return $this->_readWordpress($xml); 
		} 
		
	}

	private function _readWordpress($xml)
	{
		$content = array();

		$i = 0;
		foreach ($xml['channel']['item'] as $item) {

			$readyContent = array();
			$readyContent['title'] = $item['title'];  
			$readyContent['id'] = $i;
			$readyContent['content_type'] = 'post';
			$readyContent['subtype'] = 'post';
			$readyContent['is_active'] = 1;
			$readyContent['content'] = ''; // $item['description'];

			$categories = array();
			$tags = array();
			foreach ($item['category'] as $category) {
				if (isset($category['@attributes'])) {

					$attributes = $category['@attributes'];

					if (isset($attributes['nicename']) && $attributes['domain'] == 'category') {
						$categories[] = $attributes['nicename'];
					}

					if (isset($attributes['nicename']) && $attributes['domain'] == 'tag') {
						$tags[] = $attributes['nicename'];
					}
				}
			}

			$tags = implode(', ', $tags);
			if (! empty($tags)) {
				$readyContent['tags'] = $tags;
			}

			$categories = implode(', ', $categories);
			if (! empty($tags)) {
				$readyContent['categories'] = $categories;
			}

			$content[] = $readyContent;
			$i ++;
		}
		
		return array(
			'content' => $content
		);
	}

}