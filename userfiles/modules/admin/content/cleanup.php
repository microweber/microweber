<form method="post"><input type="hidden" name="step1" value="1" /> <input
    type="submit" value="cleanup custom fields"></form>

<?php

print "<hr>";
?>
<?php

if ($_POST) {
	print '<pre>';
	global $cms_db_tables;
	if ($_POST ['step1']) {
		
		$table_custom_fields = $cms_db_tables ['table_custom_fields'];
		
		$q = "select id, to_table, to_table_id from $table_custom_fields ";
		
		$q = CI::model ( 'core' )->dbQuery ( $q, md5 ( $q ), 'custom_fields' );
		
		if (! empty ( $q )) {
			
			foreach ( $q as $q1 ) {
				
				$q_check = CI::model ( 'core' )->getById ( $q1 ['to_table'], $id = $q1 ['to_table_id'] );
				if (! empty ( $q_check )) {
					//	print "CF found {$q1 ['to_table']} - {$q1 ['to_table_id']} \n\r <br>";
				} else {
					print "Custom field NOT FOUND! {$q1 ['to_table']} - {$q1 ['to_table_id']} \n\r <br>";
					
					$clean = CI::model ( 'core' )->deleteDataById ( $table_custom_fields, $q1 ['id'] );
				
				}
				//p ( $q_check );
			}
		}
		
		$table_custom_fields = $cms_db_tables ['table_media'];
		
		$q = "select id, to_table, to_table_id from $table_custom_fields ";
		
		$q = CI::model ( 'core' )->dbQuery ( $q, md5 ( $q ), 'custom_fields' );
		
		if (! empty ( $q )) {
			
			foreach ( $q as $q1 ) {
				
				$q_check = CI::model ( 'core' )->getById ( $q1 ['to_table'], $id = $q1 ['to_table_id'] );
				if (! empty ( $q_check )) {
					//	print "Media found {$q1 ['to_table']} - {$q1 ['to_table_id']} \n\r <br>";
				} else {
					print "Media NOT FOUND! {$q1 ['to_table']} - {$q1 ['to_table_id']} \n\r <br>";
					
					$clean = CI::model ( 'core' )->deleteDataById ( $table_custom_fields, $q1 ['id'] );
				
				}
				//p ( $q_check );
			}
		}
		
		$table_custom_fields = $cms_db_tables ['table_comments'];
		
		$q = "select id, to_table, to_table_id from $table_custom_fields ";
		
		$q = CI::model ( 'core' )->dbQuery ( $q, md5 ( $q ), 'custom_fields' );
		
		if (! empty ( $q )) {
			
			foreach ( $q as $q1 ) {
				
				$q_check = CI::model ( 'core' )->getById ( $q1 ['to_table'], $id = $q1 ['to_table_id'] );
				if (! empty ( $q_check )) {
					//print "comments found {$q1 ['to_table']} - {$q1 ['to_table_id']} \n\r <br>";
				} else {
					print "comments NOT FOUND! {$q1 ['to_table']} - {$q1 ['to_table_id']} \n\r <br>";
					
					$clean = CI::model ( 'core' )->deleteDataById ( $table_custom_fields, $q1 ['id'] );
				
				}
				//p ( $q_check );
			}
		}
		
		$table_custom_fields = $cms_db_tables ['table_taxonomy_items'];
		$table_taxonomy = $cms_db_tables ['table_taxonomy'];
		
		$q = "select id, to_table, to_table_id, parent_id from $table_custom_fields ";
		
		$q = CI::model ( 'core' )->dbQuery ( $q, md5 ( $q ), 'custom_fields' );
		
		if (! empty ( $q )) {
			
			foreach ( $q as $q1 ) {
				
				$q_check = CI::model ( 'core' )->getById ( $q1 ['to_table'], $id = $q1 ['to_table_id'] );
				if (! empty ( $q_check )) {
					//print "table_taxonomy_items found {$q1 ['to_table']} - {$q1 ['to_table_id']} \n\r <br>";
				} else {
					print "table_taxonomy_items NOT FOUND! {$q1 ['to_table']} - {$q1 ['to_table_id']} \n\r <br>";
					
					$clean = CI::model ( 'core' )->deleteDataById ( $table_custom_fields, $q1 ['id'] );
				
				}
				
				$q_check = CI::model ( 'core' )->getById ( $table_taxonomy, $id = $q1 ['parent_id'] );
				if (! empty ( $q_check )) {
					//print "table_taxonomy_items found {$q1 ['to_table']} - {$q1 ['to_table_id']} \n\r <br>";
				} else {
					print "table_taxonomy NOT FOUND! $table_taxonomy id:  $id  \n\r <br>";
					
					$clean = CI::model ( 'core' )->deleteDataById ( $table_custom_fields, $q1 ['id'] );
				
				}
				//p ( $q_check );
			}
		}
	
	}
	
	print '</pre>';
	$clean = CI::model ( 'core' )->cacheDeleteAll ();
	//var_dump ( $k );
} else {

}
?>
