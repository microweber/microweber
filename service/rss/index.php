<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Demo of how to use SimplePie and a flat file database to display old feed items</title>
	</head>
	<body>

		<?php

		/*
		 This is a demo showing how to use SimplePie and a flat file database to display previous feed items like Google Reader.

		 Summary
		 Load the flat file db into array.
		 Run SimplePie.
		 Add new feed items to the array.
		 Save the array back to the flat file db.
		 Loop through the db to display feed items.

		 Author: Michael Shipley (http://www.michaelpshipley.com/)

		 */

		require 'simplepie.php';

		// db holder
		$savedItems = array();

		// db file name
		$savedItemsFilename = 'saveditems.php';

		// max days to keep items in db
		$numberOfDays = 3;

		$numberOfDaysInSeconds = ($numberOfDays * 24 * 60 * 60);
		$expireDate = time() - $numberOfDaysInSeconds;

		$urls = array('http://feeds.feedburner.com/dezeen','http://www.behance.net/feeds/projects','http://cnet.tumblr.com/rss','http://feeds.feedburner.com/thr/news','http://feeds.gawker.com/Gizmodo/full', 'http://www.engadget.com/rss.xml');
		$feed = new SimplePie();
		$feed -> set_feed_url($urls);
		$feed -> set_cache_duration(10000);
		$feed -> init();

		/*
		 load flat file db into array
		 */
		if (file_exists($savedItemsFilename)) {
			$savedItems = unserialize(file_get_contents($savedItemsFilename));
			if (!$savedItems) {
				$savedItems = array();
			}
		}

		/*
		 Loop through items to find new ones and insert them into db
		 */
		foreach ($feed->get_items() as $item) {

			// if item is too old dont even look at it
			if ($item -> get_date('U') < $expireDate)
				continue;

			// make id
			$id = md5($item -> get_id());

			// if item is already in db, skip it
			if (isset($savedItems[$id])) {
				continue;
			}

			// found new item, add it to db
			$i = array();
			$i['title'] = $item -> get_title();
			$i['link'] = $item -> get_link();
			$i['author'] = '';
			$author = $item -> get_author();
			if ($author) {
				$i['author'] = $author -> get_name();
			}
			$i['date'] = $item -> get_date('U');
			$i['content'] = $item -> get_content();
			$feed = $item -> get_feed();
			$i['feed_link'] = $feed -> get_permalink();
			$i['feed_title'] = $feed -> get_title();

			$savedItems[$id] = $i;
		}

		/*
		 remove expired items from db
		 */
		$keys = array_keys($savedItems);
		foreach ($keys as $key) {
			if ($savedItems[$key]['date'] < $expireDate) {
				unset($savedItems[$key]);
			}
		}

		/*
		 sort items in reverse chronological order
		 */
		function customSort($a, $b) {
			return $a['date'] <= $b['date'];
		}

		uasort($savedItems, 'customSort');

		/*
		 save db
		 */
		if (!file_put_contents($savedItemsFilename, serialize($savedItems))) {
			echo("<strong>Error: Can't save items.</strong><br>");
		}

		/*
		 display all items from db

		 echo '<h2>SimplePie + flat file database</h2>';
		 $count = 1;
		 foreach($savedItems as $item)
		 {
		 echo $count++ . '. ';
		 echo '<strong>' . $item['feed_title'] . '</strong>';
		 echo ' : ';
		 echo $item['title'];
		 echo '<br>';
		 echo '<small>' . date('r',$item['date']) . '</small>';
		 echo '<br>';
		 echo '<br>';
		 }

		 /*
		 for comparison, show all feed items using SimplePie only
		 */
		set_time_limit(0);
		$tmp_fname = tempnam("./cache", "COOKIE");

		$rem_image = 'C:/xampp/htdocs/1k/userfiles/media/downloaded/';
		if (!is_dir($rem_image)) {
			mkdir($rem_image);
		}

		echo '<h2>SimplePie only</h2>';
		$count = 1;
		foreach ($feed->get_items() as $item) {

			$i = array();
			$i['api_key'] = 'aaa';
			$i['id'] = '0';
			$i['content_type'] = 'post';
			$i['subtype'] = 'post';

			$i['parent'] = $_GET['p'];
			$i['title'] = $item -> get_title();
			$i['url'] = str_replace(' ', '-', htmlentities($i['title']));
			$zxzz1 = $i['screenshot_url'] = get_first_image_url($item -> get_content());

			if ($zxzz1 != false and strstr($zxzz1,'.jpg')) {
				$thefn = md5($zxzz1) . basename($zxzz1);
				$rem_image_1 = $rem_image . $thefn;
				if (!file_exists($rem_image_1)) {
				}

				if (!file_exists($rem_image_1)) {
					url_download1remp($zxzz1, false, $rem_image_1);
				}
				print $rem_image_1;
				if (file_exists($rem_image_1)) {
					//
				}
			}
			$i['screenshot_url'] = false;
			//file_get_contents($i['screenshot_url']);
$i['content'] = $item -> get_content();
			//if ($i['screenshot_url'] != false) {
			if ($zxzz1 != false and strstr($zxzz1,'.jpg') and file_exists($rem_image_1) and $i['content']  != '') {

				$i['original_link'] = $item -> get_link();
				//$i['author'] = '';
				//$author = $item -> get_author();

				$i['created_on'] = $item -> get_date('U');
				
				$i['created_on'] = date("Y-m-d H:i:s");
				$i['content'] = str_replace($zxzz1, '{SITE_URL}userfiles/media/downloaded/' . $thefn, $i['content']);
				//	$feed = $item -> get_feed();
				//	$i['feed_link'] = $feed -> get_permalink();
				//$i['feed_title'] = $feed -> get_title();

				//	var_dump($i);

				$is_there = "http://pecata/1k/api/get_content?api_key=aaa&title=" . $i['title'];

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $is_there);

				curl_setopt($ch, CURLOPT_COOKIEJAR, $tmp_fname);
				curl_setopt($ch, CURLOPT_COOKIEFILE, $tmp_fname);
				$is_there = curl_exec($ch);
				$is_there = json_decode($is_there, 1);
				//var_dump($is_there);
				curl_close($ch);
				if (is_array($is_there) == false) {
					$url = 'http://pecata/1k/api/save_content';
					$fields = $i;
					$fields_string = '';
					foreach ($fields as $key => $value) { $fields_string .= $key . '=' . $value . '&';
					}
					rtrim($fields_string, '&');
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_POST, count($fields));
					curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
					curl_setopt($ch, CURLOPT_COOKIEJAR, $tmp_fname);
					curl_setopt($ch, CURLOPT_COOKIEFILE, $tmp_fname);
					$result = curl_exec($ch);
					var_dump($result);
					curl_close($ch);
					//break;
				}
			}

		}

		/*
		 Total counts
		 */
		//echo '<h2>Total item counts</h2>';
		//echo 'Database item count: ' . count($savedItems);
		echo '<br>';
		echo 'SimplePie item count: ' . $feed -> get_item_quantity();
		echo '<br>';
		?>
	</body>
</html>