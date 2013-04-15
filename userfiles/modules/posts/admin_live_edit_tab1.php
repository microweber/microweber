<?

only_admin_access();
 
$set_content_type = 'post';
if(isset($params['global']) and $params['global'] != false){
	$set_content_type =  get_option('data-content-type', $params['id']); 
}
$rand = uniqid(); ?>
<? if(!isset($is_shop) or $is_shop == false): ?>
<? $is_shop = false; $pages = get_content('content_type=page&subtype=dynamic&is_shop=n&limit=1000');   ?>
 
<? else:  ?>
<? $pages = get_content('content_type=page&is_shop=y&limit=1000');   ?>
<? endif; ?>
<?php $posts_parent_page =  get_option('data-page-id', $params['id']); ?>
<? if(isset($params['global']) and $params['global'] != false) :  ?>
<? if($set_content_type =='product'):  ?>
<? $is_shop = 1; $pages = get_content('content_type=page&is_shop=y&limit=1000');   ?>
<? endif; ?>
<label class="mw-ui-label">Content type</label>
<div class="mw-ui-select" style="width: 100%;">
  <select name="data-content-type" id="the_post_data-content-type<? print  $rand ?>"  class="mw_option_field" data-also-reload="<? print  $config['the_module'] ?>"  >
    <option  value="post"    <? if(('post' == trim($set_content_type))): ?>   selected="selected"  <? endif; ?>>Posts</option>
    <option  value="page"    <? if(('page' == trim($set_content_type))): ?>   selected="selected"  <? endif; ?>>Pages</option>
    <option  value="product"    <? if(('product' == trim($set_content_type))): ?>   selected="selected"  <? endif; ?>>Product</option>
    <option   value="none"   <? if(('none' == trim($set_content_type))): ?>   selected="selected"  <? endif; ?>>None</option>
  </select>
</div>
<? endif; ?>

<? if(!isset($set_content_type) or $set_content_type != 'none') :  ?>


<label class="mw-ui-label">Display <? print pluralize($set_content_type) ?> from page</label>
<div class="mw-ui-select" style="width: 100%;">
  <select name="data-page-id" id="the_post_data-page-id<? print  $rand ?>"  class="mw_option_field" data-also-reload="<? print  $config['the_module'] ?>"   >
    <option     <? if((0 == intval($posts_parent_page))): ?>   selected="selected"  <? endif; ?>>All pages</option>
    <?
$pt_opts = array();
  $pt_opts['link'] = "{empty}{title}";
$pt_opts['list_tag'] = " ";
$pt_opts['list_item_tag'] = "option";
//$pt_opts['include_categories'] = "option";
$pt_opts['active_ids'] = $posts_parent_page;
$pt_opts['remove_ids'] = $params['id'];
$pt_opts['active_code_tag'] = '   selected="selected"  ';
 if($is_shop != false){
	 $pt_opts['is_shop'] = 'y';
 }
 if($set_content_type == 'product'){
	  $pt_opts['is_shop'] = 'y';
}


 pages_tree($pt_opts);

  ?>
  </select>
</div>


<? if($posts_parent_page != false and intval($posts_parent_page) > 0): ?>

<?php $posts_parent_category =  get_option('data-category-id', $params['id']); ?>
 
<label class="mw-ui-label">Show only from category</label>
<div class="mw-ui-select" style="width: 100%;">
  <select name="data-category-id" id="the_post_data-page-id<? print  $rand ?>"  class="mw_option_field"  data-also-reload="<? print  $config['the_module'] ?>"  >
    <option     <? if((0 == intval($posts_parent_category))): ?>   selected="selected"  <? endif; ?>>Select a category</option>
    <?
$pt_opts = array();
  $pt_opts['link'] = "{empty}{title}";
$pt_opts['list_tag'] = " ";
$pt_opts['list_item_tag'] = "option";
 $pt_opts['active_ids'] = $posts_parent_category;
 $pt_opts['active_code_tag'] = '   selected="selected"  ';
 
$pt_opts['rel'] = 'content';
$pt_opts['rel_id'] = $posts_parent_page;
 
 category_tree($pt_opts);

  ?>
  </select>
