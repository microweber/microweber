<?php
namespace Microweber\Utils;

use Microweber\Providers\Shop\OrderManager;

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
		$content = file_get_contents('http://active-bs4.local/microweber/api/content_export_download?filename=content_export_2019-05-15-042448.json');
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
	{
		foreach ($pages as $page) {
			
		}
	}

	private function _importPosts($posts)
	{
		foreach ($posts as $post) {
			
			$save = save_content_admin(array(
				// "id"=>$post['id'],
				"is_active"=> 1,
				"content_type"=> "post",
				"title" => $post['title']
			));
			
			var_dump($save);
			die();
		}
	}

	private function _importComments($comments)
	{
		var_dump($comments);
	}

	private function _importCategories($categories)
	{
		foreach ($categories as $category) {
			
		}
	}

	private function _importProducts($products)
	{
		
	}

	private function _importOrders($orders)
	{
		foreach ($orders as $order) { 
			
		}
	}

	private function _importClients($clients)
	{
		
	}

	private function _importCoupons($coupons)
	{
		var_dump($coupons);
	}
}