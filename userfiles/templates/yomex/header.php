<?php if (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 8")) {header("X-UA-Compatible: IE=7");}
  if (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 8")) {header("X-UA-Compatible: IE=EmulateIE7");}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<link rel="shortcut icon" href="<?php print TEMPLATE_URL; ?>img/favico.ico" />
<meta name="author" content="http://ooyes.net" />
<meta name="language" content="en" />
<meta name="distribution" content="global" />
<meta http-equiv="Page-Enter" content="revealtrans(duration=0.0)" />
<meta http-equiv="Page-Exit" content="revealtrans(duration=0.0)" />
<?php include (ACTIVE_TEMPLATE_DIR.'header_scripts_and_css.php') ?>
</head><body id="<?php ($page['content_section_name2'] )? print ucfirst($page['content_section_name2']) : print 'Home' ; ?>">
<div style="background: white;text-align: center;padding: 10px;color: #333;">
    Сайтът е в процес на изграждане. <a href="http://yomexbg.com/kontakti"><u>Свържете се с нас за повече информация</u></a>.
</div>

<div id="container">
<div id="shadow">
<div id="shadow-right">
<div id="wrapper">
<div id="header">
  <div id="top-nav">
    <ul>
      <?php $menu_items = $this->content_model->getMenuItemsByMenuUnuqueId('top_menu'); ?>
      <?php foreach($menu_items as $item): ?>
      <li><a href="<?php print $item['the_url'] ?>"><?php print strtoupper( $item['item_title'] ) ?></a><span style="position: relative;top: -1px">|</span></li>
      <?php endforeach ;  ?>
      <li><a href="#"><img src="<?php print TEMPLATE_URL; ?>img/english.gif" align="absmiddle" /> English</a></li>
      <!--<li><a href="#"><img src="<?php print TEMPLATE_URL; ?>img/bulgaria.gif" align="absmiddle" /> Български</a></li>-->
    </ul>
  </div>
  <a href="<?php print site_url(); ?>" id="logo">Yomex - Culture and Tourism</a>
  <form method="post" action="<?php print site_url('search'); ?>" id="header-search">
    <input type="text" name="searchsite" value="<?php print $this->core_model->getParamFromURL('keyword'); ?>" class="type-text" />
    <input type="submit" value="" class="type-submit" />
  </form>
  <div class="c">&nbsp;</div>
  <div id="nav">
    <ul>
      <?php $menu_items = $this->content_model->getMenuItemsByMenuUnuqueId('main_menu'); ?>
      <?php foreach($menu_items as $item): ?>
      <?php $content_id_item = $this->content_model->contentGetByIdAndCache ( $item['content_id'] );

      	  	     ?>
      <?php if($the_section_layout == false): ?>
      <li <?php if($item['is_active'] == true): ?>  class="parent parent-active nav-<?php print ($content_id_item['content_section_name2'] ); ?>" <?php else: ?> class="parent nav-<?php print ($content_id_item['content_section_name2'] ); ?>"    <?php endif; ?>>
      <a <?php if($item['is_active'] == true): ?>  class="active parent-link nav-<?php print ($content_id_item['content_section_name2'] ); ?>" <?php else: ?> class="parent nav-<?php print ($content_id_item['content_section_name2'] ); ?>"  <?php endif; ?> href="<?php print $item['the_url'] ?>"> <?php print ucwords( $item['item_title'] ) ?> </a>
      <?php $content_item = $this->content_model->contentGetById($item['content_id']) ;
		  
		  if(!empty($content_item)){
			if(($content_item['content_subtype']  == 'blog_section' ) and (intval($content_item['content_subtype_value'])  != 0 ) ){
				
		//	 $view = $this->core_model->getParamFromURL ( 'view' ); 
$link = false;
if($view == false){
$link = $this->content_model->getContentURLByIdAndCache($content_item['id']).'/category:{taxonomy_value}' ;
} else {
	$link = $this->content_model->getContentURLByIdAndCache($content_item['id']).'/category:{taxonomy_value}/view:'. $view ;
}
$active = '  class="active"   ' ;
$actve_ids = $active_categories;
if( empty($actve_ids ) == true){
$actve_ids = array($page['content_subtype_value']);
}
$this->content_model->content_helpers_getCaregoriesUlTree($content_item['content_subtype_value'], "<a href='$link'  {active_code}    >{taxonomy_value}</a>" , $actve_ids = $actve_ids, $active_code = $active, $remove_ids = false, $removed_ids_code = false, $ul_class_name = 'child-nav', $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $order = false, $only_with_content = true);



//$this->content_model->content_helpers_getCaregoriesUlTree($content_item['content_subtype_value'], "<a href='$link'  {active_code}    ><strong>{taxonomy_value}</strong></a>" , $actve_ids = $actve_ids, $active_code = $active, $remove_ids = false, $removed_ids_code = false, $ul_class_name = 'sec-nav', $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $order = array('taxonomy_value', 'asc'), $only_with_content = true);

			  
		  }
		  }
		  
		 ?>
      </li>
      <?php else : ?>
      <li <?php if($content_id_item['content_section_name'] == $the_section_layout): ?>  class="active <?php print ($content_id_item['content_section_name2'] ); ?>"  <?php endif; ?>><a <?php if($content_id_item['content_section_name'] == $the_section_layout): ?>  class="active"  <?php endif; ?> href="<?php print $item['the_url'] ?>"><span><?php print ucwords( $item['item_title'] ) ?></span></a></li>
      <?php endif; ?>
      <?php endforeach ;  ?>
    </ul>
    
    <!-- <ul>
                        
                        
                            <li class="nav-orange"><a href="#">Културен туризъм</a></li>
                            <li class="nav-purple"><a href="#">Конгресен туризъм</a></li>
                            <li class="nav-yellow"><a href="#">Екскурзии</a>
                                <ul>
                                    <li><a href="#">Автобусни</a></li>
                                    <li><a href="#">Със самолет</a></li>
                                    <li><a href="#">Със самолет</a></li>
                                </ul>
                            </li>
                            <li class="nav-green"><a href="#">Почивки</a>
                                <ul>
                                    <li><a href="#">Автобусни</a></li>
                                    <li><a href="#">Със самолет</a></li>
                                    <li><a href="#">Със самолет</a></li>
                                </ul>
                            </li>
                            <li class="nav-pink"><a href="#">Хотелски резервации</a>
                                <ul>
                                    <li><a href="#">Автобусни</a></li>
                                    <li><a href="#">Със самолет</a></li>
                                    <li><a href="#">Със самолет</a></li>
                                </ul>                       
                            </li>
                            <li class="nav-blue"><a href="#">Самолетни билети</a></li>
                        </ul>--> 
  </div>
</div>
<!-- /#header -->

<div id="content">
