admin posts
<hr />

Adrrsss
<module type="option" name="asdsadas" field_type="category_tree">




Pages
<module type="option" name="asdsadas" field_type="pages_menu">


<strong>Limit</strong>
<?php print get_option('data-limit', $params['id']) ?>


<input name="data-limit" class="mw_option_field"   type="text"    value="<?php print option_get('data-limit', $params['id']) ?>" />

 
 



<? $rand = uniqid(); ?>
<? $pages = get_content('content_type=page&subtype=dynamic&is_shop=n&limit=1000');   ?>
<?php $posts_parent_page =  option_get('data-page-id', $params['id']); ?>
<strong>From page</strong>
<select name="data-page-id" id="the_post_data-page-id<? print $rand ?>"  class="mw_option_field"  >
  <option     <? if((0 == intval($posts_parent_page))): ?>   selected="selected"  <? endif; ?>>None</option>
  
   <?
$pt_opts = array();
$pt_opts['link'] = "{title}";
$pt_opts['list_tag'] = " ";
$pt_opts['list_item_tag'] = "option";
$pt_opts['actve_ids'] = $posts_parent_page;
$pt_opts['remove_ids'] = $params['id'];
 
 
$pt_opts['active_code_tag'] = '   selected="selected"  ';
 



 pages_tree($pt_opts);


  ?>
  
  
  
  
  
</select>
<br />
<strong>Limit</strong>
<input name="data-limit" class="mw_option_field"   type="text"    value="<?php print option_get('data-limit', $params['id']) ?>" />
<br />
<?php $show_fields =  option_get('data-show', $params['id']);
if(is_string($show_fields)){
$show_fields = explode(',',$show_fields);	
}
if($show_fields == false or !is_array($show_fields)){
$show_fields = array();	
}



 ?>
 
  <script>
    $(function() {
        $( "#post_fields_sort_<? print $rand ?>" ).sortable({
		
		containment: "parent",
		stop: function( event, ui ){
		
		// $( "#post_fields_sort_<? print $rand ?> name['data-show']:first" ).trigger('change');
			
		}
		}
);
        
    });
    </script>
<strong>Show fields</strong>
<ul id="post_fields_sort_<? print $rand ?>">
  <li>
    <label>
      <input type="checkbox" name="data-show" value="thumbnail" class="mw_option_field" <? if(in_array('thumbnail',$show_fields)): ?>   checked="checked"  <? endif; ?> />
      thumbnail</label>
  </li>
  <li>
    <label>
      <input type="checkbox" name="data-show" value="title" class="mw_option_field" <? if(in_array('title',$show_fields)): ?>   checked="checked"  <? endif; ?> />
      title</label>
  </li>
  <li>
    <label>
      <input type="checkbox" name="data-show" value="read_more" class="mw_option_field"  <? if(in_array('read_more',$show_fields)): ?>   checked="checked"  <? endif; ?> />
      read_more</label>
  </li>
  <li>
    <label>
      <input type="checkbox" name="data-show" value="created_on" class="mw_option_field"  <? if(in_array('created_on',$show_fields)): ?>   checked="checked"  <? endif; ?> />
      created_on</label>
  </li>
</ul>
<br />
<br />
<strong>Thumbnail size</strong>
<input name="data-thumbnail-size" class="mw_option_field"   type="text"    value="<?php print option_get('data-thumbnail-size', $params['id']) ?>" />
<small>ex: 250x200</small> <br />