</div>


 
<? endif; ?>


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
<style type="text/css">

    .mw-ui-check input + span + span, body{
      font-size: 11px;
    }

    .fields-controlls li{
      list-style: none;
      clear: both;
      min-height: 45px;

      min-height: 32px;
      overflow: hidden;
      padding: 6px 6px 4px;
      border-radius:2px;

    }
    .fields-controlls li:hover{background: #F8F8F8}

    .mw-ui-label-horizontal{
      display: inline-block;
      float: left;
      text-align: right;
      padding-right: 10px;
      margin-top: 5px;
    }

    .mw-ui-check input + span{
      top: 5px;
    }

    </style>
<div class="vSpace"></div>
<label class="mw-ui-label">Display on posts: </label>
<ul id="post_fields_sort_<? print  $rand ?>" class="fields-controlls">
  <li>
    <label class="mw-ui-check left">
      <input type="checkbox" name="data-show" value="thumbnail" class="mw_option_field" <? if(in_array('thumbnail',$show_fields)): ?>   checked="checked"  <? endif; ?> />
      <span></span> <span>Thumbnail</span> </label>
    <div class="right">
      <label class="mw-ui-label-horizontal">Size</label>
      <input name="data-thumbnail-size" class="mw-ui-field mw_option_field"   type="text" style="width:65px;" placeholder="250x200"  value="<?php print get_option('data-thumbnail-size', $params['id']) ?>" />
    </div>
  </li>
  <li>
    <label class="mw-ui-check">
      <input type="checkbox" name="data-show" value="title" class="mw_option_field" <? if(in_array('title',$show_fields)): ?>   checked="checked"  <? endif; ?> />
      <span></span> <span>Title</span></label>
      <div class="right">
      <label class="mw-ui-label-horizontal">Length</label>
      <input name="data-title-limit" class="mw-ui-field mw_option_field"   type="text" placeholder="255" style="width:65px;"  value="<?php print get_option('data-title-limit', $params['id']) ?>" />
    </div>
  </li>
  <li>
    <label class="mw-ui-check">
      <input type="checkbox" name="data-show" value="description" class="mw_option_field" <? if(in_array('description',$show_fields)): ?>   checked="checked"  <? endif; ?> />
      <span></span> <span>Description</span></label>
    <div class="right">
      <label class="mw-ui-label-horizontal">Length</label>
      <input name="data-character-limit" class="mw-ui-field mw_option_field"   type="text" placeholder="80" style="width:65px;"  value="<?php print get_option('data-character-limit', $params['id']) ?>" />
    </div>
  </li>
  <? if($is_shop): ?>
  <li>
    <label class="mw-ui-check">
      <input type="checkbox" name="data-show" value="price" class="mw_option_field" <? if(in_array('price',$show_fields)): ?>   checked="checked"  <? endif; ?> />
      <span></span> <span>Show price</span></label>
  </li>
  <li>
    <label class="mw-ui-check">
      <input type="checkbox" name="data-show" value="add_to_cart" class="mw_option_field"  <? if(in_array('add_to_cart',$show_fields)): ?>   checked="checked"  <? endif; ?> />
      <span></span> <span>Add to cart button</span></label>
    <div class="right">
      <label class="mw-ui-label-horizontal">Title</label>
      <input name="data-add-to-cart-text" class="mw-ui-field mw_option_field" style="width:65px;" placeholder="Add to cart"  type="text"    value="<?php print get_option('data-add-to-cart-text', $params['id']) ?>" />
    </div>
  </li>
  <? endif; ?>
  <li>
    <label class="mw-ui-check">
      <input type="checkbox" name="data-show" value="read_more" class="mw_option_field"  <? if(in_array('read_more',$show_fields)): ?>   checked="checked"  <? endif; ?> />
      <span></span> <span>Read More Link</span></label>
    <div class="right">
      <label class="mw-ui-label-horizontal">Title</label>
      <input name="data-read-more-text" class="mw-ui-field mw_option_field"   type="text" placeholder="Read more" style="width:65px;"   value="<?php print get_option('data-read-more-text', $params['id']) ?>" />
    </div>
  </li>
  <li>
    <label class="mw-ui-check">
      <input type="checkbox" name="data-show" value="created_on" class="mw_option_field"  <? if(in_array('created_on',$show_fields)): ?>   checked="checked"  <? endif; ?> />
      <span></span> <span>Date</span></label>
  </li>
  <li>
    <label class="mw-ui-check left">
      <input type="checkbox" name="data-hide-paging" value="y" class="mw_option_field" <? if(get_option('data-hide-paging', $params['id']) =='y'): ?>   checked="checked"  <? endif; ?> />
      <span></span><span>Hide paging</span></label>
    <div class="right">
      <label class="mw-ui-labe-horizontall">Posts per page</label>
      <input name="data-limit" class="mw-ui-field mw_option_field"   type="number"  style="width:65px;" placeholder="10"  value="<?php print get_option('data-limit', $params['id']) ?>" />
    </div>
  </li>
</ul>
<? endif; ?>