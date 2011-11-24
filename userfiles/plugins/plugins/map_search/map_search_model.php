<?php class Map_search_model extends Model {
	public $db_tables;
	
	function __construct() {
		parent::Model ();
		
		$plugin_db_tables = array ( );
		$plugin_db_tables ['table_geodata'] = TABLE_PREFIX . 'geodata';
		
		$this->db_tables = $plugin_db_tables;
	
	}
	
	function test() {
		
	//$posts = $this->content_model->contentGetLatestPosts ( array (0, 4 ), $categories = false, $featured = true );
	//var_dump ( $posts );
	}
	
	function getContent() {
		$plugin_db_tables = $this->db_tables;
		$table = $plugin_db_tables ['table_geodata'];
		//var_dump ( $plugin_db_tables );
		

		$data = array ( );
		$data ['to_table'] = 'table_content';
		$data = $this->core_model->getDbData ( $table = $table, $criteria = $data, $limit = false, $offset = false, $orderby = false, $cache_group = 'content', $debug = false, $ids = false, $count_only = false );
		if (empty ( $data )) {
			return false;
		} else {
			$content_to_return = array ( );
			foreach ( $data as $content_item ) {
				$content = array ( );
				$content ['id'] = $content_item ['to_table_id'];
				$content ['content_type'] = 'post';
				$content = $this->content_model->getContent ( $content );
				$content = $content [0];
				if (! empty ( $content )) {
					$content_to_return [] = $content;
				}
			}
			return $content_to_return;
		}
		
	//var_dump ( $data );
	

	//$posts = $this->content_model->contentGetLatestPosts ( array (0, 4 ), $categories = false, $featured = true );
	//var_dump ( $posts );
	

	}

}
