<?php
class Cacaomail_model extends Model {
	
	function __construct() {
		
		parent::Model ();
		//exit ();
		$this->cronjobs ();
	}
	
	function cronjobs() {
		$cron = array ( );
		
		$cron ['cronjob_group'] = 'mailer_process';
		$cron ['cronjob_name'] = 'processJobFeeds';
		$cron ['model_name'] = strtolower ( get_class () );
		$cron ['function_to_execute'] = 'processJobFeeds()';
		$cron ['is_active'] = 1;
		$cron ['interval_minutes'] = 1;
		CI::model('core')->cronjobRegister ( $cron );
		
		$cron = array ( );
		$cron ['cronjob_group'] = 'mailer_process';
		$cron ['cronjob_name'] = 'processJobFeedsGetPages';
		$cron ['model_name'] = strtolower ( get_class () );
		$cron ['function_to_execute'] = 'processJobFeedsGetPages()';
		$cron ['is_active'] = 1;
		$cron ['interval_minutes'] = 1;
		CI::model('core')->cronjobRegister ( $cron );
		
		$cron = array ( );
		$cron ['cronjob_group'] = 'mailer_work';
		$cron ['cronjob_name'] = 'doWork';
		$cron ['model_name'] = strtolower ( get_class () );
		$cron ['function_to_execute'] = 'doWork()';
		$cron ['is_active'] = 1;
		$cron ['interval_minutes'] = 1;
		CI::model('core')->cronjobRegister ( $cron );
		
		$cron = array ( );
		$cron ['cronjob_group'] = 'mailer_work';
		$cron ['cronjob_name'] = 'doWorkLoop';
		$cron ['model_name'] = strtolower ( get_class () );
		$cron ['function_to_execute'] = 'doWorkLoop()';
		$cron ['is_active'] = 1;
		$cron ['interval_minutes'] = 120;
		CI::model('core')->cronjobRegister ( $cron );
		
		
		
		$cron = array ( );
		$cron ['cronjob_group'] = 'mailer_housekeep';
		$cron ['cronjob_name'] = 'houseKeepStats';
		$cron ['model_name'] = strtolower ( get_class () );
		$cron ['function_to_execute'] = 'houseKeepStats()';
		$cron ['is_active'] = 1;
		$cron ['interval_minutes'] = 1440;
		CI::model('core')->cronjobRegister ( $cron );
		
		$cron = array ( );
		$cron ['cronjob_group'] = 'mailer_fake';
		$cron ['cronjob_name'] = 'fakeSend';
		$cron ['model_name'] = strtolower ( get_class () );
		$cron ['function_to_execute'] = 'fakeSend()';
		$cron ['is_active'] = 1;
		$cron ['interval_minutes'] = 144000000;
		CI::model('core')->cronjobRegister ( $cron );
	
	}
	
	function getMailAccounts($criteria = false, $limit = false, $offset = false) {
		require_once ('Zend/Date.php');
		$table = TABLE_PREFIX . 'cacaomail_mail_accounts';
		$query = CI::model('core')->getData ( $table, $criteria, $limit, $offset );
		$the_return = array ( );
		foreach ( $query as $item ) {
			$group_id_data = array ( );
			$group_id_data ['id'] = $item ['group_id'];
			$group_id_data ['group_to_table'] = 'cacaomail_mail_accounts';
			$group_id_data = CI::model('core')->groupsGet ( $group_id_data );
			$group_id_data = $group_id_data [0];
			if (! empty ( $group_id_data )) {
				$item ['group_id_data'] = $group_id_data;
			}
			$table2 = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
			$date = new Zend_Date ( );
			$date->sub ( '24', Zend_Date::HOUR );
			$past = $date->toValue ();
			$past = date ( "Y-m-d H:i:s", $past );
			$now = date ( "Y-m-d H:i:s" );
			$q = " SELECT count(*) as qty FROM $table2 where mailsent_date > '$past' and account_id = {$item['id']} ";
			//print $q ;
			$query = CI::db()->query ( $q );
			$row = $query->row_array ();
			//var_dump($row);
			//exit;
			$qty = $row ['qty'];
			$item ['sent_mails_for_today'] = intval ( $qty );
			$the_return [] = $item;
		}
		return $the_return;
	}
	
	function getMailAccountsGroups($criteria = false) {
		$data = $criteria;
		$data ['group_to_table'] = 'cacaomail_mail_accounts';
		$data = CI::model('core')->groupsGet ( $data );
		return $data;
	}
	
	function saveMailAccounts($criteria) {
		if (strval ( $criteria ['account_group_new'] ) != '') {
			//check if group is there
			$data = array ( );
			$data ['group_to_table'] = 'cacaomail_mail_accounts';
			$data ['group_name'] = strtolower ( $criteria ['account_group_new'] );
			//if($data['is_active'])
			$data = $this->input->xss_clean ( $data );
			$results = CI::model('core')->groupsGet ( $data );
			if (empty ( $results )) {
				$save = CI::model('core')->groupsSave ( $data );
				$criteria ['group_id'] = $save;
			} else {
				$results = $results [0];
				$id = $results ['id'];
				$criteria ['group_id'] = $id;
				//var_dump($results);
			}
		} else {
		
		}
		
		if (intval ( $criteria ['id'] ) != 0) {
			if (intval ( $criteria ['is_active'] ) != 1) {
				$criteria ['is_active'] = 0;
			}
		}
		
		$table = TABLE_PREFIX . 'cacaomail_mail_accounts';
		$criteria = $this->input->xss_clean ( $criteria );
		$save = CI::model('core')->saveData ( $table, $criteria );
		$cleanup_groups = CI::model('core')->groupsCleanup ( 'cacaomail_mail_accounts' );
		return $save;
	}
	
	function deleteMailAccounts($criteria) {
		$table = TABLE_PREFIX . 'cacaomail_mail_accounts';
		CI::model('core')->deleteData ( $table, $criteria );
		$cleanup_groups = CI::model('core')->groupsCleanup ( 'cacaomail_mail_accounts' );
	}
	
