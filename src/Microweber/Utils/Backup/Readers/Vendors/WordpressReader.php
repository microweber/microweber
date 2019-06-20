<?php 
namespace Microweber\Utils\Backup\Readers\Vendors;

use Microweber\Providers\UrlManager;

trait WordpressReader {
	
	protected function readWordpress($items)
	{
		$content = array();
		
		$i = 0;
		foreach ($items as $item) {
			
			$urlManager = new UrlManager();
			
			$readyContent = array();
			$readyContent['title'] = $item['title'];
			$readyContent['url'] = $urlManager->slug($item['title']);
			$readyContent['id'] = $i;
			$readyContent['content_type'] = 'post';
			$readyContent['subtype'] = 'post';
			$readyContent['is_active'] = 1;
			
			if (isset($item['price']) && !empty($item['price'])) {
				$readyContent['custom_field_price'] = $item['price'];
				$readyContent['content_type'] = 'product';
			}
			
			if (isset($item['description']) && !empty($item['description'])) {
				$readyContent['content'] = $item['description'];
			}
			
			if (isset($item['pictures']) && !empty($item['pictures']) && is_string($item['pictures'])) {
				$readyContent['images'][] = $item['pictures'];
			}
			
			$categoryRecognize = 'category';
			if (isset($item['categories'])) {
				$categoryRecognize = 'categories';
			}
			
			$categories = array();
			$tags = array();
			
			if (isset($item[$categoryRecognize])) {
				
				if (is_array($item[$categoryRecognize] )) {
					foreach ($item[$categoryRecognize] as $category) {
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
					
					foreach ($item[$categoryRecognize] as $category) {
						$categories[] = $category;
					}
				}
				
				if (is_string($item[$categoryRecognize])) {
					$categories = explode(',', $item[$categoryRecognize]);
				}
			}
			
			if (is_array($tags)) {
				$tags = implode(', ', $tags);
			}
			
			if (! empty($tags)) {
				$readyContent['tags'] = $tags;
			}
			
			if (is_array($categories)) {
				$categories = implode(', ', $categories);
			}
			
			if (! empty($categories)) {
				$readyContent['categories'] = $categories;
			}
			
			$content[] = $readyContent;
			$i ++;
		}
		
		return array('content' => $content);
		
	}
}