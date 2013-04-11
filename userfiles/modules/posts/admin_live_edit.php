<?
only_admin_access();
$is_shop = false;

if(isset($params['is_shop']) and $params['is_shop'] == 'y'){
	$is_shop = 1;
}

		$dir_name = normalize_path(MODULES_DIR);
$posts_mod =  $dir_name.'posts'.DS.'admin_live_edit_tab1.php';;
 ?>
 
<div class="mw_simple_tabs mw_tabs_layout_simple">
<!--    <a href="<? print admin_url('view:').$params['module']  ?>" class="mw-ui-btn right relative" style="z-index: 2;margin:13px 13px 0 0;" target="_blank">Add post</a>
-->

<a href="javascript:;"
    class="mw-ui-btn mw-ui-btn-green"
    onclick="mw.simpletab.set(mwd.getElementById('add_new_post'));"
    style="position: absolute;top: 12px;right: 12px;z-index: 2;"><span class="ico iplus"></span><span class="ico ipost"></span>New Post </a>

  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="actSive">
      <? if($is_shop): ?>
      Products
      <? else:  ?>
      Posts
      <? endif;  ?>
      list</a></li>
    <li><a href="javascript:;">Skin/Template</a></li>
    <li id="add_new_post" style="display: none;"><a href="javascript:;"></a></li>


  </ul>


  <div class="tab">
    <? include_once($posts_mod); ?>
  </div>
  <div class="tab">
    <module type="admin/modules/templates" id="posts_list_templ" />
  </div>
   <div class="tab">
   <? 
   $add_post_q = 'subtype=post ';
   if(isset($params['page-id'])){
	    $add_post_q  .=' data-parent-page-id='.$params['page-id'];
   }?>
    <module type="content/edit_post" <? print $add_post_q ?> id="mw_posts_add_live_edit" />
  </div>
  <div class="mw_clear"></div>
  <div class="vSpace"></div>
</div>
