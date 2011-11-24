<div id="sidebar">
  <div class="menu">
    <h3><? print $page['content_title'] ?></h3>
    <?php $link = false;

$link = CI::model('content')->getContentURLById($page['id']).'/categories:{id}' ;

$active = '  class="active"   ' ;

$actve_ids = $active_categories;

if( empty($actve_ids ) == true){

$actve_ids = array($page['content_subtype_value']);

}

CI::model('content')->content_helpers_getCaregoriesUlTree($page['content_subtype_value'], "<a href='$link'  {active_code}    >{taxonomy_value}</a>" , $actve_ids = $actve_ids, $active_code = $active, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false); ?>
    <div class="clear" style="padding-top:30px;"></div>
    <?php $menu_items = CI::model('content')->getMenuItemsByMenuUnuqueId('sidebar_menu');	?>
    <?php if(!empty($menu_items)): ?>
    <ul>
      <?php foreach($menu_items as $item): ?>
      <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
      <?php endforeach ;  ?>
    </ul>
    <?php endif; ?>
  </div>
  
  
  <br>
<br>
<form method="post" action="<?php print CI::model('content')->getContentURLByIdAndCache($page['id']) ?>" class="form label-inline">
<?php $kw_value =   CI::model('core')->getParamFromURL ( 'keyword' ); 
if($kw_value  == false){
$kw_value = 	'Search';
}
?>

         
          <input  name="search_by_keyword" type="text" class="required" value="<?php print $kw_value ?>" onfocus="this.value=='Search'?this.value='':''" onblur="this.value==''?this.value='Search':''" >
          
        
            <button type="submit"><span>Search</span></button>
   
         
          


</form>
</div>
