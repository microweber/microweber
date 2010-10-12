<?php
/**
 * Microweber
 *
 * An open source CMS and application development framework for PHP 5.1 or newer
 *
 * @package		Microweber
 * @author		Peter Ivanov
 * @copyright	Copyright (c), Mass Media Group, LTD.
 * @license		http://ooyes.net
 * @link		http://ooyes.net
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------


/**
 * Ajax_helpers class
 *
 * @desc some ajax functions look in the source for more info
 * @access		public
 * @category	Ajax API
 * @subpackage		Core
 * @author		Peter Ivanov
 * @link		http://ooyes.net
 */
class Ajax_helpers extends Controller {

	function __construct() {

		parent::Controller ();

		require_once (APPPATH . 'controllers/default_constructor.php');

	}

	function index() {

		print 'test';

	}

	function set_cookie() {

		$name = $_POST ['cookie_name'];

		$val = $_POST ['cookie_value'];

		switch (strtolower ( $name )) {
			case 'is_admin' :
			case 'admin' :
			case 'userdata' :
			case 'username' :

				exit ( 'oops' );
				break;

		}

		if ($name and $val) {

			setcookie ( $name, $val );

		}

		exit ();

	}

	function security_captcha() {
		@ob_clean ();

		include 'captcha/securimage.php';

		$img = new securimage ( );
		$img->image_type = SI_IMAGE_PNG;
		$img->text_color = new Securimage_Color ( rand ( 0, 64 ), rand ( 64, 128 ), rand ( 128, 255 ) );
		$img->num_lines = 5;
		$img->line_color = new Securimage_Color ( rand ( 0, 64 ), rand ( 64, 128 ), rand ( 128, 255 ) );
		$img->text_transparency_percentage = 30;
		$img->perturbation = 0.3;

		//Change some settings
		/*
$img->image_width = 275;
$img->image_height = 90;
$img->perturbation = 0.9; // 1.0 = high distortion, higher numbers = more distortion
$img->image_bg_color = new Securimage_Color(0x0, 0x99, 0xcc);
$img->text_color = new Securimage_Color(0xea, 0xea, 0xea);
$img->text_transparency_percentage = 65; // 100 = completely transparent
$img->num_lines = 8;
$img->line_color = new Securimage_Color(0x0, 0xa0, 0xcc);
$img->signature_color = new Securimage_Color(rand(0, 64), rand(64, 128), rand(128, 255));

*/

		$img->show ( '' ); // alternate use:  $img->show('/path/to/background_image.jpg');


	}

	function set_session_vars() {
		$name = $_POST ['the_var'];
		$val = $_POST ['the_val'];

		switch (strtolower ( $name )) {
			case 'is_admin' :
			case 'admin' :
			case 'userdata' :
			case 'username' :

				exit ( 'oops' );
				break;

		}

		if ($name and $val) {
			$this->session->set_userdata ( $name, $val );
		}
		exit ();
	}

	function set_session_vars_by_post() {
		$new = array ();
		foreach ( $_POST as $k => $v ) {
			switch (strtolower ( $k )) {
				case 'is_admin' :
				case 'admin' :
				case 'userdata' :
				case 'user' :
				case 'the_user' :
				case 'username' :
					//case 'billing_cvv2' :


					//exit ( 'oops, cannot set reserved session var:' . $k );
					break;

				default :
					if ($k and $v) {
						//$this->session->set_userdata ( $k, $v );
						$new [$k] = $v;
					}
					break;

			}

		}
		if (! empty ( $new )) {
			$this->session->set_userdata ( $new );

		}

		//var_dump($_POST);
		exit ();

	}

	function comments_post() {
		//@todo delete this
		exit ( 'Moved to the comments api' );
		if ($_POST) {

			$_POST ['to_table_id'] = base64_decode ( $_POST ['to_table_id'] );
			$_POST ['to_table'] = base64_decode ( $_POST ['to_table'] );

			if (intval ( $_POST ['to_table_id'] ) == 0) {
				exit ( '1' );
			}

			if (($_POST ['to_table']) == '') {
				exit ( '2' );
			}

			$save = $this->comments_model->commentsSave ( $_POST );

			print $save;

		//var_dump ( $_POST );


		}

		$this->core_model->cacheDeleteAll ();
		exit ();
	}

