<?php if (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 8")) {header("X-UA-Compatible: IE=7");}
  if (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 8")) {header("X-UA-Compatible: IE=EmulateIE7");}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:v='urn:schemas-microsoft-com:vml'>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="googlebot" content="index,follow" />
<meta name="robots" content="index,follow" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="rating" content="GENERAL" />
<meta name="MSSmartTagsPreventParsing" content="TRUE" />
<link rel="start" href="<?php print site_url(); ?>" />
<link rel="home" type="text/html" href="<?php print site_url(); ?>"  />
<link rel="index" type="text/html" href="<?php print site_url(); ?>" />
<meta name="generator" content="Microweber" />
<title>{content_meta_title}</title>
{content_meta_other_code}
<meta name="keywords" content="{content_meta_keywords}" />
<meta name="description" content="{content_meta_description}" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php print site_url('main/rss'); ?>" />
<link rel="sitemap" type="application/rss+xml" title="Sitemap" href="<?php print site_url('main/sitemaps'); ?>" />
<meta name="reply-to" content="<?php print $this->core_model->optionsGetByKey ( 'creator_email' ); ?>" />
<link rev="made" href="mailto:<?php print $this->core_model->optionsGetByKey ( 'creator_email' ); ?>" />
<meta name="author" content="http://ooyes.net" />
<meta name="language" content="en" />
<meta name="distribution" content="global" />
<meta http-equiv="Page-Enter" content="revealtrans(duration=0.0)" />
<meta http-equiv="Page-Exit" content="revealtrans(duration=0.0)" />
<?php include (ACTIVE_TEMPLATE_DIR.'header_scripts_and_css.php') ?>
<?php if(intval($page['id']) == 0){
$page['id'] = 1;

}?>
</head><body  id="page-<?php print $page['id']; ?>" >

<div id="container">
<div id="wrapper">
<div id="header">
  <div id="header-top"> <a href="<?php print site_url(); ?>" id="logo">School of Online Business</a>
    <?php require (ACTIVE_TEMPLATE_DIR.'header_user_top.php') ?>
  </div>
  <!-- /#header-top -->
  <?php $menu_items = $this->content_model->getMenuItemsByMenuUnuqueId('main_menu'); ?>
  <div id="navigation-bar">
    <ul>
      <?php foreach($menu_items as $item): ?>
      <?php $content_id_item = $this->content_model->contentGetByIdAndCache ( $item['content_id'] );

      	  	     ?>
      <?php if($the_section_layout == false): ?>
      <li <?php if($item['is_active'] == true): ?>  class="parent parent-active" <?php else: ?> class="parent"    <?php endif; ?>>
      <a <?php if($item['is_active'] == true): ?>  class="active parent-link" <?php else: ?> class="parent-link"  <?php endif; ?> href="<?php print $item['the_url'] ?>"><span><?php print ucwords( $item['item_title'] ) ?></span></a>
      <?php $content_item = $this->content_model->contentGetByIdAndCache($item['content_id']) ;
		   ?>
      <div class="sub-nav-block">
        <?php if(!empty($content_item)){
			if(($content_item['content_subtype']  == 'blog_section' ) and (intval($content_item['content_subtype_value'])  != 0 ) ){ ?>
        <a href="<?php print site_url('main/rss/categories:').$content_item['content_subtype_value'] ?>" class="sub-nav-rss">Subscribe for RSS</a>
        <?php }} ?>
        <div class="sub-nav-bar">
          <form action="<?php print site_url(); ?>browse/" method="post" class="sub-form" style="display:none">
            <input type="text" onblur="this.value==''?this.value='Search':''" onfocus="this.value=='Search'?this.value='':''" value="Search" name="search_by_keyword" class="type-text">
            <input type="submit" class="type-submit" value="">
          </form>
          <div class="c" style="padding-bottom: 21px;">&nbsp;</div>
          <h2><a href="<?php print $this->content_model->getContentURLByIdAndCache($content_item['id']); ?>"><?php print $content_item['content_title'] ?></a></h2>
          <br />
          <h2><a href="<?php print $this->content_model->getContentURLByIdAndCache($content_item['id']); ?>/view:list">Articles</a></h2>
          <h2><a href="<?php print $this->content_model->getContentURLByIdAndCache($content_item['id']); ?>/view:list/type:services">Sevices</a></h2>
          <h2><a href="<?php print $this->content_model->getContentURLByIdAndCache($content_item['id']); ?>/view:list/type:trainings">Trainings</a></h2>
          <h2><a href="<?php print $this->content_model->getContentURLByIdAndCache($content_item['id']); ?>/view:list/type:products">Products</a></h2>
          <h2 class="sub-forum"><a href="<?php print site_url('forum/forum.php?id='. $content_item['content_subtype_value']) ?>">Forum</a></h2>
        </div>
        <?php if(!empty($content_item)){
			if(($content_item['content_subtype']  == 'blog_section' ) and (intval($content_item['content_subtype_value'])  != 0 ) ){

		//	 $view = $this->core_model->getParamFromURL ( 'view' );
$link = false;
if($view == false){
$link = $this->content_model->getContentURLById($content_item['id']).'/category:{taxonomy_value}' ;
} else {
	$link = $this->content_model->getContentURLById($content_item['id']).'/category:{taxonomy_value}/view:'. $view ;
}
$active = '  class="active"   ' ;
$actve_ids = $active_categories;
if( empty($actve_ids ) == true){
$actve_ids = array($page['content_subtype_value']);
}
$this->content_model->content_helpers_getCaregoriesUlTree($content_item['content_subtype_value'], "<a href='$link'  {active_code}    >{taxonomy_value}</a>" , $actve_ids = $actve_ids, $active_code = $active, $remove_ids = false, $removed_ids_code = false, $ul_class_name = 'child-nav', $include_first = false);

		  }
		  }

		 ?>
      </div>
      </li>
      <?php else : ?>
      <li <?php if($content_id_item['content_section_name'] == $the_section_layout): ?>  class="active"  <?php endif; ?>><a <?php if($content_id_item['content_section_name'] == $the_section_layout): ?>  class="active"  <?php endif; ?> href="<?php print $item['the_url'] ?>"><span><?php print ucwords( $item['item_title'] ) ?></span></a></li>
      <?php endif; ?>
      <?php endforeach ;  ?>
      <!-- <li><a href="#"><span>Home</span></a></li>
                      <li><a href="#"><span>Entrepreneur</span></a></li>
                      <li><a href="#"><span>Business</span></a></li>
                      <li><a href="#"><span>Marketing</span></a></li>
                      <li><a href="#"><span>Money</span></a></li>
                      <li><a href="#"><span>Personal development</span></a></li>
                      <li><a href="#"><span>Community</span></a></li>-->
    </ul>
    <span id="nb-left"></span><span id="nb-right"></span> </div>
  <!-- /#navigation-bar --> 
</div>
<!-- /#header -->
<div id="content">
