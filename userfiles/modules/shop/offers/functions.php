<?php
api_expose_admin('offer_save');
function offer_save($offerData = array())
{
	$json = array();
	$ok = false;
	$errorMessage = '';
	$table = 'offers';

	if (strstr($offerData['product_id'],'|')) {
		$id_parts = explode('|',$offerData['product_id']);
		$offerData['product_id'] = $id_parts[0];
		$offerData['price_key'] = $id_parts[1];
	}

	if (!is_numeric($offerData['offer_price'])) {
		$errorMessage .= 'offer price must be a number.<br />';
	}

	if (!empty($offerData['expires_at'])) {
		$date_db_format = get_date_db_format($offerData['expires_at']);
		$offerData['expires_at'] = date('Y-m-d H:i:s', strtotime($date_db_format));
	}

	if (empty($offerData['is_active'])) {
		$offerData['is_active'] = 0;
	} elseif($offerData['is_active']=='on'){
		$offerData['is_active'] = 1;
	}

	if (empty($errorMessage)) {
		$ok = true;
	}

	if ($ok) {
		$offerData['price_id'] = offer_get_price_id_by_key($offerData['price_key']);
		$offerId = db_save($table, $offerData);
		$json['offer_id'] = $offerId;
		$json['success_edit'] = true;
	} else {
		$json['error_message'] = $errorMessage;
	}

	return $json;
}

api_expose_admin('offers_get_all');
function offers_get_all()
{
	$table = 'offers';

	$offers = DB::table($table)->select('offers.id', 'offers.product_id', 'offers.price_key', 'offers.offer_price', 'offers.created_at', 'offers.updated_at', 'offers.expires_at', 'offers.is_active', 'content.title as product_title', 'content.is_deleted', 'custom_fields.name as price_name', 'custom_fields_values.value as price')
		->leftJoin('content', 'offers.product_id', '=', 'content.id')
		->where('content.content_type', '=', 'product')
		//->where('content.is_deleted', '=', 0)
		->leftJoin('custom_fields', 'offers.price_key', '=', 'custom_fields.name_key')
		->where('custom_fields.type', '=', 'price')
		->leftJoin('custom_fields_values', 'custom_fields.id', '=', 'custom_fields_values.custom_field_id')
		->get()
		->toArray();

	$specialOffers = array();
	foreach ($offers as $offer) {
		$specialOffers[] = get_object_vars($offer);
	}

	return $specialOffers;
}

//api_expose('offers_get_price');
function offers_get_price($product_id, $price_key)
{
	$offer = DB::table('offers')->select('id','offer_price')->where('product_id', '=', $product_id)->where('price_key','=',$price_key)->first();
	return $offer;
}

api_expose('offers_get_by_product_id');
function offers_get_by_product_id($product_id)
{
	$table = 'offers';

	$offers = DB::table($table)->select('offers.offer_price', 'offers.price_key', 'offers.expires_at', 'custom_fields.name as price_name', 'custom_fields_values.value as price')
		->leftJoin('content', 'offers.product_id', '=', 'content.id')
		->leftJoin('custom_fields', 'offers.price_key', '=', 'custom_fields.name_key')
		->leftJoin('custom_fields_values', 'custom_fields.id', '=', 'custom_fields_values.custom_field_id')
		->where('content.id', '=', (int)$product_id)
		->where('content.is_deleted', '=', 0)
		->where('offers.is_active', '=', 1)
		->where('custom_fields.type', '=', 'price')
		->get()
		->toArray();

	$specialOffers = array();
	foreach ($offers as $offer) {
		if(empty($offer->expires_at) || $offer->expires_at == '0000-00-00 00:00:00' || (strtotime($offer->expires_at) > strtotime("now"))){
			// converting price_name to lowercase to match key from in FieldsManager function get line 556
			$specialOffers[strtolower($offer->price_name)] = get_object_vars($offer);
		}
	}

	return $specialOffers;
}

api_expose_admin('offer_get_by_id');
function offer_get_by_id($offer_id)
{
	$table = "offers";

	return db_get($table, array(
		'id' => $offer_id,
		'single' => true,
		'no_cache' => true
	));
}

function offer_get_price_id_by_key($price_key)
{
	if (!is_admin()) return;

	$price_id = '';

	$table = "custom_fields";

	if($customfield = DB::table($table)->select('id')
		->where('name_key', '=', $price_key)
		->where('type', '=', 'price')
		->first()) {
		$price_id = $customfield->id;
	}

	return $price_id;
}