	function comments_get_for_dashboard() {
		if ($_POST) {

			$toTable = base64_decode ( $_POST ['t'] );
			$toTableId = base64_decode ( $_POST ['tt'] );

			if (intval ( $toTableId ) == 0) {
				exit ( '1' );
			}

			if ($toTable == '') {
				exit ( '2' );
			}

			$comments = array ();
			$comments ['to_table'] = $toTable;
			$comments ['to_table_id'] = $toTableId;
			$comments ['is_moderated'] = 'n';

			$comments = $this->comments_model->commentsGet ( $comments );

			ob_start ();
			foreach ( $comments as $comment ) {
				require TEMPLATES_DIR . '/dashboard/single_comment.php';
			}
			print ob_get_clean ();

		}
	}

	function votes_cast() {
		@ob_clean ();
		if ($_POST) {
			$_POST ['to_table_id'] = base64_decode ( $_POST ['tt'] );
			$_POST ['to_table'] = base64_decode ( $_POST ['t'] );
			if (intval ( $_POST ['to_table_id'] ) == 0) {
				exit ( '1' );
			}

			if (($_POST ['to_table']) == '') {
				exit ( '2' );
			}

			$save = $this->votes_model->votesCast ( $_POST ['to_table'], $_POST ['to_table_id'] );
			if ($save == true) {
				exit ( 'yes' );
			} else {
				exit ( 'no' );
			}
		} else {
			exit ( 'no votes casted!' );
		}

	}

	function clearcache() {
		ob_clean ();
		$this->core_model->cacheDeleteAll ();
		exit ( '1' );
	}

