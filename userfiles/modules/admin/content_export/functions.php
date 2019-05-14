<?php
api_expose('content_export_start');
function content_export_start($data)
{
	$export = new ContentExport($data);
	$export->setExportFormatType('json');
	$export->start();
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
			$readyExport['pages'] = $this->getPages();
		}
		
		if (isset($this->exportContentTypes['export_posts'])) {
			$readyExport['posts'] = $this->getPosts();
		}
		
		if (isset($this->exportContentTypes['export_categories'])) {
			$readyExport['categories'] = $this->getCategories();
		}
		
		if (isset($this->exportContentTypes['export_products'])) {
			$readyExport['products'] = $this->getProducts();
		}
		
		if (isset($this->exportContentTypes['export_orders'])) {
			$readyExport['orders'] = $this->getOrders();
		}
		
		if (isset($this->exportContentTypes['export_clients'])) {
			$readyExport['clients'] = $this->getClients();
		}
		
		if (isset($this->exportContentTypes['export_coupons'])) {
			$readyExport['coupons'] = $this->getCoupons();
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
}