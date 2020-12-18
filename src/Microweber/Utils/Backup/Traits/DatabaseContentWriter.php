<?php
namespace Microweber\Utils\Backup\Traits;

use Microweber\Utils\Backup\DatabaseSave;

trait DatabaseContentWriter
{

	private function _getContentById($contentId)
	{
		if (! isset($this->content['content'])) {
			return;
		}
		
		foreach ($this->content['content'] as $dataItem) {
			
			if ($dataItem['id'] == $contentId) {
				return $dataItem;
			}
		}
	}

	private function _getContentDatabase($content)
	{
		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['title'] = $content['title'];
		$dbSelectParams['content_type'] = $content['content_type'];
		$dbSelectParams['subtype'] = $content['subtype'];
		$dbSelectParams['url'] = $content['url'];
		$dbSelectParams['layout_file'] = $content['layout_file'];

		return db_get('content', $dbSelectParams);
	}

	private function _fixParentRelationship($savedItem)
	{
		if (isset($savedItem['item']['parent'])) {

			// Get content data from file export
			$content = $this->_getContentById($savedItem['item']['parent']);
			if (! empty($content)) {
				
				$contentDatabase = $this->_getContentDatabase($content);
				if (! empty($contentDatabase)) {

					$savedItem['item']['id'] = $savedItem['itemIdDatabase'];
					$savedItem['item']['parent'] = $contentDatabase['id'];
			
					//echo 'Fix parent relationship on content' . PHP_EOL;
					
					DatabaseSave::save('content', $savedItem['item']);
				}
			}
		}
	}
}