	/**
	 * @desc	prints the breadcrumbs path for categories ids sended by the $_REQUEST
	 * @param	the $_REQUEST array
	 * @return	print the breadcrumbs path
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function taxonomy_categories_print_breadcrumbs_path_for_categories_ids() {
		@ob_clean ();
		$data_to_work = $_REQUEST ['category_ids'];
		if (empty ( $data_to_work )) {
			exit ();
		}
		if ($data_to_work) {
			$data_to_work = explode ( ',', $data_to_work );
			$taxonomy_values = array ();
			foreacH ( $data_to_work as $item ) {
				$category_array = array ();

				$lets_get_parent_cats = $this->taxonomy_model->getParentsIds ( $item );
				if (! empty ( $lets_get_parent_cats )) {
					foreach ( $lets_get_parent_cats as $parent_cat_id ) {
						$parent_cat_item = $this->taxonomy_model->getSingleItem ( $parent_cat_id );
						if (! empty ( $parent_cat_item )) {
							$category_array [] = $parent_cat_item;
						}
					}
				}
				$taxonomy = $this->taxonomy_model->getSingleItem ( $item );
				$category_array [] = $taxonomy;
				$taxonomy_values [] = $category_array;
			}
		}
		if (! empty ( $taxonomy_values )) {
			foreach ( $taxonomy_values as $item ) {
				print '<ul class="selected-categories-breadcrumb-navigation">';
				$j = 0;
				foreach ( $item as $i ) {
					$j1 = $j + 1;
					$j2 = $j + 2;
					$to_print = false;
					if (! empty ( $item [$j1] )) {
						$to_print .= "<li class='breadcrumb-$j1 category-{$i['id']}'>";
					} else {
						$to_print .= "<li class='last breadcrumb-$j1 category-{$i['id']}'>";

					}

					if (! empty ( $item [$j1] )) {
						$to_print .= $i ['taxonomy_value'];
					} else {
						if (($_REQUEST ['append_custom_html_to_the_last_li']) != '') {
							$to_print .= $i ['taxonomy_value'];
							$to_print .= stripslashes ( $_REQUEST ['append_custom_html_to_the_last_li'] );
						} else {
							$to_print .= $i ['taxonomy_value'];
						}

					}
					$to_print .= '</li>';
					if (empty ( $item [$j1] )) {
						if (($_REQUEST ['append_last_li_with_custom_html']) != '') {
							$to_print .= "<li class='last breadcrumb-$j2 category-custom-html-li'>";
							$to_print .= stripslashes ( $_REQUEST ['append_last_li_with_custom_html'] );
							$to_print .= '</li>';
						}

					}
					foreach ( $i as $k => $v ) {
						$something = '{' . $k . '}';
						$to_print = str_ireplace ( $something, $v, $to_print );
					}
					print $to_print;
					$j ++;
				}
				print '</ul>';
			}
		}
		exit ();
	}

	/**
	 * @desc	loads UL categories tree by params sended by the $_REQUEST array
	 * @param	the $_REQUEST array
	 * @return	print the ul tree
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function taxonomy_categories_load_category_js_tree() {
		@ob_clean ();
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
		$tree_params = $_POST ['treeparams'];
		$test = $this->core_model->securityDecryptArray ( $tree_params );
		$tree_params = $test;
		$this->content_model->content_helpers_getCaregoriesUlTree ( $content_parent = $tree_params ['content_parent'], $link = $tree_params ['link'], $actve_ids = $tree_params ['actve_ids'], $active_code = $tree_params ['active_code'], $remove_ids = $tree_params ['remove_ids'], $removed_ids_code = $tree_params ['removed_ids_code'], $ul_class_name = $tree_params ['ul_class_name'], $include_first = $tree_params ['include_first'], $content_type = $tree_params ['content_type'], $li_class_name = $tree_params ['li_class_name'], $add_ids = false, $orderby = false, $only_with_content = false, $visible_on_frontend = false );
		exit ();
	}

	/**
	 * @desc	loads UL pages tree by params sended by the $_REQUEST array
	 * @param	the $_REQUEST array
	 * @return	print the ul tree
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function pages_load_category_js_tree() {
		@ob_clean ();
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
		$tree_params = $_POST ['treeparams'];
		$test = $this->core_model->securityDecryptArray ( $tree_params );
		$tree_params = $test;
		$this->content_model->content_helpers_getPagesAsUlTree ( $content_parent = $tree_params ['content_parent'], $link = $tree_params ['link'], $actve_ids = $tree_params ['active_ids'], $active_code = $tree_params ['active_code'], $remove_ids = $tree_params ['remove_ids'], $removed_ids_code = $tree_params ['removed_ids_code'] );
		//$this->content_model->content_helpers_getCaregoriesUlTree ( $content_parent = $tree_params ['content_parent'], $link = $tree_params ['link'], $actve_ids = $tree_params ['actve_ids'], $active_code = $tree_params ['active_code'], $remove_ids = $tree_params ['remove_ids'], $removed_ids_code = $tree_params ['removed_ids_code'], $ul_class_name = $tree_params ['ul_class_name'], $include_first = $tree_params ['include_first'], $content_type = $tree_params ['content_type'], $li_class_name = $tree_params ['li_class_name'] );
		exit ();
	}

	/**
	 * @desc	taxonomy Get Parent Ids ForId
	 * @param	$_REQUEST ['category_ids']
	 * @return	the ids of the parent items as comma demilited string
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function taxonomy_getParentsIds() {
		@ob_clean ();
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
		$data_to_work = $_REQUEST ['category_ids'];
		$data_to_work = explode ( ',', $data_to_work );
		if (empty ( $data_to_work )) {
			exit ();
		}
		$lets_get_parent_cats_return = array ();
		foreach ( $data_to_work as $item ) {
			$lets_get_parent_cats = $this->taxonomy_model->getParentsIds ( $item );
			if (! empty ( $lets_get_parent_cats )) {
				foreach ( $lets_get_parent_cats as $id ) {
					if (intval ( $id ) != 0) {
						$lets_get_parent_cats_return [] = $id;
					}
				}
			}
			//
		}
		$lets_get_parent_cats_return = array_unique ( $lets_get_parent_cats_return );
		$lets_get_parent_cats_return = implode ( ',', $lets_get_parent_cats_return );
		exit ( $lets_get_parent_cats_return );

	}

	/**
	 * @desc	get main site section for the selected categories and return its ID
	 * @param	$_REQUEST ['category_ids']
	 * @return	the id of the parent page $parent_page ['id']
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function taxonomy_categories_get_main_site_section_id_for_caegory_ids() {
		@ob_clean ();
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
		$data_to_work = $_REQUEST ['category_ids'];
		if (empty ( $data_to_work )) {
			exit ();
		}
		if ($data_to_work) {
			$data_to_work = explode ( ',', $data_to_work );
			$taxonomy_values = array ();
			$data_to_work = array_reverse ( $data_to_work );
			foreacH ( $data_to_work as $item ) {
				$parent_page = $this->content_model->contentsGetTheLastBlogSectionForCategory ( $item );
				if (! empty ( $parent_page )) {

					exit ( $parent_page ['id'] );
				}
				//var_dump($parent_page);
			}
		}
		exit ();
	}

	/**
	 * @desc	get main site section for the selected categories and return its url
	 * @param	$_REQUEST ['id']
	 * @return	print the url
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function content_get_url_by_id() {
		@ob_clean ();
		//require_once (APPPATH . 'controllers/admin/default_constructor.php');
		$id = $_REQUEST ['id'];
		if (intval ( $id ) == 0) {
			exit ();
		}
		$url = $this->content_model->getContentURLById ( intval ( $id ) );
		exit ( $url );
	}

	/**
	 * @desc	Generate Unique Url Title From Content Title
	 * @param	$_REQUEST ['id']
	 * @param	$_REQUEST ['content_title']
	 * @return	print the url
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function content_GenerateUniqueUrlTitleFromContentTitle() {
		@ob_clean ();
		//require_once (APPPATH . 'controllers/admin/default_constructor.php');
		$id = $_REQUEST ['id'];
		$the_content_title = $_REQUEST ['content_title'];
		if (intval ( $id ) == 0) {
			//exit ();
		}
		$url = $this->content_model->contentGenerateUniqueUrlTitleFromContentTitle ( intval ( $id ), $the_content_title );
		exit ( $url );
	}

	/**
	 * @desc	Add item to cart
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function cart_itemAdd() {
		//	@ob_clean ();


		//var_dump($_POST);
		//	var_dump($_REQUEST);


		if ($_POST) {

			$_POST ['length'] = $_POST ['item_length'];

			$cart = $this->cart_model->itemAdd ( $_POST );
			$cart = $this->cart_model->itemsGetQty ();
			print $cart;
		}
		exit ();
	}

	/**
	 * @desc	items Get Qty
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function cart_itemsGetQty() {
		@ob_clean ();
		$cart = $this->cart_model->itemsGetQty ();
		print $cart;
		exit ();
	}

	/**
	 * @desc	items Get Qty
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function cart_itemDeleteById() {
		@ob_clean ();
		$id = $_POST ['id'];
		$cart = $this->cart_model->itemDeleteById ( $id );
		//print $cart;.3
		exit ();
	}

	/**
	 * @desc	Empty Cart
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function cart_itemsEmptyCart() {
		@ob_clean ();
		$cart = $this->cart_model->itemsEmptyCart ();
		exit ();
	}

	/**
	 * @desc	order Place
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function cart_orderPlace() {
		@ob_clean ();
		if (empty ( $_POST )) {
			exit ();
		}

		$is_valid = FALSE;
		foreach ( $_POST as $k => $v ) {
			if ($k == 'first_name') {
				if ($v != false) {
					$is_valid = true;
				} else {
					$is_valid = FALSE;
				}
			}

			if ($k == 'last_name') {
				if ($v != false) {
					$is_valid = true;
				} else {
					$is_valid = FALSE;
				}
			}

			if ($k == 'email') {
				if ($v != false) {
					$is_valid = true;
				} else {
					$is_valid = FALSE;
				}
			}

			if ($k == 'country') {
				if ($v != false) {
					$is_valid = true;
				} else {
					$is_valid = FALSE;
				}
			}

			if ($k == 'city') {
				if ($v != false) {
					$is_valid = true;
				} else {
					$is_valid = FALSE;
				}
			}

			if ($k == 'address1') {
				if ($v != false) {
					$is_valid = true;
				} else {
					$is_valid = FALSE;
				}
			}

			if ($k == 'order_id') {
				if ($v != false) {
					$is_valid = true;
				} else {
					$is_valid = FALSE;
				}
			}

		}
		if ($is_valid == true) {
			$cart = $this->cart_model->orderPlace ( $_POST );
		}
		exit ();
	}

	function cart_ModifyItemProperties() {
		$id = $_POST ['id'];
		$property = $_POST ['propery_name'];
		$new_value = $_POST ['new_value'];

		if ($property != 'sid') {
			$new = array ();
			$new ['id'] = $id;
			$new [$property] = $new_value;
			$this->cart_model->itemSave ( $new );
		}
	}

	function cart_itemsGetTotal() {
		print ($this->cart_model->itemsGetTotal ( false, $this->session->userdata ( 'shop_currency' ) )) ;
	}

	function cart_sumByField() {
		$id = $_POST ['field'];
		print $this->cart_model->cartSumByFields ( $id );
	}

	function cart_removeItemFromCart() {
		$id = $_POST ['id'];
		print $this->cart_model->itemDeleteById ( $id );
	}

	function cart_getPromoCode() {
		$code = $_POST ['code'];
		$code = trim ( $code );
		if ($code == '') {
			exit ( '0' );
		}
		$get_promo = array ();
		$get_promo ['promo_code'] = $code;

		$codes = $this->cart_model->promoCodesGet ( $get_promo );
		if (! empty ( $codes )) {
			$codes = $codes [0];
			$this->session->set_userdata ( 'cart_promo_code', $codes ['promo_code'] );
			print json_encode ( $codes );

		} else {
			exit ( '0' );
		}
		//print $this->cart_model->itemDeleteById ( $id );
	}

	function cart_getTotal() {
		$code = $this->session->userdata ( 'cart_promo_code' );

		print $this->cart_model->itemsGetTotal ( $code, $this->session->userdata ( 'shop_currency' ) );
	}

	function cart_shippingCalculateToCountryName() {
		$country = $_POST ['country'];
		$country = trim ( $country );
		if ($country != '') {

		} else {
			$country = false;
		}
		print $this->cart_model->shippingCalculateToCountryName ( $country, $this->session->userdata ( 'shop_currency' ) );
	}

	function cart_shippingCalculateUPS() {
		@ob_clean ();

		if (! $this->session->userdata ( 'shipping_first_name' )) {
			print 'Missing Shipping First Name';
			exit ();
		}

		if (! $this->session->userdata ( 'shipping_last_name' )) {
			print 'Missing Shipping Last Name';
			exit ();
		}
		if (! $this->session->userdata ( 'shipping_company_name' )) {
			print 'Missing Shipping Company Name';
			exit ();
		}
		if (! $this->session->userdata ( 'shipping_user_email' )) {
			print 'Missing Shipping Email';
			exit ();
		}
		if (! $this->session->userdata ( 'shipping_user_phone' )) {
			print 'Missing Shipping Phone';
			exit ();
		}
		if (! $this->session->userdata ( 'shipping_city' )) {
			print 'Missing Shipping City';
			exit ();
		}
		if (! $this->session->userdata ( 'shipping_address' )) {
			print 'Missing Shipping Address';
			exit ();
		}
		if (! $this->session->userdata ( 'shipping_state' )) {
			print 'Missing Shipping State';
			exit ();
		}
		if (! $this->session->userdata ( 'shipping_zip' )) {
			print 'Missing Shipping Zip';
			exit ();
		}

		$dimensions = $this->cart_model->shippingGetOrderPackageSize ();

		$from_zip = $this->core_model->optionsGetByKey ( 'shop_orders_ship_from_zip' );
		if ($from_zip == false) {
			exit ( "Error no UPS default ZIP code set, please set option with key 'shop_orders_ship_from_zip' in the admin panel. This will be the default ZIP code from which you ship the goods" );
		}

		$data ['from_zip'] = $from_zip;

		global $cms_db_tables;

		$table = $cms_db_tables ['table_cart'];
		$session_id = $this->session->userdata ( 'session_id' );
		$q = "select * from $table where sid='$session_id' and order_completed='n'";

		$q = $this->core_model->dbQuery ( $q );

		$data ['shipping_service'] = $this->session->userdata ( 'shipping_service' );
		if (! $data ['shipping_service'])
			$data ['shipping_service'] = '03';

		$data ['shipping_address_type'] = $this->session->userdata ( 'shipping_address_type' );
		if (! $data ['shipping_address_type'])
			$data ['shipping_address_type'] = '01';

		$data ['shipping_to_zip'] = $this->session->userdata ( 'shipping_zip' );
		$data ['shipping_to_city'] = $this->session->userdata ( 'shipping_city' );

		$data ['shipping_company_name'] = $this->session->userdata ( 'shipping_company_name' );
		$data ['shipping_name'] = $this->session->userdata ( 'shipping_first_name' ) . ' ' . $this->session->userdata ( 'shipping_last_name' );
		$data ['shipping_user_phone'] = $this->session->userdata ( 'shipping_user_phone' );
		$data ['shipping_address'] = $this->session->userdata ( 'shipping_address' );
		$data ['shipping_state'] = $this->session->userdata ( 'shipping_state' );

		$cost = 0;
		if (! empty ( $q )) {
			foreach ( $q as $item ) {
				//function shippingUPSGetCost($from_zip, $to_zip, $service = '03', $length, $width, $height, $weight)
				$weight = false;
				$height = false;
				$width = false;
				$height = false;

				$data ['weight'] = (floatval ( $item ['weight'] ) * 1);
				$data ['height'] = (floatval ( $item ['height'] ) * 1);
				$data ['length'] = (floatval ( $item ['length'] ) * 1);
				$data ['width'] = (floatval ( $item ['width'] ) * 1);

				$data ['cost'] = $cost;

				$cost += (intval ( $item ['qty'] ) * $this->cart_model->shippingUPSGetCost ( $data ));

				for($i = 1; $i <= $item ['qty']; $i ++) {

				}

			}
			if (intval ( $cost ) != 0)
				$this->session->set_userdata ( 'shipping_total_charges', $cost );
		} else {
			return false;
		}

		$this->session->set_userdata ( 'shop_shipping_cost', $cost );

		print $cost;
		exit ();
	}

	function cart_set_shop_currency() {
		$currency = $_POST ['currency'];
		$currency = trim ( $currency );
		if ($currency != '') {

		} else {
			$currency = false;
		}
		print $this->cart_model->currencyChangeSessionData ( $currency );

	}

	function cart_check_cc() {
		$check = $this->cart_model->billingProcessCreditCard ();

		if ($check === true) {
			echo 'ok';
			exit ();
		} else {
			$error = strtoupper ( $check );

			$check_arr = array ();
			$check_arr = explode ( '&', $error );

			$error = '';
			foreach ( $check_arr as $key => $val ) {
				$pos = strpos ( $val, 'MSG=' );
				if ($pos !== false) {
					$error = substr ( $val, 4 );
					$error = str_replace ( "+", ' ', $error );
				}
			}
			if (! $error)
				$error = 'Invali Credit Card Number or CVV2';
			print ($error) ;
			exit ();

		}

	}

	function users_register() {
		@ob_clean ();
		$is_ok = false;
		$err = array ();
		foreach ( $_POST as $k => $v ) {
			if ($k == 'captcha') {

				include 'captcha/securimage.php';

				$img = new Securimage ( );
				$valid = $img->check ( $v );

				if ($valid == true) {
					$this->session->set_userdata ( 'valid_captcha', true );
					//echo "<center>Thanks, you entered the correct code.<br />Click <a href=\"{$_SERVER['PHP_SELF']}\">here</a> to go back.</center>";
				} else {
					$this->session->set_userdata ( 'valid_captcha', false );
					$err [] = 'Invalid CAPTCHA';
					//echo "<center>Sorry, the code you entered was invalid.  <a href=\"javascript:history.go(-1)\">Go back</a> to try again.</center>";


				}

			//print $v;
			}

		}

		//$captcha = $_POST ['captcha'];


		var_dump ( $_POST );

	}

	function user_delete_picture() {
		@ob_clean ();

		$user = $this->session->userdata ( 'user_session' );
		if ($user ['user_id']) {
			//$user_info = $this->users_model->getUserById($user ['user_id']);
			$media = array ();
			$media ['to_table'] = 'table_users';
			$media ['to_table_id'] = $user ['user_id'];

			$media = $this->core_model->mediaGet ( $media ['to_table'], $to_table_id = $media ['to_table_id'], $media_type = 'picture' );
			if (! empty ( $m ['pictures'] )) {
				foreach ( $m ['pictures'] as $m ) {
					$this->core_model->mediaDelete ( $m [0] ['id'] );
				}
			}

		}

	//var_dump ( $_POST );


	}

	function status_update() {
		if ($_POST) {

			$this->_requireLogin ();

			$currentUser_id = $this->core_model->userId ();

			$status = array ('user_id' => $currentUser_id, 'status' => $_POST ['status'] );

			$updated = $this->core_model->saveData ( TABLE_PREFIX . 'users_statuses', $status );
			$this->users_model->cleanOldStatuses ( $currentUser );
			$this->core_model->cleanCacheGroup ( 'users/statuses' );
			echo $updated;

		}
	}

	function _requireLogin() {
		$user_session = $this->session->userdata ( 'user_session' );
		if (strval ( $user_session ['is_logged'] ) != 'yes') {
			exit ( 'Error: Login reguired.' );
		}
	}

	/*~~~~~~~~~~~~~~~ Messages related methods ~~~~~~~~~~~~~~~~~~~*/