api_expose_admin('offer_delete');
function offer_delete()
{
	if (!is_admin()) return;

	$table = "offers";
	$offerId = (int) $_POST['offer_id'];

	$delete = db_delete($table, $offerId);

	if ($delete) {
		return array(
			'status' => 'success'
		);
	} else {
		return array(
			'status' => 'failed'
		);
	}
}

function offers_get_offer_product_ids()
{
	$offers = DB::table('offers')->select('product_id')->get();
	$product_ids = array();
	foreach($offers as $offer) {
		$product_ids[] = $offer->product_id;
	}
	return $product_ids;
}

api_expose_admin('offers_get_products');
function offers_get_products()
{
	$table = 'content';

	$offers = DB::table($table)->select('content.id as product_id', 'content.title as product_title', 'custom_fields.name as price_name', 'custom_fields.name_key as price_key', 'custom_fields_values.value as price')
		->leftJoin('custom_fields', 'content.id', '=', 'custom_fields.rel_id')
		->leftJoin('custom_fields_values', 'custom_fields.id', '=', 'custom_fields_values.custom_field_id')
		->where('content.content_type', '=', 'product')
		->where('content.is_deleted', '=', 0)
		->where('custom_fields.type', '=', 'price')
		->get()
		->toArray();

	$existingOfferProductIds = offers_get_offer_product_ids();

	$specialOffers = array();
	foreach ($offers as $offer) {
		if(!in_array($offer->product_id, $existingOfferProductIds)) {
			$specialOffers[] = get_object_vars($offer);
		}
	}

	return $specialOffers;
}

// a global class or function and system constant might be better
function get_date_format(){
	$date_format_set = get_option('date_format', 'website');
	$date_format_default = 'm/d/Y h:i a';
	$date_format = '';
	if($date_format_set && (strstr($date_format_set,'/')||strstr($date_format_set,'-'))){
		$date_format = str_replace('-','/',$date_format_set);
		if(strstr($date_format,'d/m')) {
			$date_format = 'd/m/Y h:i a';
		} else {
			$date_format = $date_format_default;
		}
	} else {
		$date_format = $date_format_default;
	}
	return $date_format;
}

function date_system_format($db_date){
	$date_format = get_date_format();
	$date = date_create($db_date);
	return date_format($date,$date_format);
}

function get_date_db_format($str_date){
	$date_format_set = get_option('date_format', 'website');
	$date_db_format = 'Y-m-d H:i:s';
	$date_format_default = 'm/d/Y h:i a';
	$str_db_date = '';
	if(strstr($str_date,'/')||strstr($str_date,'-')||strstr($str_date,'.')) {
		$str_date = str_replace('-','/',$str_date);
		$str_date = str_replace('.','/',$str_date);
	}
	if($date_format_set){
		$date = find_date($str_date);
		$str_db_date = $date['year'] . '-' . $date['month'] . '-' . $date['day'];
	}elseif($dateTime = DateTime::createFromFormat($date_format_default, $str_date)){
		$str_db_date = $dateTime->format($date_db_format);
	}else{
		$str_db_date = '0000-00-00 00:00:00';
	}
	return $str_db_date;
}

/**
 * Find Date in a String
 *
 * @author   Etienne Tremel
 * @license  http://creativecommons.org/licenses/by/3.0/ CC by 3.0
 * @link     http://www.etiennetremel.net
 * @version  0.2.0
 *
 * @param string  find_date( ' some text 01/01/2012 some text' ) or find_date( ' some text October 5th 86 some text' )
 * @return mixed  false if no date found else array: array( 'day' => 01, 'month' => 01, 'year' => 2012 )
 */
function find_date( $string ) {
  $shortenize = function( $string ) {
    return substr( $string, 0, 3 );
  };
  // Define month name:
  $month_names = array(
    "january",
    "february",
    "march",
    "april",
    "may",
    "june",
    "july",
    "august",
    "september",
    "october",
    "november",
    "december"
  );
  $short_month_names = array_map( $shortenize, $month_names );
  // Define day name
  $day_names = array(
    "monday",
    "tuesday",
    "wednesday",
    "thursday",
    "friday",
    "saturday",
    "sunday"
  );
  $short_day_names = array_map( $shortenize, $day_names );
  // Define ordinal number
  $ordinal_number = ['st', 'nd', 'rd', 'th'];
  $day = "";
  $month = "";
  $year = "";
  // Match dates: 01/01/2012 or 30-12-11 or 1 2 1985
  preg_match( '/([0-9]?[0-9])[\.\-\/ ]+([0-1]?[0-9])[\.\-\/ ]+([0-9]{2,4})/', $string, $matches );
  if ( $matches ) {
    if ( $matches[1] )
      $day = $matches[1];
    if ( $matches[2] )
      $month = $matches[2];
    if ( $matches[3] )
      $year = $matches[3];
  }
  // Match dates: Sunday 1st March 2015; Sunday, 1 March 2015; Sun 1 Mar 2015; Sun-1-March-2015
  preg_match('/(?:(?:' . implode( '|', $day_names ) . '|' . implode( '|', $short_day_names ) . ')[ ,\-_\/]*)?([0-9]?[0-9])[ ,\-_\/]*(?:' . implode( '|', $ordinal_number ) . ')?[ ,\-_\/]*(' . implode( '|', $month_names ) . '|' . implode( '|', $short_month_names ) . ')[ ,\-_\/]+([0-9]{4})/i', $string, $matches );
  if ( $matches ) {
    if ( empty( $day ) && $matches[1] )
      $day = $matches[1];
    if ( empty( $month ) && $matches[2] ) {
      $month = array_search( strtolower( $matches[2] ),  $short_month_names );
      if ( ! $month )
        $month = array_search( strtolower( $matches[2] ),  $month_names );
      $month = $month + 1;
    }
    if ( empty( $year ) && $matches[3] )
      $year = $matches[3];
  }
  // Match dates: March 1st 2015; March 1 2015; March-1st-2015
  preg_match('/(' . implode( '|', $month_names ) . '|' . implode( '|', $short_month_names ) . ')[ ,\-_\/]*([0-9]?[0-9])[ ,\-_\/]*(?:' . implode( '|', $ordinal_number ) . ')?[ ,\-_\/]+([0-9]{4})/i', $string, $matches );
  if ( $matches ) {
    if ( empty( $month ) && $matches[1] ) {
      $month = array_search( strtolower( $matches[1] ),  $short_month_names );
      if ( ! $month )
        $month = array_search( strtolower( $matches[1] ),  $month_names );
      $month = $month + 1;
    }
    if ( empty( $day ) && $matches[2] )
      $day = $matches[2];
    if ( empty( $year ) && $matches[3] )
      $year = $matches[3];
  }
  // Match month name:
  if ( empty( $month ) ) {
    preg_match( '/(' . implode( '|', $month_names ) . ')/i', $string, $matches_month_word );
    if ( $matches_month_word && $matches_month_word[1] )
      $month = array_search( strtolower( $matches_month_word[1] ),  $month_names );
    // Match short month names
    if ( empty( $month ) ) {
      preg_match( '/(' . implode( '|', $short_month_names ) . ')/i', $string, $matches_month_word );
      if ( $matches_month_word && $matches_month_word[1] )
        $month = array_search( strtolower( $matches_month_word[1] ),  $short_month_names );
    }
    $month = $month + 1;
  }
  // Match 5th 1st day:
  if ( empty( $day ) ) {
    preg_match( '/([0-9]?[0-9])(' . implode( '|', $ordinal_number ) . ')/', $string, $matches_day );
    if ( $matches_day && $matches_day[1] )
      $day = $matches_day[1];
  }
  // Match Year if not already setted:
  if ( empty( $year ) ) {
    preg_match( '/[0-9]{4}/', $string, $matches_year );
    if ( $matches_year && $matches_year[0] )
      $year = $matches_year[0];
  }
  if ( ! empty ( $day ) && ! empty ( $month ) && empty( $year ) ) {
    preg_match( '/[0-9]{2}/', $string, $matches_year );
    if ( $matches_year && $matches_year[0] )
      $year = $matches_year[0];
  }
  // Day leading 0
  if ( 1 == strlen( $day ) )
    $day = '0' . $day;
  // Month leading 0
  if ( 1 == strlen( $month ) )
    $month = '0' . $month;
  // Check year:
  if ( 2 == strlen( $year ) && $year > 20 )
    $year = '19' . $year;
  else if ( 2 == strlen( $year ) && $year < 20 )
    $year = '20' . $year;
  $date = array(
    'year'  => $year,
    'month' => $month,
    'day'   => $day
  );
  // Return false if nothing found:
  if ( empty( $year ) && empty( $month ) && empty( $day ) )
    return false;
  else
    return $date;
}