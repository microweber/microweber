<?php

namespace MicroweberPackages\Utils\Misc;

class ContentExport
{

	protected $exportContentTypes = array();
	protected $exportFormatType = 'json';

	public function __construct($data = array()) {

		//ini_set('memory_limit', '512M');
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

		if (isset($this->exportContentTypes['export_comments'])) {
			$readyExport['comments'] = $this->_getComments();
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
			$exportPath = $this->getExportLocation() . $exportFilename;

			$save = json_encode($readyExport, JSON_PRETTY_PRINT);

			if (file_put_contents($exportPath, $save)) {
				return array('success' => count($readyExport, COUNT_RECURSIVE) . ' items are exported', 'filename'=>$exportFilename);
			} else {
				return array('error' => 'There was error with export.');
			}

		} else {
			return array('error' => 'Export format type is not supported.');
		}
	}

	public function download($filename) {

		$exportLocation = $this->getExportLocation();

		$exportPath = $exportLocation . $filename;
		$exportPath = sanitize_path($exportPath);

		if (!is_file($exportPath)) {
			return array('error' => 'You have not provided a existing filename to download.');
		}

		// Check if the file exist.
		if (file_exists($exportPath)) {

			// Add headers
			header('Cache-Control: public');
			header('Content-Description: File Transfer');
			header('Content-Disposition: attachment; filename=' . basename($exportPath));
			header('Content-Length: ' . filesize($exportPath));

			// Read file
			$import = new Import();
			$import->readfile_chunked($exportPath);

		} else {
			return array('error' => 'File does not exist');
		}
	}

	public function setExportContentType($data) {
		$this->exportContentType = $data;
	}

	public function setExportFormatType($type) {
		$this->exportFormatType = $type;
	}

	public function getExportLocation() {

		$exportLocation = storage_path().'/export_content/';
		if (!is_dir($exportLocation)) {
			mkdir_recursive($exportLocation);
		}

		return $exportLocation;
	}

	private function _getPages() {

		$getPages = get_pages('no_limit=1');
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

		$getPosts = get_posts('no_limit=1');
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

	private function _getComments() {

		$getComments = get_comments('no_limit=1');
		if (empty($getComments)) {
			return array();
		}

		$comments = array();
		foreach ($getComments as $comment) {
			$comments[] = array(
				"id" => $comment['id'],
				"created_by" => $comment['created_by'],
				"name" => $comment['comment_name'],
				"body" => $comment['comment_body'],
				"email" => $comment['comment_email'],
				"website" => $comment['comment_website'],
				"subject" => $comment['comment_subject'],
				"is_spam" => $comment['is_spam'],
				"is_new" => $comment['is_new'],
				"reply_to_comment_id" => $comment['reply_to_comment_id'],
				"user_ip" => $comment['user_ip'],
				"from_url" => $comment['from_url'],
			);
		}
		return $comments;
	}

	private function _getCategories() {

		$getCategories = get_categories('no_limit=1');
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

		$getProducts = get_products('no_limit=1');
		if (empty($getProducts)) {
			return array();
		}

		$products = array();
		$offers = array();

		foreach (offers_get_products('no_limit=1') as $offer) {
			$offers[$offer['product_id']] = array(
				"price"=>$offer['price']
			);
		}

		foreach ($getProducts as $product) {

			$contentData = get_content_by_id($product['id']);

			$readyProduct = array();
			$readyProduct["id"] = $product['id'];
			$readyProduct["title"] = $product['title'];

			if (isset($offers[$product['id']]['price'])) {
				$readyProduct["price"] = $offers[$product['id']]['price'];
			}

			if (!empty($contentData)) {

				if (isset($contentData['content_meta_title'])) {
					$readyProduct["meta_title"] = $contentData['content_meta_title'];
				}

				if (isset($contentData['content_meta_keywords'])) {
					$readyProduct["meta_keywords"] = $contentData['content_meta_keywords'];
				}

				if (isset($contentData['qty'])) {
					$readyProduct["quantity"] = $contentData['qty'];
				}

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
			$readyProduct["content"] = $this->_clearContent($product['content']);
			$readyProduct["pictures"] = $this->_getPicturesByContentId($product['id']);

			$products[] = $readyProduct;
		}
		return $products;
	}

	private function _getOrders() {

		$getOrders = get_orders('no_limit=1');
		if (empty($getOrders)) {
			return array();
		}

		$orders = array();
		foreach ($getOrders as $order) {

			$readyOrder = array(
				"id" => $order['id'],
				"order_id" => $order['order_id'],
				"amount" => $order['amount'],
				"transaction_id" => $order['transaction_id'],
				"shipping" => $order['shipping'],
				"currency" => $order['currency'],
				"currency_code" => $order['currency_code'],
				"first_name" => $order['first_name'],
				"last_name" => $order['last_name'],
				"email" => $order['email'],
				"country" => $order['country'],
				"city" => $order['city'],
				"state" => $order['state'],
				"zip" => $order['zip'],
				"address" => $order['address'],
				"address2" => $order['address2'],
				"phone" => $order['phone'],
				"user_ip" => $order['user_ip'],
				"items_count" => $order['items_count'],
				"order_status" => $order['order_status'],
				"price" => $order['price'],
				"other_info" => $order['other_info'],

			);

			$orderDiscount = array(
				"promo_code" => $order['promo_code'],
				"skip_promo_code" => $order['skip_promo_code'],
				"coupon_id" => $order['coupon_id'],
				"discount_type" => $order['discount_type'],
				"discount_value" => $order['discount_value'],
				"taxes_amount" => $order['taxes_amount'],
			);
			$readyOrder['discount_details'] = $orderDiscount;

			$orderPayment = array(
				"payment_amount" => $order['payment_amount'],
				"payment_currency" => $order['payment_currency'],
				"payment_status" => $order['payment_status'],
				"payment_email" => $order['payment_email'],
				"payment_receiver_email" => $order['payment_receiver_email'],
				"payment_name" => $order['payment_name'],
				"payment_country" => $order['payment_country'],
				"payment_address" => $order['payment_address'],
				"payment_city" => $order['payment_city'],
				"payment_state" => $order['payment_state'],
				"payment_zip" => $order['payment_zip'],
				"payment_phone" => $order['payment_phone'],
				"payment_type" => $order['payment_type'],
				"payer_details" => array(
					"payer_id" => $order['payer_id'],
					"payer_status" => $order['payer_status']
				)
			);
			$readyOrder['payment_details'] = $orderPayment;

			$orders[] = $readyOrder;
		}

		return $orders;
	}

	private function _getClients() {

		$getOrders = get_orders('order_by=created_at desc&groupby=email&is_completed=1&no_limit');
		if (empty($getOrders)) {
			return array();
		}

		$clients = array();
		foreach ($getOrders as $order) {

			$totalOrders = get_orders('count=1&email=' . $order['email'] . '&is_completed=1&no_limit=1');

			$clients[] = array(
				"id"=>$order['created_by'],
				"picture"=>user_picture($order['created_by']),
				"first_name" => $order['first_name'],
				"last_name" => $order['last_name'],
				"email" => $order['email'],
				"country" => $order['country'],
				"city" => $order['city'],
				"state" => $order['state'],
				"zip" => $order['zip'],
				"address" => $order['address'],
				"address2" => $order['address2'],
				"phone" => $order['phone'],
				"user_ip" => $order['user_ip'],
				"total_orders"=>$totalOrders
			);
		}

		return $clients;
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
		$content = preg_replace('#\s(id|class|style)="[^"]+"#', '', $content);

		$content = trim($content);

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