	function message_send() {
		exit ( 'Function ' . __FUNCTION__ . ' moved to the users API' );

		if ($_POST) {

			$this->_requireLogin ();

			$currentUser = $this->session->userdata ( 'user' );

			$messageKey = $_POST ['mk'];
			unset ( $_POST ['mk'] );
			$messageKey = base64_decode ( $messageKey );
			$messageKey = $this->core_model->securityDecryptString ( $messageKey );

			if ($currentUser ['email'] != $messageKey) {
				exit ( 1 );
			}

			$data = $_POST;
			$data = stripFromArray ( $data );
			$data = htmlspecialchars_deep ( $data );

			/*
			 * Format data array
			 */

			// from user
			$data ['from_user'] = intval ( $currentUser ['id'] );

			// to user
			$data ['to_user'] = intval ( $data ['receiver'] );
			unset ( $data ['receiver'] );

			// parent id
			if ($data ['conversation']) {
				$data ['parent_id'] = $data ['conversation'];
			}
			unset ( $data ['conversation'] );

			// validate 'to_user'
			if ($data ['parent_id']) {

				$parentMessage = $this->core_model->fetchDbData ( 'firecms_messages', array (array ('id', $data ['parent_id'] ) ) );

				$parentMessage = $parentMessage [0];

				if (! in_array ( $data ['to_user'], array ($parentMessage ['from_user'], $parentMessage ['to_user'] ) )) {
					throw new Exception ( 'Cheating detected.' );
				}

			}

			$sent = $this->core_model->saveData ( 'firecms_messages', $data );

			echo $sent;

			$this->core_model->cleanCacheGroup ( 'messages' );

		}

	}

