<?
$is_shop = false;

if(isset($params['is_shop']) and $params['is_shop'] == 'y'){
	$is_shop = 1;
}

		$dir_name = normalize_path(MODULES_DIR);
$posts_mod =  $dir_name.'posts'.DS.'admin_live_edit_tab1.php';;
 ?>

<div class="mw_simple_tabs mw_tabs_layout_simple">
  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="active">
      <? if($is_shop): ?>
      Products
      <? else:  ?>
      Posts
      <? endif;  ?>
      list</a></li>
    <li><a href="javascript:;">Skin/Template</a></li>
  </ul>
  <div class="tab">
    <? include_once($posts_mod); ?>
  </div>
  <div class="tab">
    <module type="admin/modules/templates" id="posts_list_templ" />
  </div>
  <div class="mw_clear"></div>
  <div class="vSpace"></div>
</div>