	function getJobfeeds($criteria = false, $limit = false, $offset = false) {
		$table = TABLE_PREFIX . 'cacaomail_mailing_lists';
		$query = CI::model('core')->getData ( $table, $criteria, $limit, $offset );
		$return_me = array ( );
		$table2 = TABLE_PREFIX . 'cacaomail_mails_to_send';
		$table3 = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
		foreach ( $query as $item ) {
			if ($item ['id'] != 0) {
				
				$q = "
	select count(*) as qty from $table2
	where is_active=1 and 
		(for_download=0 or for_download IS NULL ) and 
		feed_id={$item['id']} and
		(  job_email IS NOT NULL and
       job_email != '0' )
	";
				$query = CI::db()->query ( $q );
				$query = $query->row_array ();
				$query = $query ['qty'];
				
				$item ['count_mails_total'] = intval ( $query );
				
				$q = "
				select count(*) as qty from $table3
				where feed_id={$item['id']}
				";
				$query = CI::db()->query ( $q );
				$query = $query->row_array ();
				$query = $query ['qty'];
				$item ['count_mails_sent'] = intval ( $query );
				
				if ($item ['count_mails_sent'] != 0 and $item ['count_mails_total'] != 0) {
					$item ['count_mails_done_percentage'] = round ( ($item ['count_mails_sent'] / $item ['count_mails_total']) * 100 );
				} else {
					$item ['count_mails_done_percentage'] = 0;
				}
			
			}
			
			$return_me [] = $item;
		}
		
		return $return_me;
	}
	
	function getJobfeedsGroups($criteria = false) {
		$table = TABLE_PREFIX . 'cacaomail_mailing_lists';
		$data = $criteria;
		$data ['group_to_table'] = 'cacaomail_mailing_lists';
		$data = CI::model('core')->groupsGet ( $data );
		return $data;
	}
	
	function saveJobfeeds($criteria) {
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
			require_once ('Zend/Feed.php');
			require_once ('Zend/Feed/Rss.php');
			$channel = new Zend_Feed_Rss ( $criteria ['feed_url'] );
			$criteria ['feed_title'] = $channel->title ();
			foreach ( $channel as $item ) {
				//echo $item->title() . "\n";
			}
		}
		
		if (strval ( $criteria ['group_id_new'] ) != '') {
			//check if group is there
			$data = array ( );
			$data ['group_to_table'] = 'cacaomail_mailing_lists';
			$data ['group_name'] = strtolower ( $criteria ['group_id_new'] );
			$data = $this->input->xss_clean ( $data );
			$results = CI::model('core')->groupsGet ( $data );
			if (empty ( $results )) {
				$save = CI::model('core')->groupsSave ( $data );
				$criteria ['group_id'] = $save;
			} else {
				$results = $results [0];
				$id = $results ['id'];
				$criteria ['group_id'] = $id;
				//var_dump($results);
			}
		} else {
		
		}
		
		$table = TABLE_PREFIX . 'cacaomail_mailing_lists';
		$criteria = $this->input->xss_clean ( $criteria );
		
