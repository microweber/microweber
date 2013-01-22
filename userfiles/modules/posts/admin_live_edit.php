<div class="mw_simple_tabs mw_tabs_layout_simple">
  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="active">Posts list</a></li>
    <li><a href="javascript:;">Skin/Template</a></li>
  </ul>
  <div class="tab">
    <? $rand = uniqid(); ?>
    <? $pages = get_content('content_type=page&subtype=dynamic&is_shop=n&limit=1000');   ?>
    <?php $posts_parent_page =  get_option('data-page-id', $params['id']); ?>
    <strong>Display posts from</strong>
    <select name="data-page-id" id="the_post_data-page-id<? print $rand ?>"  class="mw_option_field"  >
      <option     <? if((0 == intval($posts_parent_page))): ?>   selected="selected"  <? endif; ?>>None</option>
      <?
$pt_opts = array();
$pt_opts['link'] = "{title}";
$pt_opts['list_tag'] = " ";
$pt_opts['list_item_tag'] = "option";
$pt_opts['active_ids'] = $posts_parent_page;
$pt_opts['remove_ids'] = $params['id'];
$pt_opts['active_code_tag'] = '   selected="selected"  ';
 pages_tree($pt_opts);

  ?>
    </select>
    <?php $show_fields =  get_option('data-show', $params['id']);
if(is_string($show_fields)){
$show_fields = explode(',',$show_fields);
 $show_fields = array_trim($show_fields);
}
if($show_fields == false or !is_array($show_fields)){
$show_fields = array();	
}


//d($show_fields);
 ?>
    <script>
    $(function() {
        $( "#post_fields_sort_<? print $rand ?>" ).sortable({
		
		containment: "parent",
		stop: function( event, ui ){
		
		 $( "#post_fields_sort_<? print $rand ?> name['data-show']:first" ).trigger('change');
			
		}
		}
);
        
    });
    </script> 
    <br />
    <strong>Show fields</strong> <br />
    <ul id="post_fields_sort_<? print $rand ?>">
      <li>
        <label>
          <input type="checkbox" name="data-show" value="thumbnail" class="mw_option_field" <? if(in_array('thumbnail',$show_fields)): ?>   checked="checked"  <? endif; ?> />
          thumbnail</label>
        <strong>Size</strong>
        <input name="data-thumbnail-size" class="mw_option_field"   type="text"    value="<?php print get_option('data-thumbnail-size', $params['id']) ?>" />
        <small>ex: 250x200</small> </li>
      <li>
        <label>
          <input type="checkbox" name="data-show" value="title" class="mw_option_field" <? if(in_array('title',$show_fields)): ?>   checked="checked"  <? endif; ?> />
          title</label>
      </li>
       <li>
        <label>
          <input type="checkbox" name="data-show" value="description" class="mw_option_field" <? if(in_array('description',$show_fields)): ?>   checked="checked"  <? endif; ?> />
          description</label>
          
          <input name="data-character-limit" class="mw_option_field"   type="text"    value="<?php print get_option('data-character-limit', $params['id']) ?>" />
        <small>ex: 80</small>
        
        
      </li>
      <li>
        <label>
          <input type="checkbox" name="data-show" value="read_more" class="mw_option_field"  <? if(in_array('read_more',$show_fields)): ?>   checked="checked"  <? endif; ?> />
          Link</label>
        <input name="data-read-more-text" class="mw_option_field"   type="text"    value="<?php print get_option('data-read-more-text', $params['id']) ?>" />
        <small>ex: Read more</small> </li>
      <li>
        <label>
          <input type="checkbox" name="data-show" value="created_on" class="mw_option_field"  <? if(in_array('created_on',$show_fields)): ?>   checked="checked"  <? endif; ?> />
          date</label>
      </li>
    </ul>
    <br />
    <label>
      <input type="checkbox" name="data-hide-paging" value="y" class="mw_option_field" <? if(get_option('data-hide-paging', $params['id']) =='y'): ?>   checked="checked"  <? endif; ?> />
      Hide paging</label>
    <br />
    <strong>Number of posts per page</strong>
    <input name="data-limit" class="mw_option_field"   type="text"    value="<?php print get_option('data-limit', $params['id']) ?>" />
  </div>
  <div class="tab">
    <module type="admin/modules/templates"  />
  </div>
</div>
