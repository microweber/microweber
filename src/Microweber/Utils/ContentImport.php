<?php
namespace Microweber\Utils;

class ContentImport
{

	protected $importContentTypes = array();
	protected $importFormatType = 'json';

	public function __construct($data = array())
	{
		ini_set('memory_limit', '512M');
		set_time_limit(0);

		$this->importContentTypes = $data;
	}

	public function start()
	{
		$content = file_get_contents('api/content_export_download?filename=content_export_2019-05-15-042448.json');
		$content = json_decode($content, TRUE);

		if (isset($content['pages'])) {
			$this->_importPages($content['pages']);
		}

		if (isset($content['posts'])) {
			$this->_importPosts($content['posts']);
		}

		if (isset($content['comments'])) {
			$this->_importComments($content['comments']);
		}

		if (isset($content['categories'])) {
			$this->_importCategories($content['categories']);
		}

		if (isset($content['products'])) {
			$this->_importProducts($content['products']);
		}

		if (isset($content['orders'])) {
			$this->_importOrders($content['orders']);
		}

		if (isset($content['clients'])) {
			$this->_importClients($content['clients']);
		}

		if (isset($content['coupons'])) {
			$this->_importCoupons($content['coupons']);
		}
	}

	private function _importPages($pages)
	{}

	private function _importPosts($posts)
	{}

	private function _importComments($comments)
	{}

	private function _importCategories($categories)
	{}

	private function _importProducts($products)
	{}

	private function _importOrders($orders)
	{}

	private function _importClients($clients)
	{}

	private function _importCoupons($coupons)
	{}
}