		//var_dump($criteria);
		//exit;
		$save = CI::model('core')->saveData ( $table, $criteria );
		$cleanup_groups = CI::model('core')->groupsCleanup ( 'cacaomail_mailing_lists' );
		return $save;
	}
	
	function deleteJobfeeds($criteria) {
		$table = TABLE_PREFIX . 'cacaomail_mailing_lists';
		CI::model('core')->deleteData ( $table, $criteria );
		$cleanup_groups = CI::model('core')->groupsCleanup ( 'cacaomail_mailing_lists' );
	}
	
	function cron() {
		
		print "cron";
		$this->processJobFeeds ();
	
	}
	
	function processJobFeedsGetPages() {
		//print 'asdsadas';
		header ( "Content-type: text/plain" );
		print 'processJobFeedsGetPages';
		require_once ('Zend/Date.php');
		require_once ('Zend/Http/Client.php');
		require_once ('Zend/Http/Response.php');
		require_once ('Zend/Validate.php');
		require_once ('Zend/Validate/EmailAddress.php');
		
		$table = TABLE_PREFIX . 'cacaomail_mails_to_send';
		$q = "
		select * from $table where 
		for_download = 1 
		
		order by RAND() DESC limit 30
		";
		//	$q = "select * from $table where 	feed_id=27 ";
		

		//print $q;
		//exit;
		$query = CI::db()->query ( $q );
		$links = $query->result_array ();
		
		foreach ( $links as $link ) {
			$to_save = array ( );
			$to_save ['id'] = $link ['id'];
			$client = new Zend_Http_Client ( );
			//var_dump($link ["job_link"]);
			

			$link ["job_link"] = str_ireplace ( ' ', '', $link ["job_link"] );
			$link ["job_link"] = stripslashes ( $link ["job_link"] );
			
			//	var_dump($link ["job_link"]);
			

			$client->setUri ( $link ["job_link"] );
			print "\n\n Getting:  {$link["id"]}  {$link["job_link"]} \n  ";
			$client->setConfig ( array ('maxredirects' => 300, 'useragent' => 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.4) Gecko/20060612 Firefox/1.5.0.4 Flock/0.7.0.17.1', 'timeout' => 30 ) );
			//$redir_irl = $client->getUri();
			$response = $client->request ();
			
			$the_actual_url = $client->getUri ( true );
			$table = TABLE_PREFIX . 'cacaomail_mails_to_send';
			if (md5 ( $link ["job_link"] ) != md5 ( $the_actual_url )) {
				$the_actual_url = addslashes ( $the_actual_url );
				$q = " UPDATE  $table set job_link='$the_actual_url' where id={$link['id']} ";
				$query = CI::db()->query ( $q );
				$to_save ['job_link'] = $the_actual_url;
			}
			
			//var_dump($client->getUri(true));
			//exit;
			

			$ctype = $response->getHeader ( 'Content-type' );
			if (is_array ( $ctype ))
				$ctype = $ctype [0];
			
			$body = $response->getBody ();
			$body = str_ireplace ( '@recruitireland.com', ' ', $body );
			$body = str_ireplace ( '@totaljobsgroup.com', ' ', $body );
			$body = str_ireplace ( '@guardianunlimited.co.uk', ' ', $body );
			$body = str_ireplace ( '@sempo.org', ' ', $body );
			$body = str_ireplace ( '@import', ' ', $body );
			$body = str_ireplace ( '<img title="@" alt="@" src="../img/at.gif"/>', '@', $body );
			
			
			
			//if ($ctype == 'text/html' || $ctype == 'text/xml') {
			$email = CI::model('core')->extractEmailsFromString ( $body );
			if ($email [0] == '') {
				$body = html_entity_decode ( $body );
				$body = str_ireplace ( '@recruitireland.com', ' ', $body );
				$body = str_ireplace ( '@totaljobsgroup.com', ' ', $body );
				$body = str_ireplace ( '@sempo.org', ' ', $body );
				$body = str_ireplace ( '@import', ' ', $body );
				$body = str_ireplace ( '<img title="@" alt="@" src="../img/at.gif"/>', '@', $body );
				$email = CI::model('core')->extractEmailsFromString ( $body );
			}
			$email_to_save = false;
			if (! empty ( $email )) {
				foreach ( $email as $eml_to_check ) {
					
					$validator = new Zend_Validate_EmailAddress ( );
					if ($validator->isValid ( $eml_to_check )) {
						$email_to_save = $eml_to_check;
						$to_save ['is_active'] = '1';
					} else {
					
					}
				
				}
				
			//print $email_to_save;
			

			} else {
				$email_to_save = false;
			}
			$to_save ['job_email'] = $email_to_save;
			$body = htmlentities ( $body );
			//}
			

			print $to_save ['job_email'] . "\n";
			if (strval ( $to_save ['job_email'] ) != '') {
				$chexcs = $this->checkIfEmailExits ( $to_save ['job_email'] );
				if ($chexcs == false) {
					print "Saved! \n\n";
					$to_save ['for_download'] = '0';
					$to_save ['job_src'] = $body;
					//var_dump($to_save);
					//exit;
					$table = TABLE_PREFIX . 'cacaomail_mails_to_send';
					$save = CI::model('core')->saveData ( $table, $to_save );
				} else {
					$table = TABLE_PREFIX . 'cacaomail_mails_to_send';
					$q = "update  $table set is_active=0,for_download=0    where job_email='{$to_save ['job_email']}' ";
					//print $q;
					$query = CI::db()->query ( $q );
					//$table2 = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
					//$q = "update $table2 where mail_id={$to_save['id']} ";
					//$query = CI::db()->query ( $q );
					print "Valid email {$to_save ['job_email']}. Not saved! Cleaned up id: {$to_save['id']} \n\n";
				}
			} else {
				$table = TABLE_PREFIX . 'cacaomail_mails_to_send';
				$q = "update  $table set is_active=0,for_download=0   where id={$to_save['id']} ";
				//print $q;
				$query = CI::db()->query ( $q );
				//$table2 = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
				//$q = "delete from $table2 where mail_id={$to_save['id']} ";
				//$query = CI::db()->query ( $q );
				print "Email not found at all ! Cleaned up id: {$to_save['id']} \n\n";
			}
			//echo $body;
		//exit;
		}
		
	//var_dump($links);
	

	}
	
	function processJobFeeds() {
		require_once ('Zend/Date.php');
		require_once ('Zend/Validate.php');
		require_once ('Zend/Validate/EmailAddress.php');
		$date = new Zend_Date ( );
		
		// changes $date by adding 12 hours
		$date->sub ( '0', Zend_Date::HOUR );
		// use magic __toString() method to call Zend_Date's toString()
		$past = $date->toValue ();
		$past = date ( "Y-m-d H:i:s", $past );
		//echo "Date via toString() = ", $past , "\n";
		

		//header("Content-type: text/plain");
		header ( "Cache-Control: no-store, no-cache" );
		$table = TABLE_PREFIX . 'cacaomail_mailing_lists';
		//$criteria = array();
		//$criteria['is_active'] = 1;
		//$criteria['last_read_on <'] = $past;
		//'name !=', $name
		

		//$feeds = CI::model('core')->getData($table,$criteria );
		

		/*	$table_test = TABLE_PREFIX . 'cacaomail_mails_to_send';
		$q_test = " select * from $table_test where id=24 ";
		$q_test = CI::db()->query ( $q_test );
		$q_test = $q_test->row_array ();
		var_dump ( $q_test );
		$email = CI::model('core')->extractEmailsFromString ( html_entity_decode ( $q_test ['job_description'] ) );
		var_dump ( $email );
		exit ();*/
		
		$q = "
		select * from $table where 
		is_active=1 
		and (last_read_on < '$past' 
		or last_read_on='' 
		or last_read_on is null)
		
		order by last_read_on ASC
		limit 3
		";
		//print $q;
		//exit;
		$query = CI::db()->query ( $q );
		$feeds = $query->result_array ();
		
		foreach ( $feeds as $feed ) {
			if (stristr ( $feed ['feed_url'], 'http' ) == true) {
				require_once ('Zend/Feed.php');
				require_once ('Zend/Feed/Rss.php');
				
				print "Processing {$feed['feed_title']}\n";
				$channel = new Zend_Feed_Rss ( $feed ['feed_url'] );
				$to_save = array ( );
				$to_save ['feed_id'] = $feed ['id'];
				foreach ( $channel as $item ) {
					$to_save ['job_title'] = html_entity_decode ( $item->title () );
					$to_save ['job_title'] = str_ireplace ( '"', ' ', $to_save ['job_title'] );
					$to_save ['job_title'] = str_ireplace ( "'", ' ', $to_save ['job_title'] );
					
					$to_save ['job_link'] = $item->link ();
					$to_save ['job_description'] = ($item->description ());
					$to_save ['job_hash'] = md5 ( $item->link () );
					//$to_save['job_pub_date'] = date("Y-m-d H:i:s", $item->pubDate ());
					

					$date = date ( "Y-m-d H:i:s" );
					$to_save ['job_pub_date'] = $date;
					
					//search for email
					$src = html_entity_decode ( $item->description () );
					$email = CI::model('core')->extractEmailsFromString ( $src );
					//	$email = parseTextForEmail ( $src );
					

					if (! empty ( $email )) {
						$email = $email [0];
						$to_save ['for_download'] = 0;
						$to_save ['is_active'] = 1;
						$to_save ['job_email'] = $email;
					} else {
						$email = false;
						$to_save ['for_download'] = 1;
						$to_save ['job_email'] = false;
					}
					
					print $to_save ['job_email'] . "\n";
					
					//check
					$chexcs = $this->checkIfEmailExits ( $to_save ['job_email'] );
					if ($chexcs == false) {
						$table2 = TABLE_PREFIX . 'cacaomail_mails_to_send';
						$criteria = false;
						$criteria ['job_hash'] = $to_save ['job_hash'];
						//$check = CI::model('core')->getData ( $table2, $criteria, $limit = 1, $offset = false, $return_type = 'row' );
						// var_dump($check);
						$q = " select * from  $table2 where job_hash='{$to_save ['job_hash']}'  ";
						$query = CI::db()->query ( $q );
						$check = $query->row_array ();
						
						if (empty ( $check )) {
							//var_dump ( $to_save );
							print 'Saving link ' .addslashes($to_save ['job_link']). "\n";
							$to_save ['job_priority'] = 0;
							$save = CI::model('core')->saveData ( $table2, $to_save );
						} else {
							print 'This link is downloaded!' . "\n";
						}
						
						
						
					} else {
						print 'This email exists. Skip save!' . "\n\n";
					}
				
				}
			}
			$table = TABLE_PREFIX . 'cacaomail_mailing_lists';
			$to_save = array ( );
			$to_save ['id'] = $feed ['id'];
			$to_save ['last_read_on'] = date ( "Y-m-d H:i:s" );
			$save = CI::model('core')->saveData ( $table, $to_save );
		}
	
	}
	
	function getMailCampaigns($criteria = false, $limit = false, $offset = false) {
		$table = TABLE_PREFIX . 'cacaomail_mail_campaigns';
		$query = CI::model('core')->getData ( $table, $criteria, $limit, $offset );
		//var_dump($query);
		return $query;
	}
	
	function saveMailCampaigns($criteria) {
		$table = TABLE_PREFIX . 'cacaomail_mail_campaigns';
		$criteria = $this->input->xss_clean ( $criteria );
		
		if (! empty ( $criteria ['mailaccounts_groups_ids'] )) {
			$criteria ['mailaccounts_groups_ids'] = join ( ',', $criteria ['mailaccounts_groups_ids'] );
		} else {
			$criteria ['mailaccounts_groups_ids'] = 0;
		}
		if (! empty ( $criteria ['mailists_groups_ids'] )) {
			$criteria ['mailists_groups_ids'] = join ( ',', $criteria ['mailists_groups_ids'] );
		} else {
			$criteria ['mailists_groups_ids'] = 0;
		}
		
		//	var_dump($criteria);
		//exit;
		

		$save = CI::model('core')->saveData ( $table, $criteria );
		return $save;
	}
	
	function deleteMailCampaigns($criteria) {
		$table = TABLE_PREFIX . 'cacaomail_mail_campaigns';
		CI::model('core')->deleteData ( $table, $criteria );
		$cleanup_groups = CI::model('core')->groupsCleanup ( 'cacaomail_mail_campaigns' );
	}
	
	function checkIfEmailExits($email) {
		if ($email == '') {
			return false;
		}
		//$email = 'naizchun@gmail.com';
		$email = CI::model('core')->extractEmailsFromString ( $email );
		$email = $email [0];
		//var_dump($email);
		//exit;
		

		if ($email != '') {
			$table_upd2 = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
			$q = "
			
			SELECT mail_id , job_email , count(*) as qty  from  $table_upd2

		  where 
  (job_email   LIKE   '%{$email}%'
  or
   job_email   =   '{$email}'
   ) 
   group by job_email
			";
			$query = CI::db()->query ( $q );
			$query = $query->row_array ();
			if ((intval ( $query ['qty'] ) > 0)) {
				print "Email found in the log \n";
				return $query;
			} else {
				print "Email NOT found in the log \n";
			}
		
		}
		
		//careers@telligent.com
		$fake ['id'] = 1;
		$fake ['job_email'] = 'peter@ooyes.net';
		$fake ['qty'] = '1000';
		
		if (stristr ( $email, 'telligent.com' ) == true) {
			return $fake;
		}
		if (stristr ( $email, 'luma-pictures.com' ) == true) {
			return $fake;
		}
		
		$table_upd = TABLE_PREFIX . 'cacaomail_mails_to_send';
		$table_upd2 = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
		$q = "  SELECT id,job_email, count(*) as qty  from  $table_upd 

		  where 
  (job_email   LIKE   '%$email%'
  or
   job_email   =   '$email'
   ) 
    group by job_email

		
		";
		//print $q;
		$query = CI::db()->query ( $q );
		$query = $query->row_array ();
		$query ['qty'] = intval ( $query ['qty'] );
		//var_dump($query);
		

		if ($query ['qty'] != 0) {
			
			//var_dump($query);
			print "Email found in the haystack \n";
			//exit;
			return $query;
		} else {
			//	exit;
			print "Email NOT found in the haystack \n";
			return false;
		}
	}
	
	function doWorkLoop() {
		for($i = 1; $i <= 10; $i ++) {
			
			$this->doWork();
			print  "\n\n" . $i. " ";
		}
	}
	
	function doWork() {
		//return false;
		//exit ();
		$write_to_log = false;
		$table = TABLE_PREFIX . 'cacaomail_mail_campaigns';
		require_once ('Zend/Date.php');
		require_once ('Zend/Validate.php');
		require_once ('Zend/Validate/EmailAddress.php');
		
		require_once "Swift/Swift.php";
		require_once "Swift/Swift/Connection/SMTP.php";
		require_once "Swift/Swift/Authenticator/LOGIN.php";
		require_once "Swift/Swift/Plugin/AntiFlood.php";
		$mailer_files = BASEPATHSTATIC . 'mailer/';
		
		//for($i = 1; $i <= 10; $i ++) {
			//print $i;
			$write_to_log = false;
			
			$now = date ( "Y-m-d H:i:s" );
			
			$criteria = array ( );
			$criteria ['is_active'] = 1;
			$criteria ['campaign_start_date <'] = $now;
			$criteria ['campaign_end_date >'] = $now;
			//'name !=', $name
			//$limit = false, $offset = false, $return_type = false, $orderby = false
			$campaigns = CI::model('core')->getData ( $table, $criteria, $limit = false, $offset = false, $return_type = false, $orderby = array ('campaign_priority', 'DESC' ) );
			
			foreach ( $campaigns as $item ) {
				//var_dump($item);
				$write_to_log = false;
				$write_to_log ['campaign_id'] = $item ['id'];
				$campaign = $item;
				
				$send_to_those_mailing_lists = array ( );
				if (intval ( $item ['mailists_single_id'] ) != 0) {
					$send_to_those_mailing_lists [] = intval ( $item ['mailists_single_id'] );
				} else {
					$send_to_those_mailing_lists = explode ( ',', $item ['mailists_groups_ids'] );
					$ids = array ( );
					foreach ( $send_to_those_mailing_lists as $i ) {
						$data = array ( );
						$data ['group_id'] = $i;
						$data ['is_active'] = 1;
						$data = $this->getJobfeeds ( $data );
						if (! empty ( $data )) {
							foreach ( $data as $i2 ) {
								$ids [] = $i2 ['id'];
							}
						}
					}
					$send_to_those_mailing_lists = $ids;
				}
				
				$send_from_those_mail_accounts = array ( );
				if (intval ( $item ['mailaccounts_single_id'] ) != 0) {
					$send_from_those_mail_accounts [] = intval ( $item ['mailaccounts_single_id'] );
				} else {
					$send_from_those_mail_accounts = explode ( ',', $item ['mailaccounts_groups_ids'] );
					$ids = array ( );
					foreach ( $send_from_those_mail_accounts as $i ) {
						$data = array ( );
						$data ['group_id'] = $i;
						$data ['is_active'] = 1;
						$data = $this->getMailAccounts ( $data );
						if (! empty ( $data )) {
							foreach ( $data as $i2 ) {
								$ids [] = $i2 ['id'];
							}
						}
					}
					$send_from_those_mail_accounts = $ids;
				}
				
				$recipient_info = $this->getRandomRecipientForCampaign ( $campaign ['id'] );
				$account_info = $this->getRandomMailAccountForCampaign ( $campaign ['id'] );
				//var_dump ( $account_info );
				//exit;
				if (empty ( $recipient_info )) {
					print 'No recipients';
					return false;
				}
				//exit;
				

				//check for valid email
				$validator = new Zend_Validate_EmailAddress ( );
				if ($validator->isValid ( $recipient_info ['job_email'] )) {
					$write_to_log ['mail_id'] = $recipient_info ['id'];
					$write_to_log ['job_email'] = $recipient_info ['job_email'];
					$write_to_log ['feed_id'] = $recipient_info ['feed_id'];
					//$write_to_log['mail_id'] = $recipient_info['feed_id'];
				} else {
					$table_upd = TABLE_PREFIX . 'cacaomail_mails_to_send';
					$q = "  SELECT *   from  $table_upd where id={$recipient_info ['id']}";
					$query = CI::db()->query ( $q );
					$query = $query->row_array ();
					
					$email = CI::model('core')->extractEmailsFromString ( html_entity_decode ( $query ['job_description'] ) );
					$validator2 = new Zend_Validate_EmailAddress ( );
					if ($validator2->isValid ( $email [0] )) {
						$table_upd = TABLE_PREFIX . 'cacaomail_mails_to_send';
						$q = "Update $table_upd set job_email='{$email[0]}' where id={$recipient_info ['id']}";
						$query = CI::db()->query ( $q );
						print "Email fixed {$email[0]} to {$recipient_info ['id']}";
						return false;
					} else {
						$email = CI::model('core')->extractEmailsFromString ( html_entity_decode ( $query ['job_src'] ) );
						$validator3 = new Zend_Validate_EmailAddress ( );
						if ($validator3->isValid ( $email [0] )) {
							$table_upd = TABLE_PREFIX . 'cacaomail_mails_to_send';
							$q = "Update $table_upd set job_email='{$email[0]}' where id={$recipient_info ['id']}";
							$query = CI::db()->query ( $q );
							print "Email fixed 2 {$email[0]} to {$recipient_info ['id']}";
							return false;
						} else {
							$table_upd = TABLE_PREFIX . 'cacaomail_mails_to_send';
							$q = "Update $table_upd set is_active=0 where id={$recipient_info ['id']}";
							$query = CI::db()->query ( $q );
							print 'Invalid mail address';
							return false;
						}
					}
					
					$table_upd = TABLE_PREFIX . 'cacaomail_mails_to_send';
					$q = "Update $table_upd set is_active=0 where id={$recipient_info ['id']}";
					$query = CI::db()->query ( $q );
					print 'Invalid mail address';
					return false;
				}
				//END check for valid email
				

				$table = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
				//$q = "  SELECT count(*) as qty from  $table where mail_id={$write_to_log['mail_id']}  and campaign_id={$write_to_log['campaign_id']} ";
				$q = "  SELECT count(*) as qty from  $table where (mail_id={$write_to_log['mail_id']} or job_email='{$write_to_log['job_email']}' ) ";
				//print $q;
				$query = CI::db()->query ( $q );
				$query = $query->row_array ();
			$query ['qty']  =	intval ( $query ['qty'] );
				//var_dump ( intval ( $query ['qty'] ) );
				if (intval ( $query ['qty'] ) < 1) {
					//var_dump($send_to_those_mailing_lists);
					//var_dump($send_from_those_mail_accounts);
					shuffle ( $send_from_those_mail_accounts );
					$table2 = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
					//$send_from_those_mail_accounts = $send_from_those_mail_accounts[0];
					$account_settings = $account_info;
					
					//foreach ( $send_from_those_mail_accounts as $mail_account_id ) {
					

					$write_to_log ['account_id'] = $account_settings ['id'];
					
					$date = new Zend_Date ( );
					$date->sub ( '24', Zend_Date::HOUR );
					$past = $date->toValue ();
					$past = date ( "Y-m-d H:i:s", $past );
					$now = date ( "Y-m-d H:i:s" );
					$q = " SELECT count(*) as qty FROM $table2 where mailsent_date > '$past' and account_id = {$account_settings['id']} ";
					//print $q ;
					$query = CI::db()->query ( $q );
					$row = $query->row_array ();
					//var_dump($row);
					//exit;
					$qty = $row ['qty'];
					$qty = intval($qty);
					
					if (intval ( $qty ) > intval ( $account_settings ['limit_per_day'] )) {
						print "Mail account limit {$account_settings ['limit_per_day']} reached for: {$account_settings['your_email']} \n\n";
					} else {
						print "Sendind $qty of {$account_settings ['limit_per_day']} for: {$account_settings['your_email']} \n\n";
						
						$smtp = new Swift_Connection_SMTP ( $account_settings ['outgoing_mail_server'], Swift_Connection_SMTP::PORT_SECURE, Swift_Connection_SMTP::ENC_TLS );
						$smtp->setUsername ( $account_settings ['mail_username'] );
						$smtp->setPassword ( $account_settings ['mail_password'] );
						
						$swift = & new Swift ( $smtp );
						$swift->attachPlugin ( new Swift_Plugin_AntiFlood ( 100 ), "anti-flood" );
						
						//Create the message
						$message = & new Swift_Message ( );
						$message->setCharset ( "utf-8" );
						$subject = $campaign ['campaign_default_subject'];
						
						if (strval ( $recipient_info ['job_auto_subject'] ) != '') {
							$subject = strval ( $recipient_info ['job_auto_subject'] );
						}
						
						$message->headers->set ( "Subject", $subject );
						$message->headers->set ( "X-Mailer", 'Apple Mail (2.752.3)' );
						
						$the_description = false;
						$the_description = html_entity_decode ( $recipient_info ['job_description'] );
						$the_description_txt = strip_tags ( $the_description );
						//print $the_description_txt; 
						

						if (is_file ( $mailer_files . trim ( $campaign ['campaign_template_file'] ) ) == true) {
							$file_content = file_get_contents ( $mailer_files . $campaign ['campaign_template_file'] );
							
							$file_content = str_ireplace ( "{_NAME_}", $recipient_info ['job_name'], $file_content );
							$file_content = str_ireplace ( "{_EMAIL_}", $recipient_info ['job_email'], $file_content );
							$file_content = str_ireplace ( "{_LINK_}", $recipient_info ['job_link'], $file_content );
							+ $date = new Zend_Date ( $recipient_info ['job_pub_date'], Zend_Date::ISO_8601 );
							$past = $date->toValue ();
							
							$file_content = str_ireplace ( "{_PUBDATE_}", date ( 'l jS  F Y ', $past ), $file_content );
							$file_content = str_ireplace ( "{_SUBJECT_}", $subject, $file_content );
							
							$file_content = str_ireplace ( "{_PUBDAY_}", date ( 'l', $past ), $file_content );
							$file_content = str_ireplace ( "{_DESCRIPTION_}", $the_description, $file_content );
							//print $file_content;
							

							$message->attach ( new Swift_Message_Part ( $file_content, "text/html" ) );
						}
						
						if (is_file ( $mailer_files . trim ( $campaign ['campaign_template_file_plain'] ) ) == true) {
							$file_content = file_get_contents ( $mailer_files . $campaign ['campaign_template_file_plain'] );
							$file_content = str_ireplace ( "{_NAME_}", $recipient_info ['job_name'], $file_content );
							$file_content = str_ireplace ( "{_EMAIL_}", $recipient_info ['job_email'], $file_content );
							$file_content = str_ireplace ( "{_LINK_}", $recipient_info ['job_link'], $file_content );
							$date = new Zend_Date ( $recipient_info ['job_pub_date'], Zend_Date::ISO_8601 );
							$past = $date->toValue ();
							
							$file_content = str_ireplace ( "{_PUBDATE_}", date ( 'l jS  F Y ', $past ), $file_content );
							$file_content = str_ireplace ( "{_SUBJECT_}", $subject, $file_content );
							
							$file_content = str_ireplace ( "{_PUBDAY_}", date ( 'l', $past ), $file_content );
							$file_content = str_ireplace ( "{_DESCRIPTION_}", $the_description_txt, $file_content );
							//print $file_content;
							$message->attach ( new Swift_Message_Part ( $file_content ) );
						}
						
						if (strval ( $campaign ['campaign_attachments'] ) != '') {
							$att = explode ( ',', $campaign ['campaign_attachments'] );
							if (! empty ( $att )) {
								foreach ( $att as $a ) {
									if (is_file ( $mailer_files . trim ( $a ) ) == true) {
										//print $mailer_files.trim($a);
										$parts = explode ( '/', $a );
										$currentFile = $parts [count ( $parts ) - 1];
										$message->attach ( new Swift_Message_Attachment ( file_get_contents ( $mailer_files . trim ( $a ) ), $currentFile ) );
									}
								}
							
							}
						
						}
						//	$stream =& $message->build();
						

						//	exit ();
						$table2 = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
						$write_to_log ['mailsent_date'] = date ( "Y-m-d H:i:s" );
						CI::model('core')->saveData ( $table2, $write_to_log );
						
						$t = TABLE_PREFIX . 'cacaomail_mails_to_send';
						$q = " update $t set is_active=0 where job_email LIKE '{$recipient_info ['job_email']}'  ";
						print $q;
						$query = CI::db()->query ( $q );
						
						
						//
						//$message, new Swift_Address ( "peter@ooyes.net", $recipient_info ['job_name'] ),
						$sent = $swift->send ( 
 
$message, new Swift_Address ( $recipient_info ['job_email'], $recipient_info ['job_name'] ), 


new Swift_Address ( "{$account_settings['your_email']}", "{$account_settings['your_name']}" ) );
						
						echo "Sent to $sent recipients: {$recipient_info['job_email']}";
						$swift->disconnect ();
						
					//var_dump($write_to_log);					

					//exit ();
					//sleep ( 30 );
					

					}
					
				//}
				} else {
					print 'Already sent';
					//return false;
				}
			}
		
		//} //here end for loop 
	

	}
	
	function logSending($criteria) {
		$table2 = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
	}
	
	function getRandomMailAccountForCampaign($campaign_id) {
		if (intval ( $campaign_id ) == 0) {
			return $campaign_id;
		}
		$table = TABLE_PREFIX . 'cacaomail_mail_campaigns';
		$now = date ( "Y-m-d H:i:s" );
		
		$criteria = array ( );
		$criteria ['is_active'] = 1;
		$criteria ['id'] = $campaign_id;
		
		$criteria ['campaign_start_date <'] = $now;
		$criteria ['campaign_end_date >'] = $now;
		//'name !=', $name
		//$limit = false, $offset = false, $return_type = false, $orderby = false
		$campaigns = CI::model('core')->getData ( $table, $criteria, $limit = false, $offset = false, $return_type = false, $orderby = array ('campaign_priority', 'DESC' ) );
		$campaigns = $campaigns [0];
		if (empty ( $campaigns )) {
			return false;
		}
		
		$item = $campaigns;
		$send_from_those_mail_accounts = array ( );
		if (intval ( $item ['mailaccounts_single_id'] ) != 0) {
			$send_from_those_mail_accounts [] = intval ( $item ['mailaccounts_single_id'] );
		} else {
			$send_from_those_mail_accounts = explode ( ',', $item ['mailaccounts_groups_ids'] );
			$ids = array ( );
			foreach ( $send_from_those_mail_accounts as $i ) {
				$data = array ( );
				$data ['group_id'] = $i;
				$data ['is_active'] = 1;
				$data = $this->getMailAccounts ( $data );
				if (! empty ( $data )) {
					foreach ( $data as $i2 ) {
						$ids [] = $i2 ['id'];
					}
				}
			}
			$send_from_those_mail_accounts = $ids;
		}
		//var_dump($send_from_those_mail_accounts);
		

		shuffle ( $send_from_those_mail_accounts );
		$send_from_those_mail_accounts_ready = array ( );
		$table2 = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
		
		//$send_from_those_mail_accounts = $send_from_those_mail_accounts[0];
		

		foreach ( $send_from_those_mail_accounts as $mail_account_id ) {
			
			$write_to_log ['account_id'] = $mail_account_id;
			$qty = false;
			$account_settings = array ( );
			$account_settings ['id'] = $mail_account_id;
			$account_settings = $this->getMailAccounts ( $account_settings );
			$account_settings = $account_settings [0];
			$date = new Zend_Date ( );
			$date->sub ( '24', Zend_Date::HOUR );
			$past = $date->toValue ();
			$past = date ( "Y-m-d H:i:s", $past );
			$now = date ( "Y-m-d H:i:s" );
			$q = " SELECT count(*) as qty FROM $table2 where mailsent_date > '$past' and account_id = {$account_settings['id']} ";
			//print $q ;
			$query = CI::db()->query ( $q );
			$row = $query->row_array ();
			//var_dump($row);
			//exit;
			$qty = $row ['qty'];
			$account_settings ['sent_today'] = $qty;
			if (intval ( $qty ) > intval ( $account_settings ['limit_per_day'] )) {
			
			} else {
				$send_from_those_mail_accounts_ready [] = $account_settings;
			}
		
		}
		shuffle ( $send_from_those_mail_accounts_ready );
		return $send_from_those_mail_accounts_ready [0];
	
	}
	
	function getRandomRecipientForCampaign($campaign_id) {
		require_once ('Zend/Date.php');
		$campaign_info = array ( );
		$campaign_info ['id'] = $campaign_id;
		$campaign_info = $this->getMailCampaigns ( $campaign_info );
		$campaign_info = $campaign_info [0];
		
		//var_dump($campaign_info);
		//exit;
		$table_clean = TABLE_PREFIX . 'cacaomail_mails_to_send';
		$table_clean2 = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
		//cleanup
		$q = "SELECT  COUNT(*) as qty,  job_email 
		FROM  $table_clean where job_email !='0'  
		and is_active = 1
		group by job_email
	   	order by qty desc
	   	limit 3";
		//	print $q ; 
		$query = CI::db()->query ( $q );
		$query = $query->result_array ();
		if (! empty ( $query )) {
			foreach ( $query as $clean_item ) {
				if (intval ( $clean_item ['qty'] ) > 1) {
					$q = "select id, job_email from $table_clean where job_email='{$clean_item['job_email']}' order by id asc ";
					$q_clean = CI::db()->query ( $q );
					$q_clean = $q_clean->result_array ();
					$fruit = array_pop ( $q_clean );
					foreach ( $q_clean as $lets_clean ) {
						//print 'clean up ' . $lets_clean ['job_email'] . "\n";
						//$q = "delete from $table_clean  where id='{$lets_clean['id']}' ";
						//$q = CI::db()->query ( $q );
						print 'Set inactive ' . $lets_clean ['job_email'] . "\n";
						$q = "update $table_clean  set is_active=0 where job_email='{$clean_item['job_email']}'  ";
						//print $q;
						$q = CI::db()->query ( $q );
						//$q = "delete from $table_clean2  where mail_id='{$lets_clean['id']}' ";
					//$q = CI::db()->query ( $q );
					}
				
				}
			}
		}
		
		$table_clean = TABLE_PREFIX . 'cacaomail_mails_to_send';
		$q = " update $table_clean set is_active=0 where for_download=0 and (job_email='0' )    ";
		//print $q;
		//exit;
		$q = CI::db()->query ( $q );
		
		//more housekeeping 
		$table = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
		$table2 = TABLE_PREFIX . 'cacaomail_mails_to_send';
		$q = "SELECT  mail_id 
		FROM  $table where job_email is null  
		
	   	limit 300";
		//	print $q ; 
		$query = CI::db()->query ( $q );
		$query = $query->result_array ();
		//	var_dump($query);
		if (! empty ( $query )) {
			foreach ( $query as $clean_item ) {
				if (intval ( $clean_item ['mail_id'] ) != 0) {
					//print $clean_item ['mail_id'];
					$q = "select id, job_email from $table2 where id='{$clean_item ['mail_id']}' limit 1 ";
					//print $q;
					$q_clean = CI::db()->query ( $q );
					$q_clean = $q_clean->row_array ();
					//	var_dump($q_clean);
					if (! empty ( $q_clean )) {
						$q = "update $table set job_email='{$q_clean['job_email']}' where mail_id='{$clean_item ['mail_id']}'";
						//	print $q;
						$q_clean = CI::db()->query ( $q );
					}
					//var_dump($q_clean);
				

				}
			}
		}
		//exit;
		

		$table = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
		$send_to_those_mailing_lists = array ( );
		if (intval ( $campaign_info ['mailists_single_id'] ) != 0) {
			$send_to_those_mailing_lists [] = intval ( $campaign_info ['mailists_single_id'] );
		} else {
			$send_to_those_mailing_lists = explode ( ',', $campaign_info ['mailists_groups_ids'] );
			$ids = array ( );
			//var_dump($send_to_those_mailing_lists);
			//exit;
			shuffle ( $send_to_those_mailing_lists );
			foreach ( $send_to_those_mailing_lists as $i ) {
				$data = array ( );
				$data ['group_id'] = $i;
				$data ['is_active'] = 1;
				$data = $this->getJobfeeds ( $data );
				//var_dump($data);
				if (! empty ( $data )) {
					foreach ( $data as $i2 ) {
						$ids [] = $i2 ['id'];
					}
				}
			}
			$send_to_those_mailing_lists = $ids;
			shuffle ( $send_to_those_mailing_lists );
		}
		//var_dump($send_to_those_mailing_lists);
		//exit;
		

		if (! empty ( $send_to_those_mailing_lists )) {
			$mailing_list_groups_query = ' feed_id=0  ';
			foreach ( $send_to_those_mailing_lists as $item ) {
				$mailing_list_groups_query = $mailing_list_groups_query . "  or feed_id=$item   ";
			}
			$mailing_list_groups_query = "  ($mailing_list_groups_query)  and   ";
		} else {
			return false;
		}
		
		//exit($mailing_list_groups_query);
		

		$date = new Zend_Date ( );
		$date->sub ( '24', Zend_Date::HOUR ); //?
		$past = $date->toValue ();
		$past = date ( "Y-m-d H:i:s", $past );
		$now = date ( "Y-m-d H:i:s" );
		$q = " SELECT mail_id FROM $table where mailsent_date > '{$campaign_info['campaign_start_date']}' and mailsent_date < '{$campaign_info['campaign_end_date']}' and campaign_id={$campaign_info['id']} ";
		$query = CI::db()->query ( $q );
		$query = $query->result_array ();
		$mail_ids_to_exclude = array ( );
		$mails_to_exclude = array ( );
		if (! empty ( $query )) {
			foreach ( $query as $item ) {
				if (intval ( $item ['mail_id'] ) != 0) {
					$mail_ids_to_exclude [] = intval ( $item ['mail_id'] );
					$table2 = TABLE_PREFIX . 'cacaomail_mails_to_send';
					$q = " SELECT job_email FROM $table2 where id={$item ['mail_id']} limit 1  ";
					$q = CI::db()->query ( $q );
					$q = $q->row_array ();
					$mails_to_exclude [] = $q ['job_email'];
				}
			}
			$mails_to_exclude = array_unique ( $mails_to_exclude );
			$mail_ids_to_exclude = array_unique ( $mail_ids_to_exclude );
		}
		//var_dump($mail_ids_to_exclude);
		if (! empty ( $mail_ids_to_exclude )) {
			$exclude_q = '   id!=0   ';
			$exclude_mails_q = '   job_email is not null  ';
			foreach ( $mail_ids_to_exclude as $i ) {
				$exclude_q = $exclude_q . "  AND id<>$i  ";
			}
			
			foreach ( $mails_to_exclude as $i ) {
				$exclude_mails_q = $exclude_mails_q . "  AND job_email not like '$i'  ";
			}
			
			//print $exclude_q;
			$exclude_q = " ($exclude_q)  and ";
			$exclude_mails_q = " ($exclude_mails_q)  and ";
		
		}
		
		//print $exclude_q;
		$repeat_q = false;
		if (intval ( $campaign_info ['campaign_repeat_days'] ) != 0) {
			$sub = intval ( $campaign_info ['campaign_repeat_days'] ) * 24;
			//print $sub;
			$date = new Zend_Date ( );
			$date->sub ( $sub, Zend_Date::HOUR ); //?
			$future = $date->toValue ();
			$future = date ( "Y-m-d H:i:s", $future );
			$repeat_q = " AND ( job_mailsent_date<'$future' or job_mailsent_date is null) ";
		}
		
		$table = TABLE_PREFIX . 'cacaomail_mails_to_send';
		$table2 = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
		$q = " SELECT * from   $table  where
		$mailing_list_groups_query 
	
	$exclude_q
	$exclude_mails_q
		is_active=1 and 
		
		
		 job_email NOT IN (SELECT job_email FROM
       $table2 where job_email is not null group by job_email) and
		
		 id NOT IN (SELECT mail_id FROM
        $table2 where mail_id is not null
         group by mail_id) and
		
		
		(for_download=0 or for_download IS NULL ) and 
		job_pub_date>='{$campaign_info['campaign_start_date']}' and
		(  job_email IS NOT NULL and
       job_email <> '0' and job_email <> '@' )
		$repeat_q

		group by job_email
		ORDER BY rand() DESC
		limit 1
		";
		//print $q;
		//$q = " SELECT * from   $table  where id=587";
		//exit ();
		

		$query = CI::db()->query ( $q );
		$query = $query->row_array ();
		if (empty ( $query )) {
			return false;
		}
		//print $query ['job_email'];
		//$check = $this->checkIfEmailExits($query ['job_email']);
		//var_dump($check);
		//exit;
		

		//var_dump($query);
		$query ['job_auto_subject'] = $query ['job_title'];
		
		if ($query ['job_title'] != '') {
			
			$temp = false;
			if (stristr ( $query ['job_title'], 'is looking for' ) == true) {
				$mystring = $query ['job_title'];
				$findme = 'looking for';
				$pos = strpos ( $mystring, $findme );
				$temp = substr ( $query ['job_title'], $pos + strlen ( $findme ), strlen ( $query ['job_title'] ) );
				$query ['job_auto_subject'] = $temp;
			}
			
			if (stristr ( $query ['job_title'], '-' ) == true) {
				$mystring = $query ['job_title'];
				$findme = '-';
				$pos = strpos ( $mystring, $findme );
				$temp = substr ( $query ['job_title'], 0, ($pos) );
				$query ['job_auto_subject'] = $temp;
			}
			
			if (stristr ( $query ['job_title'], ':' ) == true) {
				$mystring = $query ['job_title'];
				$findme = ':';
				$pos = strpos ( $mystring, $findme );
				$temp = substr ( $query ['job_title'], $pos + strlen ( $findme ), strlen ( $query ['job_title'] ) );
				$query ['job_auto_subject'] = $temp;
			}
		
		}
		//var_dump ( $query ['job_email'] );
		//exit($query['job_auto_subject']);
		return $query;
		//
	//var_dump($send_to_those_mailing_lists);
	//exit;
	}
	
	function houseKeepStats() {
		$table = TABLE_PREFIX . 'cacaomail_mails_to_send';
		$table2 = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
		
		$q = " select id from $table where id NOT IN (SELECT mail_id FROM
        $table2 where mail_id is not null
         group by mail_id)  ";
		
		$query = CI::db()->query ( $q );
		$query = $query->result_array ();
		var_dump ( $query );
		foreach ( $query as $i ) {
			$q = " delete from $table where id={$i['id']}";
			$query = CI::db()->query ( $q );
		}
		
		return true;
	}
	
	function fakeSend() {
		$table = TABLE_PREFIX . 'cacaomail_mails_to_send';
		$table2 = TABLE_PREFIX . 'cacaomail_mails_to_send_log';
		
		$q = " select * from $table where id NOT IN (SELECT mail_id FROM
        $table2 where mail_id is not null
         group by mail_id)  
         
         and for_download = 0 and is_active = 1
         ";
		
		$query = CI::db()->query ( $q );
		$query = $query->result_array ();
		var_dump ( $query );
		foreach ( $query as $i ) {
		
		}
		
		return true;
	}

}

?>