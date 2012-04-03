<div id="side_nav">
<?php $link = false;
$link = CI::model ( 'content' )->getContentURLById($page['id']).'/categories:{id}' ;
$active = '  class="active"   ' ;
$actve_ids = $active_categories;
if( empty($actve_ids ) == true){
$actve_ids = array($page['content_subtype_value']);
}
CI::model ( 'content' )->content_helpers_getCaregoriesUlTree($page['content_subtype_value'], "<a href='$link'  {active_code}    >{taxonomy_value}</a>" , $actve_ids = $actve_ids, $active_code = $active, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false); ?>



<div class="clear" style="padding-top:30px;"></div>



          <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('shop_sidebar_menu');	?>
<?php if(!empty($menu_items)): ?>
  <ul>
          <?php foreach($menu_items as $item): ?>

          <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>

          <?php endforeach ;  ?>

          <li><a href="<?php print site_url('sale'); ?>">Special Offers</a></li>


        </ul>
<?php endif; ?>
</div>


