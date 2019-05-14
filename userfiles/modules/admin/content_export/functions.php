<?php
api_expose('content_export_start');
function content_export_start($data)
{
	$export = new ContentExport($data);
	$export->setExportFormatType('json');
	return $export->start();
}

class ContentExport
{

	protected $exportContentTypes = array();
	protected $exportFormatType = 'json';

	public function __construct($data) {
		$this->exportContentTypes = $data;
	}
	
	public function start()
	{
		$readyExport = array();
		
		if (isset($this->exportContentTypes['export_pages'])) {
			$readyExport['pages'] = $this->_getPages();
		}
		
		if (isset($this->exportContentTypes['export_posts'])) {
			$readyExport['posts'] = $this->_getPosts();
		}
		
		if (isset($this->exportContentTypes['export_categories'])) {
			$readyExport['categories'] = $this->_getCategories();
		}
		
		if (isset($this->exportContentTypes['export_products'])) {
			$readyExport['products'] = $this->_getProducts();
		}
		
		if (isset($this->exportContentTypes['export_orders'])) {
			$readyExport['orders'] = $this->_getOrders();
		}
		
		if (isset($this->exportContentTypes['export_clients'])) {
			$readyExport['clients'] = $this->_getClients();
		}
		
		if (isset($this->exportContentTypes['export_coupons'])) {
			$readyExport['coupons'] = $this->_getCoupons();
		}
		
		
		if ($this->exportFormatType == 'json') {
			header('Content-Type: application/json');
			return json_encode($readyExport);
		} else {
			throw new \Exception('Export format type is not supported.');
		}
	}
	
	public function setExportContentType($data) {
		$this->exportContentType = $data;
	}
	
	public function setExportFormatType($type)
	{
		$this->exportFormatType = $type;
	}
	
	private function _getPages() {
		
	}
	
	private function _getPosts() {
		
	}
	
	private function _getCategories() {
		$categories = array();
		foreach (get_categories(array()) as $category) {
			$categories[] = array(
				"id" => $category['id'],
				"parent_id" => $category['parent_id'],
				"title" => $category['title'],
				"description" => $category['description'],
				"content" => $category['content']
			);
		}
		return $categories;
	}
	
	private function _getProducts() {
		$products = array();
		foreach (get_products(array()) as $product) {
			var_dump($product)
			die();
			$products[] = array(
				"id" => $product['id'],
				"parent_id" => $product['parent_id'],
				"title" => $product['title'],
				"description" => $product['description'],
				"content" => $product['content']
			);
		}
		return $products;
	}
	
	private function _getOrders() {
		
	}
	
	private function _getClients() {
		
	}
	
	private function _getCoupons() {
		
		$coupons = array();
		foreach (coupon_get_all() as $coupon) {
			$coupons[] = array(
				"id" => $coupon['id'],
				"name" => $coupon['coupon_name'],
				"code" => $coupon['coupon_code'],
				"discount_type" => $coupon['discount_type'],
				"discount_value" => $coupon['discount_value'],
				"total_amount" => $coupon['total_amount'],
				"uses_per_coupon" => $coupon['uses_per_coupon'],
				"uses_per_customer" => $coupon['uses_per_customer'],
			);
		}
		
		return $coupons;
	}
}