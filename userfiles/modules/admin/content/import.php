<form method="post"><input type="hidden" name="step1" value="1" /> <input
    type="submit" value="step1 - categories"></form>
<form method="post"><input type="hidden" name="step1_1" value="1" /> <input
    type="submit" value="step1_1 - categories"></form>
<form method="post"><input type="hidden" name="step2" value="1" /> <input
    type="submit" value="step2 - posts"></form>
<form method="post"><input type="hidden" name="step3" value="1" /> <input
    type="submit" value="step3 - custom fields"></form>
<?php

print "<hr>";
?>
<?php
global $cms_db_tables;
if ($_POST) {
	
	$dir = dirname ( __FILE__ ) . '/import/';
	
	if ($_POST ['step1']) {
		$csvarray = array ();
		// add more if you want
		$filezz = ($dir . 'temp/category.csv');
		# Open the File.
		if (($handle = fopen ( $filezz, "r" )) !== FALSE) {
			# Set the parent multidimensional array key to 0.
			$nn = 0;
			while ( ($data = fgetcsv ( $handle, 1000, "," )) !== FALSE ) {
				# Count the total keys in the row.
				$c = count ( $data );
				# Populate the multidimensional array.
				for($x = 0; $x < $c; $x ++) {
					$csvarray [$nn] [$x] = $data [$x];
				}
				$nn ++;
			}
			# Close the File.
			fclose ( $handle );
		}
		# Print the contents of the multidimensional array.
		

		$categories = ($csvarray);
		
		foreach ( $categories as $category ) {
			
			/*	
		
		
		
		[0] => Category ID
            [1] => Date added
            [2] => Description
            [3] => Enabled
            [4] => Image
            [5] => Last modified
            [6] => Meta tag description
            [7] => Meta tag keywords
            [8] => Meta tag title
            [9] => Name
            [10] => Parent category ID
            [11] => Parent category: Name
            [12] => Sort order
            
      */
			
			$catnames = $category [9];
			$cats_from_name = explode ( ':', $catnames );
			//p ( $cats_from_name );
		//	p($category);

			$item_save = array ();
			$item_save ['taxonomy_value'] = $category [9];
			$item_save ['taxonomy_description'] = $category [2];
			$item_save ['taxonomy_type'] = 'category';
			
			$item_save ['custom_field_original_id'] = $category [9];
			$item_save ['custom_field_original_parent_id'] = $category [11];
			
			if (strstr ( $category [4], 'jpg' )) {
				$item_save ['screenshot_url'] = "http://tilos.com/cart/images/" . $category [4];
			}
			
			$get_categories_params = array ();
			$get_categories_params ['custom_fields_criteria'] ['original_id'] = $category [9];
		//		p($get_categories_params);
			$is_cat = CI::model ( 'taxonomy' )->taxonomyGet ( $get_categories_params );
		//	p ( $is_cat );
			$is_cat = $is_cat [0];
			
			//$is_cat = get_category ( $item_save ['taxonomy_value'] );
			

			if (empty ( $is_cat )) {
				$item_save ['id'] = false;
			} else {
				$item_save ['id'] = $is_cat ['id'];
				$item_save ['screenshot_url'] = false;
			}
			
			$get_categories_params = array ();
			$get_categories_params ['custom_fields_criteria'] ['original_id'] = $category [11];
			//p ( $category [11] );
			//	p ( $get_categories_params );
			$get_categories_params = CI::model ( 'taxonomy' )->taxonomyGet ( $get_categories_params );
			
			$is_parrent = $get_categories_params [0];
			//p ( $is_parrent );
			//p ( $category );
			

			if (empty ( $is_parrent )) {
				$item_save ['parent_id'] = 60;
			} else {
				print "parrr...";
				
				//	p ( $is_parrent );
				

				if ($is_parrent ['id'] != $item_save ['id']) {
					$item_save ['parent_id'] = $is_parrent ['id'];
				} else {
					$item_save ['parent_id'] = 60;
				}
			}
			
			?><pre><?php
			print "saving...";
			//p ( $get_categories_params );
			$s = CI::model ( 'taxonomy' )->taxonomySave ( $item_save, $preserve_cache = false );
			
			var_dump ( $s, $item_save );
			print "<hr>";
			?></pre>





<?php
		}
	}
	
	if ($_POST ['step1_1']) {
		$get_categories_params = array ();
		//$get_categories_params ['custom_fields_criteria'] ['original_id'] = "IS NOT NULL";
		//p ( $category [11] );
		//	p ( $get_categories_params );
		$get_categories_params = CI::model ( 'taxonomy' )->taxonomyGet ( $get_categories_params );
		//p ( $get_categories_params );
		foreach ( $get_categories_params as $c ) {
			$c_cf = CI::model ( 'core' )->getCustomFields ( 'table_taxonomy', $id = $c ['id'], $return_full = false );
			p($c);
			if ($c_cf ['original_parent_id'] != false) {
				
				$get_parent = array ();
				$get_parent ['taxonomy_value'] = $c_cf ['original_parent_id'];
				//$get_parent ['debug'] = $c_cf ['original_parent_id'];
				

				$p1=   explode ( '/' ,$c_cf ['original_parent_id']);
				$table_taxonomy = $cms_db_tables ['table_taxonomy'];
				p($p1);
				$get_parent = get_category ( $p1[0] );
				if (! empty ( $get_parent )) {
					$item_save = array ();
					$item_save ['id'] = $c ['id'];
					$item_save ['parent_id'] = $get_parent ['id'];
					$q = "update $table_taxonomy set parent_id='{$item_save ['parent_id']}' where id ='{$item_save ['id']}' ";
					//p($q);
					$q = CI::model ( 'core' )->dbQ ( $q );
					
					
					//$s = CI::model ( 'taxonomy' )->taxonomySave ( $item_save, $preserve_cache = false );
					
				//var_dump ( $s, $item_save );
				}
				
			//	p ( $get_parent );
			//p($c_cf);
			//	p($c);
			}
		}
		//p($get_categories_params);
	

	}
	
	if ($_POST ['step2']) {
		$csvarray = array ();
		// add more if you want
		$filezz = ($dir . 'temp/product.csv');
		# Open the File.
		if (($handle = fopen ( $filezz, "r" )) !== FALSE) {
			# Set the parent multidimensional array key to 0.
			$nn = 0;
			while ( ($data = fgetcsv ( $handle, 1000, "," )) !== FALSE ) {
				# Count the total keys in the row.
				$c = count ( $data );
				# Populate the multidimensional array.
				for($x = 0; $x < $c; $x ++) {
					$csvarray [$nn] [$x] = $data [$x];
				}
				$nn ++;
			}
			# Close the File.
			fclose ( $handle );
		}
		# Print the contents of the multidimensional array.
		

		$posts = ($csvarray);
		
		foreach ( $posts as $post ) {
			/*
	 * array(44) {
  [0]=>
  string(20) "Always free shipping"
  [1]=>
  string(8) "Category"
  [2]=>
  string(10) "Date added"
  [3]=>
  string(14) "Date available"
  [4]=>
  string(11) "Description"
  [5]=>
  string(13) "Discount type"
  [6]=>
  string(18) "Discount type from"
  [7]=>
  string(7) "Enabled"
  [8]=>
  string(5) "Image"
  [9]=>
  string(7) "Is call"
  [10]=>
  string(7) "Is free"
  [11]=>
  string(10) "Is virtual"
  [12]=>
  string(13) "Last modified"
  [13]=>
  string(15) "Manufacturer ID"
  [14]=>
  string(18) "Manufacturer: Name"
  [15]=>
  string(15) "Master category"
  [16]=>
  string(20) "Meta tag description"
  [17]=>
  string(17) "Meta tag keywords"
  [18]=>
  string(14) "Meta tag title"
  [19]=>
  string(21) "Metatags model status"
  [20]=>
  string(21) "Metatags price status"
  [21]=>
  string(29) "Metatags products name status"
  [22]=>
  string(21) "Metatags title status"
  [23]=>
  string(29) "Metatags title tagline status"
  [24]=>
  string(23) "Mixed discount quantity"
  [25]=>
  string(5) "Model"
  [26]=>
  string(4) "Name"
  [27]=>
  string(5) "Price"
  [28]=>
  string(12) "Price sorter"
  [29]=>
  string(19) "Priced by attribute"
  [30]=>
  string(10) "Product ID"
  [31]=>
  string(12) "Product type"
  [32]=>
  string(16) "Products ordered"
  [33]=>
  string(14) "Qty box status"
  [34]=>
  string(17) "Quantity in stock"
  [35]=>
  string(14) "Quantity mixed"
  [36]=>
  string(18) "Quantity order max"
  [37]=>
  string(18) "Quantity order min"
  [38]=>
  string(20) "Quantity order units"
  [39]=>
  string(10) "Sort order"
  [40]=>
  string(9) "Tax class"
  [41]=>
  string(3) "URL"
  [42]=>
  string(6) "Viewed"
  [43]=>
  string(6) "Weight"
}


*/
			
			$cats_from_name = explode ( ':', $post [1] );
			p($cats_from_name);
			$cat_from_name = $cats_from_name [0];
			
			$item_save = array ();
			$item_save ['content_title'] = $post [26];
			$item_save ['content_url'] = url_string ( $post [26] );
			$item_save ['custom_field_original_id_from_old_website'] = $post [30];
			$item_save ['custom_field_original_category_from_old_website'] = $post [1];
			$item_save ['custom_field_model'] = $post [25];
			$item_save ['content_body'] = $post [4];
			$item_save ['content_parent'] = 2598;
			//p($post [1]);
			//p($post [15]);
			$is_cat = get_category ( $cat_from_name );
			
			$get_categories_params = array ();
			$get_categories_params ['taxonomy_value'] = $cat_from_name;
			
			p ( $get_categories_params );
			$get_categories_params = get_categories ( $get_categories_params );
			p ( $get_categories_params );
			
			if (! empty ( $is_cat )) {
				//	p ( $is_cat );
				$item_save ['taxonomy_categories'] = array (60, $is_cat ['id'] );
			
			} else {
				$is_cat ['id'] = 60;
				$item_save ['taxonomy_categories'] = array (60 );
			
			}
			
			if (strstr ( $post [8], 'jpg' )) {
				$item_save ['screenshot_url'] = "http://tilos.com/cart/images/" . $post [8];
			}
			//p ( $item_save );
			

			$params = array ();
			//params for the output
			$params ['custom_fields_criteria'] = array ('original_id_from_old_website' => $post [30] );
			
			$check = get_posts ( $params );
			if (empty ( $check ['posts'] )) {
				if (! empty ( $is_cat )) {
					//$saved = post_save ( $item_save );
				} else {
					print 'cant save in non existing category ' . $saved;
				}
				
			//p ( $item_save );
			} else {
				print 'post is updated ' . $saved;
				
				if ($check ['posts'] [0] ['id']) {
					$item_save ['id'] = $check ['posts'] [0] ['id'];
					$item_save ['screenshot_url'] = false;
				}
				p ( $item_save );
				//$saved = post_save ( $item_save );
			
			}
			//p ( $check );
		

		}
	}
	
	if ($_POST ['step3']) {
		$csvarray = array ();
		// add more if you want
		$filezz = ($dir . 'temp/products_attributes.csv');
		# Open the File.
		if (($handle = fopen ( $filezz, "r" )) !== FALSE) {
			# Set the parent multidimensional array key to 0.
			$nn = 0;
			while ( ($data = fgetcsv ( $handle, 1000, "," )) !== FALSE ) {
				# Count the total keys in the row.
				$c = count ( $data );
				# Populate the multidimensional array.
				for($x = 0; $x < $c; $x ++) {
					$csvarray [$nn] [$x] = $data [$x];
				}
				$nn ++;
			}
			# Close the File.
			fclose ( $handle );
		}
		# Print the contents of the multidimensional array.
		

		$custom_fields = ($csvarray);
		$the_posts_to_save = array ();
		foreach ( $custom_fields as $custom_field ) {
			/*
	 * array(33) {
  [0]=>
  string(17) "Attribute is free"
  [1]=>
  string(23) "Attribute price letters"
  [2]=>
  string(28) "Attribute price letters free"
  [3]=>
  string(21) "Attribute price words"
  [4]=>
  string(26) "Attribute price words free"
  [5]=>
  string(19) "Attribute qty price"
  [6]=>
  string(27) "Attribute qty price onetime"
  [7]=>
  string(18) "Attribute required"
  [8]=>
  string(16) "Attribute weight"
  [9]=>
  string(23) "Attribute weight prefix"
  [10]=>
  string(7) "Default"
  [11]=>
  string(10) "Discounted"
  [12]=>
  string(12) "Display only"
  [13]=>
  string(18) "Download file name"
  [14]=>
  string(18) "Download max count"
  [15]=>
  string(17) "Download max days"
  [16]=>
  string(5) "Image"
  [17]=>
  string(9) "Option ID"
  [18]=>
  string(12) "Option: Name"
  [19]=>
  string(15) "Option value ID"
  [20]=>
  string(18) "Option value: Name"
  [21]=>
  string(18) "Option value price"
  [22]=>
  string(19) "Price base included"
  [23]=>
  string(12) "Price factor"
  [24]=>
  string(19) "Price factor offset"
  [25]=>
  string(20) "Price factor onetime"
  [26]=>
  string(27) "Price factor onetime offset"
  [27]=>
  string(13) "Price onetime"
  [28]=>
  string(12) "Price prefix"
  [29]=>
  string(10) "Product ID"
  [30]=>
  string(13) "Product: Name"
  [31]=>
  string(20) "Product attribute ID"
  [32]=>
  string(25) "Product option sort order"
}



*
*/
			$params = array ();
			//params for the output
			$params ['custom_fields_criteria'] = array ('original_id_from_old_website' => $custom_field [29] );
			
			//p(	$custom_field [20]);
			

			$check = get_posts ( $params );
			//p($check);
			if (! empty ( $check ['posts'] [0] )) {
				$post = $check ['posts'] [0];
				
				$aa = array ($custom_field [18] => $custom_field [20] );
				
				//array_push_array($the_posts_to_save[$post ['id']],$aa);
				

				$the_posts_to_save [$post ['id']] [$custom_field [18]] [] = $aa;
				//p($post);
			}
			
		//
		

		}
		//ksort ( $the_posts_to_save );
		print "<hr>";
		
		$tosave = array ();
		foreach ( $the_posts_to_save as $k => $v ) {
			$new = array ();
			$new ['id'] = $k;
			
			if (is_array ( $v )) {
				foreach ( $v as $vk => $vv ) {
					
					$temp = array ();
					foreach ( $vv as $vvk => $vvv ) {
						$name = (array_keys ( $vvv ));
						$temp = array_values ( $vvv );
						//p($vvv);
					}
					//	 p($name);
				// p($temp);
				

				//$new ['custom_field_' . strtolower ( $name [0] )] = $new ['custom_field_' . strtolower ( $name [0] )] . ',' . implode ( ',', $temp );
				}
			}
			$new ['cf'] = $v;
			$tosave [] = $new;
			//var_dump ( $k );
		//	var_dump ( $v );
		//print "<hr>";
		}
		foreach ( $tosave as $item ) {
			
			$cfs = ($item ['cf']);
			$cfkeys = array_keys ( $item ['cf'] );
			
			foreach ( $cfkeys as $cfkey ) {
				foreach ( $cfs as $cfs_k => $cfs_v ) {
					if (strtolower ( $cfkey ) == strtolower ( $cfs_k )) {
						//p($cfs_v);
						$vv = array_values_recursive ( $cfs_v );
						//p($vv);
						$cfs_k = strtolower ( $cfs_k );
						$post = array ();
						$post ['id'] = $item ['id'];
						$post ['custom_field_' . $cfs_k] = implode ( ',', $vv );
						$saved = post_save ( $post );
						//print $cfs_k . implode ( ',', $vv );
						p ( $post );
						print "<hr>";
					}
				}
			}
			
		//p ( $cfkeys );
		

		}
	}
	//var_dump ( $k );
} else {

}
?>
