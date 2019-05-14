<?php
api_expose('content_export_start');
function content_export_start($data)
{
	only_admin_access();
	
	$export = new ContentExport($data);
	$export->setExportFormatType('json');
	return $export->start();
	
}

class ContentExport
{

	protected $exportContentTypes = array();
	protected $exportFormatType = 'json';

	public function __construct($data) {
		
		ini_set('memory_limit', '512M');
		set_time_limit(0);
		
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
			
			$exportFilename = 'content_export_' . date("Y-m-d-his") . '.json';
			$exportPath = $this->_getExportLocation() . $exportFilename;
			
			$save = json_encode($readyExport, JSON_PRETTY_PRINT);
			
			if (file_put_contents($exportPath, $save)) {
				return array('success' => count($readyExport, COUNT_RECURSIVE) . ' items are exported', 'filepath'=>$exportPath);
			} else {
				return array('error' => 'There was error with export.');
			}
			
		} else {
			throw new \Exception('Export format type is not supported.');
		}
	}
	
	public function setExportContentType($data) {
		$this->exportContentType = $data;
	}
	
	public function setExportFormatType($type) {
		$this->exportFormatType = $type;
	}
	
	private function _getExportLocation() {
		
		$exportLocation = storage_path().'/export_content/';
		if (!is_dir($exportLocation)) {
			mkdir_recursive($exportLocation);
		}
		
		return $exportLocation;
	}
	
	private function _getPages() {
		
		$getPages = get_pages(array());
		if (empty($getPages)) {
			return array();
		}
		
		$pages = array();
		foreach ($getPages as $page) {
			$pages[] = array(
				"id" => $page['id'],
				"title" => $page['title'],
				"description" => $page['description'],
				"content" => $this->_clearContent($page['content']),
				"pictures" => $this->_getPicturesByContentId($page['id'])
			);
		}
		return $pages;
	}
	
	private function _getPosts() {
		
		$getPosts = get_posts(array());
		if (empty($getPosts)) {
			return array();
		}
		
		$posts = array();
		foreach ($getPosts as $post) {
			
			$posts[] = array(
				"id" => $post['id'],
				"title" => $post['title'],
				"description" => $post['description'],
				"content" => $this->_clearContent($post['content']),
				"pictures" => $this->_getPicturesByContentId($post['id'])
			);
		}
		return $posts;
	}
	
	private function _getCategories() {
		
		$getCategories = get_categories(array());
		if (empty($getCategories)) {
			return array();
		}
		
		$categories = array();
		foreach ($getCategories as $category) {
			$categories[] = array(
				"id" => $category['id'],
				"parent_id" => $category['parent_id'],
				"title" => $category['title'],
				"description" => $category['description'],
				"content" => $this->_clearContent($category['content']),
			);
		}
		return $categories;
	}
	
	private function _getProducts() {
		
		$getProducts = get_products(array());
		if (empty($getProducts)) {
			return array();
		}
		
		$products = array();
		$offers = array();
		
		foreach (offers_get_products(array()) as $offer) {
			$offers[$offer['product_id']] = array(
				"price"=>$offer['price']
			);
		}
		
		foreach ($getProducts as $product) {
			
			$contentData = content_data($product['id']);
			
			$readyProduct = array();
			$readyProduct["id"] = $product['id'];
			$readyProduct["title"] = $product['title'];
			
			if (isset($offers[$product['id']]['price'])) {
				$readyProduct["price"] = $offers[$product['id']]['price'];
			}
			
			if (!empty($contentData)) {
				$readyProduct["quantity"] = $contentData['qty'];
				
				if (isset($contentData['sku'])) {
					$readyProduct["sku"] = $contentData['sku'];
				}
				
				if (isset($contentData['shipping_weight'])) {
					$readyProduct["shipping_weight"] = $contentData['shipping_weight'];
				}
				
				if (isset($contentData['shipping_width'])) {
					$readyProduct["shipping_width"] = $contentData['shipping_width'];
				}
				
				if (isset($contentData['shipping_height'])) {
					$readyProduct["shipping_height"] = $contentData['shipping_height'];
				}
				
				if (isset($contentData['shipping_depth'])) {
					$readyProduct["shipping_depth"] = $contentData['shipping_depth'];
				}
				
				if (isset($contentData['additional_shipping_cost'])) {
					$readyProduct["additional_shipping_cost"] = $contentData['additional_shipping_cost'];
				}
				
				if (isset($contentData['is_free_shipping'])) {
					$readyProduct["is_free_shipping"] = $contentData['is_free_shipping'];
				}

			}
			
			$readyProduct["url"] = $product['url'];
			$readyProduct["description"] = $product['description'];
			$readyProduct["content"] = $product['content'];
			$readyProduct["pictures"] = $this->_getPicturesByContentId($product['id']);
			
			$products[] = $readyProduct;
		}
		return $products;
	}
	
	private function _getOrders() {
		
		$getOrders = get_orders(array());
		if (empty($getOrders)) {
			return array();
		}
		
		$orders = array();
		foreach ($getOrders as $order) {
			$orders[] = array(
				"id" => $order['id'],
			);
		}
		
		return $orders;
	}
	
	private function _getClients() {
		
		$getUsers = get_users(array());
		if (empty($getUsers)) {
			return array();
		}
		
		$users = array();
		foreach ($getUsers as $user) {
			$users[] = array(
				"id" => $user['id'],
				"username" => $user['username'],
				"email" => $user['email'],
				"first_name" => $user['first_name'],
				"middle_name" => $user['middle_name'],
				"last_name" => $user['last_name'],
				"thumbnail" => $user['thumbnail'],
				"user_information" => $user['user_information'],
				"profile_url" => $user['profile_url'],
				"website_url" => $user['website_url'],
			);
		}
		
		return $users;
	}
	
	private function _getCoupons() {
		
		$couponGetAll = coupon_get_all();
		if (empty($couponGetAll)) {
			return array();
		}
		
		$coupons = array();
		foreach ($couponGetAll as $coupon) {
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
	
	private function _clearContent($content) { 
		
		$content = preg_replace('#<module(.*?)>(.*?)</module>#is', '', $content);
		$content = preg_replace('/\<[\/]{0,1}div[^\>]*\>/i', '', $content);
		
		return $content;
	}
	
	private function _getPicturesByContentId($contentId) {
		
		$getPictures = get_pictures($contentId);
		if (empty($getPictures)) {
			return array();
		}
		
		$pictures = array();
		foreach ($getPictures as $picture) {
			$pictures[] = array(
				"id"=>$picture['id'],
				"title"=>$picture['title'],
				"description"=>$picture['description'],
				"filename"=>$picture['filename']
			);
		}
		
		return $pictures;
	}
}