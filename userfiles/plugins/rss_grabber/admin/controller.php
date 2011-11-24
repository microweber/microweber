<?php $this->load->vars ( $this->template );
$rss_grabber = new rss_grabber_model ( );
$primarycontent = false;
$this->template ['rss_grabber'] = $rss_grabber;
$test = $rss_grabber->test ();
$this->template ['plugintest'] = $test;
$this->load->vars ( $this->template );

$action = $this->uri->segment ( 5 );

if (! $action) {
	$action = 'index';
}

switch ( $action) {
	case 'edit' :
		$this->template ['plugin_action'] = 'add';
		
		if ($_POST) {
			$rss_grabber->saveFeed ( $_POST );
			//exit(THIS_PLUGIN_URL.'index');
			$go = THIS_PLUGIN_URL . 'index';
			header ( "Location: $go" );
			exit ();
			//redirect (  );
		

		}
		
		$id = $this->core_model->getParamFromURL ( 'id' );
		if ($id != 0) {
			$data = array ( );
			$data ['id'] = $id;
			$form_values = $rss_grabber->getFeeds ( $data );
			$form_values = $form_values [0];
			//$data ['content_type'] = 'page';
			//$data = $this->content_model->getContent ( $data );
			//var_dump($data);
			$this->template ['form_values'] = $form_values;
			$this->load->vars ( $this->template );
		}
		
		$this->load->vars ( $this->template );
		$primarycontent = $this->load->file ( THIS_PLUGIN_DIRNAME_ADMIN . 'views/nav.php', true, true );
		$primarycontent .= $this->load->file ( THIS_PLUGIN_DIRNAME_ADMIN . 'views/edit.php', true, true );
	break;
	
	case 'delete' :
		$url = $_POST ['id'];
		ob_clean ();
		$rss_grabber->deleteFeedById ( $url );
		exit ();
	break;
	
	case 'rss_get_title' :
		//rssGetTitleFromFeedUrl
		//$url = $this->core_model->getParamFromURL ( 'url' );.
		$url = $_POST ['url'];
		$title = $rss_grabber->rssGetTitleFromFeedUrl ( $url );
		ob_clean ();
		exit ( addslashes ( $title ) );
	
	break;
	
	
	
	case 'process_feed_by_id_now' :
		$id = $this->core_model->getParamFromURL ( 'id' );
		$data = array();
		$data['id'] = $id;
		$form_values = $rss_grabber->getFeeds ($data, false);
		$this->template ['form_values'] = $form_values;
		$feeds = $form_values;
		$this->template ['feeds'] = $form_values;
		
		
		$this->template ['plugin_action'] = 'process_feeds_now';
		
		$this->load->vars ( $this->template );
		$primarycontent = $this->load->file ( THIS_PLUGIN_DIRNAME_ADMIN . 'views/nav.php', true, true );
		
		$primarycontent .= $this->load->file ( THIS_PLUGIN_DIRNAME_ADMIN . 'views/process_feeds_now.php', true, true );
	break;
	
	
	case 'process_feeds_now' :
		
		
		$form_values = $rss_grabber->getFeeds (false, true);
		$this->template ['form_values'] = $form_values;
		$feeds = $form_values;
		$this->template ['feeds'] = $form_values;
		
		
		$this->template ['plugin_action'] = 'process_feeds_now';
		
		$this->load->vars ( $this->template );
		$primarycontent = $this->load->file ( THIS_PLUGIN_DIRNAME_ADMIN . 'views/nav.php', true, true );
		
		$primarycontent .= $this->load->file ( THIS_PLUGIN_DIRNAME_ADMIN . 'views/process_feeds_now.php', true, true );
	break;
	
	case 'index' :
	default :
		
		$form_values = $rss_grabber->getFeeds ();
		$this->template ['form_values'] = $form_values;
		
		$this->template ['plugin_action'] = 'index';
		
		$this->load->vars ( $this->template );
		$primarycontent = $this->load->file ( THIS_PLUGIN_DIRNAME_ADMIN . 'views/nav.php', true, true );
		
		$primarycontent .= $this->load->file ( THIS_PLUGIN_DIRNAME_ADMIN . 'views/index.php', true, true );
	break;
}

?>