	/**
	 * Mark given message as read
	 */
	function message_read() {
		exit ( 'Function ' . __FUNCTION__ . ' moved to the users API' );
		if ($_POST) {

			$this->_requireLogin ();

			$messageId = $_POST ['id'];

			$currentUser = $this->session->userdata ( 'user' );

			$message = $this->core_model->fetchDbData ( 'firecms_messages', array (array ('id', $messageId ) ) );

			$message = $message [0];

			if ($currentUser ['id'] != $message ['to_user']) {
				throw new Exception ( 'You have no rights to read this message.' );
			}

			$read = $this->core_model->saveData ( 'firecms_messages', array ('id' => $message ['id'], 'is_read' => 'y' ) );

			echo $read;

			$this->core_model->cleanCacheGroup ( 'messages' );
		}

	}

	/**
	 * Mark given message as not read
	 */
	function message_unread() {
		exit ( 'Function ' . __FUNCTION__ . ' moved to the users API' );
		if ($_POST) {

			$this->_requireLogin ();

			$messageId = $_POST ['id'];

			$currentUser = $this->session->userdata ( 'user' );

			$message = $this->core_model->fetchDbData ( 'firecms_messages', array (array ('id', $messageId ) ) );

			$message = $message [0];

			if ($currentUser ['id'] != $message ['to_user']) {
				throw new Exception ( 'You have no rights to unread this message.' );
			}

			$read = $this->core_model->saveData ( 'firecms_messages', array ('id' => $message ['id'], 'is_read' => 'n' ) );

			echo $read;

			$this->core_model->cleanCacheGroup ( 'messages' );
		}
	}

