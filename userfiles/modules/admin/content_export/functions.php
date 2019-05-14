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
		$readyCategories = array();
		$categories = get_categories(array());
		
		return $readyCategories;
	}
	
	private function _getProducts() {
		
	}
	
	private function _getOrders() {
		
	}
	
	private function _getClients() {
		
	}
	
	private function _getCoupons() {
		
	}
}