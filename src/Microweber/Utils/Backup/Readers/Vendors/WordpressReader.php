<?php
namespace Microweber\Utils\Backup\Readers\Vendors;

use Microweber\Providers\UrlManager;

trait WordpressReader
{

	protected function readWordpress()
	{
        libxml_use_internal_errors(true);

		$urlManager = new UrlManager();

		$xml = new \DOMDocument();
		$xml->load($this->file);

		$i = 0;
        $content = array();
		foreach ($xml->getElementsByTagName('item') as $item) {

			$contentType = false;
			$title = false;
			$contentDecoded = false;
			$price = false;
			$categories = array();
			$tags = array();

			if ($item->hasChildNodes() && $item->childNodes->length > 0) {
				foreach ($item->childNodes as $itemChildNode) {
					if (isset($itemChildNode->nodeName)) {

						if ($itemChildNode->nodeName == 'title') {
							$title = $itemChildNode->nodeValue;
						}

						if ($itemChildNode->nodeName == 'price') {
							$price = $itemChildNode->nodeValue;
						}

						if ($itemChildNode->nodeName == 'content:encoded') {
							$contentDecoded = $itemChildNode->nodeValue;
						}

						if ($itemChildNode->nodeName == 'category') {

							$isTag = false;
							if ($itemChildNode->hasAttribute('domain')) {
								if ($itemChildNode->getAttribute('domain') == 'tag') {
									$isTag = $itemChildNode->nodeValue;
								}
							}

							if ($isTag) {
								if (! in_array($isTag, $tags)) {
									$tags[] = $isTag;
								}
							} else {
								if (! in_array($itemChildNode->nodeValue, $categories)) {
									$categories[] = $itemChildNode->nodeValue;
								}
							}
						}

						if ($itemChildNode->nodeName == 'wp:post_type') {
							$contentType = $itemChildNode->nodeValue;
						}
					}
				}
			}

			$readyContent = array();
			$readyContent['id'] = $i;
			$readyContent['title'] = $title;
			$readyContent['url'] = $urlManager->slug($title);
			$readyContent['content'] = $contentDecoded;
			$readyContent['content_type'] = 'post';
			$readyContent['subtype'] = 'post';
			$readyContent['is_active'] = 1;

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

			if ($price) {
				$readyContent['custom_field_price'] = $price;
				$readyContent['content_type'] = 'product';
			}

			if ($contentType) {
				$readyContent['content_type'] = $contentType;
			}

			$content[] = $readyContent;
			$i ++;
		}

		return array(
			'content' => $content
		);
	}
}