<?php
$input_class = '';
 if(!isset($params['active_ids'])){
	$params['active_ids'] = 0; 
 }
 if(!isset($params['input-name'])){
    $params['input-name'] = 'category_id' ;
 }
 
  if(isset($params['input-class'])){
    $input_class = $params['input-class'] ;
 }
 
 
 
 $posts_parent_page = intval($params['active_ids']) ;
?>
       
<select class="<?php print $input_class ?>" name="<?php print $params['input-name'] ?>"     >
  <option  <?php if((0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>><?php _e("None"); ?></option>
<?php
  $pt_opts = array();
  $pt_opts['link'] = "{title}";
  $pt_opts['list_tag'] = " ";
  $pt_opts['list_item_tag'] = "option";
  $pt_opts['include_categories'] = "true";
  $pt_opts['for'] = "content";
  $pt_opts['categories_active_ids'] = $posts_parent_page;
  $pt_opts['remove_ids'] = $params['id'];
  $pt_opts['active_code_tag'] = '   selected="selected"  ';
  pages_tree($pt_opts);    ?>

<?php  if(isset($params['include_global_categories']) and $params['include_global_categories'] == true  and isset($params['include_global_categories'])){
        $str0 = 'table=categories&limit=1000&data_type=category&' . 'parent_id=0&rel_id=0&rel=content';
		$fors = db_get($str0);
		if ($fors != false and is_array($fors) and !empty($fors)) {
			foreach ($fors as $cat) {
				$cat_params =$params;
				$pt_opts = array();
                $pt_opts['link'] = "{title}";
                $pt_opts['list_tag'] = " ";
                $pt_opts['list_item_tag'] = "option";
				$pt_opts['parent'] =$cat['id'];
				$pt_opts['include_first'] = 1;
				category_tree($pt_opts);
			}
		}
 }
?>
</select>