	/**
	 * Delete message
	 */
	function message_delete() {

		exit ( 'Function ' . __FUNCTION__ . ' moved to the users API' );
		if ($_POST) {

			$this->_requireLogin ();

			$messageId = $_POST ['id'];

			$currentUser = $this->session->userdata ( 'user' );

			$message = $this->core_model->fetchDbData ( 'firecms_messages', array (array ('id', $messageId ) ) );

			$message = $message [0];

			if ($message ['from_user'] == $currentUser ['id']) {
				$deletedFrom = 'sender';
			} elseif ($message ['to_user'] == $currentUser ['id']) {
				$deletedFrom = 'receiver';
			} else {
				throw new Exception ( 'You have no permission to delete this message.' );
			}

			$deleted = $this->core_model->saveData ( 'firecms_messages', array ('id' => $message ['id'], 'deleted_from_' . $deletedFrom => 'y' ) );

			echo $deleted;

			$this->core_model->cleanCacheGroup ( 'messages' );
		}

	}

	/*~~~~~~~~~~~~~~~ Following system methods ~~~~~~~~~~~~~~~~~~~*/

	public function followUser() {
		if ($_POST) {

			$this->_requireLogin ();

			$followerId = $_POST ['follower_id'];
			$follow = ( bool ) $_POST ['follow'];
			$special = ( bool ) $_POST ['special'];

			$currentUser = $this->session->userdata ( 'user' );

			$followed = $this->users_model->saveFollower ( array ('user' => $currentUser ['id'], 'follower' => $followerId, 'follow' => $follow, 'special' => $special ) );

			echo $followed;

			// send user notification
			if ($special) {

				$notification = array ('from_user' => $currentUser ['id'], 'to_user' => $followerId, 'type' => 'add_to_circle' );

			//$this->users_model->sendNotification($notification);


			} elseif ($follow) {

				$follower = $this->users_model->getUserById ( $followerId );

				$notification = array ('from_user' => $currentUser ['id'], 'to_user' => $followerId, 'type' => 'add_to_followers', 'message_params' => array ('circle_url' => site_url ( 'userbase/action:profile/username:' . $follower ['username'] ) ) )//TODO: put user's sircle url
;

			//	$this->users_model->sendNotification($notification);


			}

			$this->core_model->cleanCacheGroup ( 'follow' );
		}
	}

	public function dashboardCounts() {
		if ($_POST) {

			$this->_requireLogin ();

			$currentUser = $this->session->userdata ( 'user' );

			$statusesIds = $_POST ['statusesIds'];
			$contentsIds = $_POST ['contentsIds'];

			/* comments and votes count */

			$contentVotes = $this->votes_model->votesGetCounts ( 'table_content', $contentsIds );
			$contentComments = $this->comments_model->commentsGetCounts ( 'table_content', $contentsIds );

			$statusesVotes = $this->votes_model->votesGetCounts ( 'table_users_statuses', $statusesIds );
			$statusesComments = $this->comments_model->commentsGetCounts ( 'table_users_statuses', $statusesIds );

			$stats = array ('statuses' => array ('votes' => $statusesVotes, 'comments' => $statusesComments ), 'contents' => array ('votes' => $contentVotes, 'comments' => $contentComments ) );

			echo json_encode ( $stats );

		}
	}

}


/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */