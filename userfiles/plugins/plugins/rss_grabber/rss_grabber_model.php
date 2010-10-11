<?php class rss_grabber_model extends Model {
	public $db_tables;
	
	function __construct() {
		parent::Model ();
		$plugin_db_tables = array ( );
		$plugin_db_tables ['rss_grabber_plugin_feeds'] = TABLE_PREFIX . "rss_grabber_plugin_feeds";
		$this->db_tables = $plugin_db_tables;
	}
	
	function test() {
		//print 1;
	//$posts = $this->content_model->contentGetLatestPosts ( array (0, 4 ), $categories = false, $featured = true );
	//	var_dump ( $posts );
	//return $posts;
	}
	
	function saveFeed($data) {
		$table = $this->db_tables ['rss_grabber_plugin_feeds'];
		$this->core_model->cacheDelete ( 'cache_group', 'rss_grabber' );
		$this->core_model->cacheDelete ( 'cache_group', 'rss' );
		$url = $data ['feed_url'];
		$data ['feed_url_md5'] = md5 ( $data ['feed_url'] );
		$check_if_exist = array ( );
		$check_if_exist ['feed_url'] = $data ['feed_url'];
		$check_if_exist = $this->getFeeds ( $check_if_exist );
		$check_if_exist = $check_if_exist [0];
		if (! empty ( $check_if_exist )) {
			$data ['id'] = $check_if_exist ['id'];
		}
		
		//$this->core_model->deleteData ( $table, $data_to_delete );
		$data_to_save_options = array ( );
		$data_to_save_options ['delete_cache_groups'] = array ('rss', 'rss_grabber' );
		if (! empty ( $data )) {
			$id = $this->core_model->saveData ( $table, $data, $data_to_save_options );
		}
		//$this->core_model->cacheDeleteAll ();
		

		return $id;
	}
	
	function getActiveFeeds() {
		$data ['is_active'] = 'y';
		$feeds = $this->getFeeds ( $data );
		return $feeds;
	}
	
	function processNumberOfFeeds($num = 1) {
		$form_values = $this->getFeeds ( false, true );
		shuffle ( $form_values );
		
		if (! empty ( $form_values )) {
			$feed = $form_values [0];
			$this->rssGrabAndProcessFeeds ( $feed ['id'] );
			print 'Function processNumberOfFeeds processing: ' . $feed ['id'] . "\n";
		
		} else {
			print 'Function processAllFeeds No feeds' . "\n\n";
			return false;
		}
	}
	
	function processAllFeeds() {
		$form_values = $this->getFeeds ( false, true );
		if (! empty ( $form_values )) {
			foreach ( $form_values as $feed ) {
				$this->rssGrabAndProcessFeeds ( $feed ['id'] );
				print 'Function processAllFeeds processing: ' . $feed ['id'] . "\n";
			}
		} else {
			print 'Function processAllFeeds No feeds' . "\n\n";
		}
	}
	
	function getFeeds($data = array(), $only_for_processing = false) {
		$table = $this->db_tables ['rss_grabber_plugin_feeds'];
		
		if ($only_for_processing == true) {
			$times_of_feedsq = " select id, feed_check_interval, last_get_on from $table  ";
			$times_of_feedsq = $this->core_model->dbQuery ( $times_of_feedsq );
			
			$ids_to_get = array ( );
			$ids_to_get [] = 'this is fake id';
			foreach ( $times_of_feedsq as $some_feed ) {
				$time_string = strtotime ( $some_feed ['last_get_on'] );
				$cd = strtotime ( $some_feed ['last_get_on'] );
				$retDAY = date ( 'Y-m-d H:i:s', mktime ( date ( 'H', $cd ), date ( 'i' + intval ( $some_feed ['feed_check_interval'] ), $cd ), date ( 's', $cd ), date ( 'm', $cd ) + $mth, date ( 'd', $cd ), date ( 'Y', $cd ) ) );
				
				$now = date ( "Y-m-d H:i:s" );
				$time_string_then = strtotime ( $retDAY );
				$time_string_now = strtotime ( $now );
				$time_string_then = intval ( $time_string_then );
				$time_string_now = intval ( $time_string_now );
				
				//var_dump($time_string_now, $time_string_then); 
				if ($time_string_now >= $time_string_then) {
					$ids_to_get [] = $some_feed ['id'];
					//	print 'asdas';
				}
			
			}
		} else {
			$ids_to_get = false;
		}
		//	var_dump($ids_to_get);
		//getDbData($table = false, $criteria = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false)
		

		$data = $this->core_model->getDbData ( $table, $criteria = $data, $limit = false, $offset = false, $orderby = false, $cache_group = 'rss', $debug = false, $ids = $ids_to_get, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = true );
		return $data;
	}
	
	function deleteFeedById($id) {
		$table = $this->db_tables ['rss_grabber_plugin_feeds'];
		
		$data = array ( );
		$data ['id'] = $id;
		$del = $this->core_model->deleteData ( $table, $data, 'rss' );
		return $data;
	}
	
	function rssGrabAndProcessFeeds($id) {
		global $cms_db_tables;
		$table_conent = $cms_db_tables ['table_content'];
		$table_content = $table_conent;
		$table = $this->db_tables ['rss_grabber_plugin_feeds'];
		
		$now = date ( "Y-m-d H:i:s" );
		$q = " UPDATE $table set last_get_on='$now' where id='$id'  ";
		$q = $this->core_model->dbQ ( $q );
		
		$feed = array ( );
		$feed ['id'] = $id;
		$feed = $this->getFeeds ( $feed );
		$feed = $feed [0];
		//var_dump($feed);
		

		if (stristr ( $feed ['feed_url'], 'http' ) == true) {
			/*require_once ('Zend/Feed.php');
			require_once ('Zend/Feed/Rss.php');
			require_once ('Zend/Feed/Atom.php');*/
			require_once ('feedparser/simplepie.inc');
			$feed_parser = new SimplePie ( );
			$feed_parser->set_feed_url ( $feed ['feed_url'] );
			$feed_parser->enable_order_by_date ( true );
			$feed_parser->set_cache_location ( CACHEDIR );
			$feed_parser->init ();
			$feed_parser->handle_content_type ();
			$channel = $feed_parser->get_items ();
			/*<?php  ?>
			<?php  foreach ($items as $item): ?>
			<strong><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?>



			</a></strong> - <?php echo $item->get_date('j M Y'); ?>

			<p><?php echo $item->get_description(); ?></p>
			<?php endforeach; ?>
			*/
			
			print "Processing {$feed['feed_name']}\n";
			//	$channel = new Zend_Feed_Rss ( $feed ['feed_url'] );
			//$channel = Zend_Feed::import ( $feed ['feed_url'] );
			$to_save = array ( );
			$to_save ['content_type'] = 'post';
			$to_save ['is_from_rss'] = 'y';
			$to_save ['rss_feed_id'] = $feed ['id'];
			//$to_save ['original_link'] = $feed ['feed_url'];
			$to_save ['original_link_no_follow'] = $feed ['original_link_no_follow'];
			
			$taxonomy_categories = array ($feed ['feed_content_category'] );
			$taxonomy = $this->taxonomy_model->getParents ( $feed ['feed_content_category'] );
			if (! empty ( $taxonomy )) {
				foreach ( $taxonomy as $i ) {
					$taxonomy_categories [] = $i;
				}
			}
			//var_dump($taxonomy);
			//exit;
			$to_save ['taxonomy_categories'] = $taxonomy_categories;
			//get the parent page
			

			$parent_page = $this->content_model->contentsGetTheFirstBlogSectionForCategory ( $feed ['feed_content_category'] );
			
			if (! empty ( $parent_page )) {
				$to_save ['content_parent'] = $parent_page ['id'];
				foreach ( $channel as $item ) {
					
					$to_save ['content_title'] = html_entity_decode ( $item->get_title () );
					$to_save ['content_title'] = str_ireplace ( '"', ' ', $to_save ['content_title'] );
					$to_save ['content_title'] = str_ireplace ( "'", ' ', $to_save ['content_title'] );
					$to_save ['original_link'] = $item->get_permalink ();
					$to_save ['content_url'] = $this->core_model->url_title ( $to_save ['content_title'] );
					$check_url = array ( );
					$check_url ['content_url'] = $to_save ['content_url'];
					$check_url = $this->content_model->getContent ( $check_url, $orderby = false, $limit = false, $count_only = false, true );
					if (empty ( $check_url )) {
						//$to_save ['content_url'] = $to_save ['content_url'] . '-' . date ( 'YmdHis' );
						$to_save ['content_unique_id'] = md5 ( strtolower ( ($item->get_permalink ()) ) );
						
						$imgs = $item->get_enclosures ();
						if (! empty ( $imgs )) {
							$parsed_images_append = false;
							$parsed_images = array ( );
							foreach ( $imgs as $im ) {
								$type = $im->type;
								if (stristr ( $type, 'image' ) == true) {
									$some_image = $im->link;
									$parsed_images [] = $some_image;
								}
								if (! empty ( $parsed_images )) {
									foreach ( $parsed_images as $parsed_imng ) {
										//$parsed_images_append.=  " <img src=\"$parsed_imng\"  allign='left' /> ";
										$parsed_images_append .= "<img src=\"$parsed_imng\" />";
									}
								}
								//print $some_image;
							}
						
						} else {
							$parsed_images_append = false;
						}
						//var_dump($parsed_images);
						//exit;
						//var_dump($item->get_content());
						//exit;
						//$to_save ['content_body'] = $parsed_images_append. $item->get_content();
						$to_save ['content_body'] = $item->get_content ();
						//print $to_save ['content_body'] ;
						//exit;
						$check = array ( );
						$check ['content_unique_id'] = $to_save ['content_unique_id'];
						$check ['content_type'] = 'post';
						//$data = $this->content_model->getContent ( $check );
						$q = " SELECT count(*) as qty from $table_conent where  content_unique_id = '{$check ['content_unique_id']}'  ";
						$q = $this->core_model->dbQuery ( $q );
						$q = $q [0] ['qty'];
						$q = intval ( $q );
						
						if ($q == 0) {
							if ($to_save ['content_url'] != '') {
								if (trim ( $to_save ['content_body'] ) != '') {
									$tags = false;
									//	$tags = $this->taxonomy_model->taxonomyGenerateAndGuessTagsFromString ( $to_save ['content_body'] );
									$tags = trim ( $tags );
									if ($tags != '') {
										//$to_save ['taxonomy_tags_csv'] = $tags;
									//$to_save ['taxonomy_tags_csv'] = false;
									//var_dump ( $tags );
									}
									
									$to_save ['created_on'] = date ( 'Y-m-d H:i:s' );
									$to_save ['updated_on'] = date ( 'Y-m-d H:i:s' );
									
									//var_dump($to_save);
									//exit; 
									

									$last_skip_check = false;
									
									$q = " SELECT count(*) as qty from $table_conent where  original_link = '{$to_save ['original_link']}'  ";
									$q = $this->core_model->dbQuery ( $q );
									//	var_dump($q);
									$q = $q [0] ['qty'];
									$q = intval ( $q );
									if ($q != 0) {
										$last_skip_check = 'skip: original link found' . $item->get_permalink () . "  <br /> <br />";
									}
									
									$q = " SELECT count(*) as qty from $table_conent where  content_url = '{$to_save ['content_url']}'  ";
									$q = $this->core_model->dbQuery ( $q );
									//	var_dump($q);
									$q = $q [0] ['qty'];
									$q = intval ( $q );
									if ($q != 0) {
										$last_skip_check = 'skip: content_url  found' . "  <br /> <br />";
									}
									
									$q = " SELECT count(*) as qty from $table_conent where  content_title = '{$to_save ['content_title']}'  ";
									$q = $this->core_model->dbQuery ( $q );
									//	var_dump($q);
									$q = $q [0] ['qty'];
									$q = intval ( $q );
									if ($q != 0) {
										$last_skip_check = 'skip: content_title  found' . "  <br /> <br />";
									}
									
									$q = " SELECT count(*) as qty from $table_conent where  content_unique_id = '{$to_save ['content_unique_id']}'  ";
									$q = $this->core_model->dbQuery ( $q );
									//	var_dump($q);
									$q = $q [0] ['qty'];
									$q = intval ( $q );
									if ($q != 0) {
										$last_skip_check = 'skip: content_unique_id  found' . "  <br /> <br />";
									}
									
									//
									$to_save ['content_body'] = str_ireplace ( 'If you find an exclusive RSS freebie on this feed or on the live WDD website, please use the following code to download it: z6LK9M', '', $to_save ['content_body'] );
									$to_save ['content_body'] = str_ireplace ( 'Written exclusively for WDD', '', $to_save ['content_body'] );
									
									//
									
									if (strlen ( trim($to_save ['content_body'] )) > 350) {
										if (stristr ( $to_save ['content_body'], 'TUTSPLUS' ) == true) {
											$last_skip_check = 'skip: TUTSPLUS  found' . "  <br /> <br />";
										}
										
										if (stristr ( $to_save ['content_body'], '&acirc;asdasdada' ) == false) {
											if ($last_skip_check == false) {
												//var_dump($to_save);
												$id = $this->content_model->saveContent ( $to_save , false);
												//print 'Sleep a while ';
												//sleep ( 2 );
												print 'Save: ' . $item->get_permalink () . " $id <br /> <br />";
											} else {
												print $last_skip_check;
											}
										} else {
											print 'skip: &acirc; ' . $item->get_permalink () . "  <br /> <br />";
										
										}
									} else {
										print 'skip: small article strlen()' . $item->get_permalink () . "  <br /> <br />";
									
									}
									
								//$this->taxonomy_model->taxonomyGenerateAndGuessTagsAndSaveTagsForContentItemId ( $id );
								

								} else {
									print 'skip: no content to save, empty  description string' . $item->get_permalink () . "  <br /> <br />";
								
								}
							
							} else {
								print 'skip: ' . $to_save ['content_url'];
							}
							//var_dump ( $to_save );
						} else {
							print 'Skip: ' . $item->get_permalink () . " <br /> <br />";
						}
					} else {
						print 'Skip2: ' . $item->get_permalink () . " <br /> <br />";
					}
				
				}
			} else {
				print 'Skip: Cant find parent page?!?! Please define at least 1 section from this category or from its parents!' . " <br /> <br />";
			}
		}
		$this->core_model->cacheDelete ( 'cache_group', 'content' );
		$this->core_model->cacheDelete ( 'cache_group', 'rss' );
		//$this->core_model->cacheDelete ( 'cache_group', 'global' );
		$this->core_model->cacheDelete ( 'cache_group', 'taxonomy' );
		print 'Done: rssGrabAndProcessFeeds ' . $id;
	}
	
	function rssGetTitleFromFeedUrl($url) {
		require_once ('feedparser/simplepie.inc');
		$criteria ['feed_url'] = $url;
		if ($criteria ['feed_url'] != '') {
			$link = $criteria ['feed_url'];
			$linkbits = parse_url ( $link );
			$host = $linkbits ['host'];
			//********************************************
			//So now $host = www.url.com
			//All I have to do is remove the 'www.':
			//********************************************
			// find pos of first dot
			if (stristr ( $host, 'www.' ) == true) {
				$dot_pos = strpos ( $host, '.', 0 ) + 1;
				// make a new substring
				$domain = substr ( $host, $dot_pos );
			} else {
				$domain = $host;
			}
			$criteria ['feed_domain'] = $domain;
		}
		if (stristr ( $criteria ['feed_url'], 'http' ) == true) {
			$feed = new SimplePie ( );
			$feed->set_feed_url ( $criteria ['feed_url'] );
			$feed->enable_order_by_date ( false );
			$feed->set_cache_location ( CACHEDIR );
			$feed->init ();
			$criteria ['feed_title'] = $feed->get_title ();
		
		}
		return trim ( $criteria ['feed_title'] );
	}

}
