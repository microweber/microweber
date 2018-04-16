<?php


if(!isset($params["category_subtype"]) or $params["category_subtype"] == false or $params["category_subtype"] == 'default'){
	 return;
}
d($params);

only_admin_access();
 
if(isset($params["data-category-id"])){
	$data = get_category_by_id($params["data-category-id"]);
}

if($data == false or empty($data )){
    include(__DIR__.DS.'_empty_category_data.php');
}

 
  
?>
<?php
 if(!isset($data['category_subtype_settings'])) { 
 $data['category_subtype_settings'] = array();
  } 
  
  $filter_content_by_keywords= false;
  if(is_array($data['category_subtype_settings'])){
	  if(isset($data['category_subtype_settings']['filter_content_by_keywords'])){
		  $filter_content_by_keywords = $data['category_subtype_settings']['filter_content_by_keywords'];
	  }
  }
  
  	?>

<div class="mw-ui-field-holder">
          <label class="mw-ui-label">
            Filter content by keywords
          </label>
          <input type="text"  class="mw-ui-field w100" name="category_subtype_settings[filter_content_by_keywords]" value="<?php print $filter_content_by_keywords; ?>" />
</div>
