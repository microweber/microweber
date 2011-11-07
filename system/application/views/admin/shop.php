<? $view_order = url_param('view_order'); 

$nav = url_param('nav'); 
?>
<script>
  $(document).ready(function() {
    //$("#orders_tabs").tabs();
  });
  </script>

<div class="box radius">
  <div class="box_content_small_margin">
    <div class="shop_nav_main">
      <h2 class="box_title">Online Shop</h2>
      <ul class="shop_nav">
        <? if($view_order  == false):?>
        <li><a  href="<? print site_url('admin/action:shop');?>"><span>Orders list</span></a></li>
        <li><a  <? if($nav == 'options'):?>  class="active"  <? endif;  ?>  href="<? print site_url('admin/action:shop/nav:options');?>"><span>Options</span></a></li>
         <li><a  <? if($nav == 'promo'):?>  class="active"  <? endif;  ?>  href="<? print site_url('admin/action:shop/nav:promo');?>"><span>Promo codes</span></a></li>
        <? else : ?>
        
        <li><a href="#" class="active"><span>Viewing order: <? print $view_order ?> </span></a></li>
        <li><a href="<? print site_url('admin/action:shop');?>"><span>Back to orders list</span></a></li>
         
        <? endif;  ?>
      </ul>
    </div>
    <div class="c">&nbsp;</div>
    <? if($view_order  == false and $nav == false):?>
    
      <mw module="admin/orders/list" />
   
    <? endif;  ?>
    <? if($nav == 'options'):?>
   
      <mw module="admin/orders/options" />
    
    <? endif;  ?>
    
    
     <? if($nav == 'promo'):?>
   
      <mw module="admin/orders/promo" />
    
    <? endif;  ?>
    
    
    <? if($view_order != false):?>
<br />
 

   
      <mw module="admin/orders/edit_order" id="<? print $view_order ?>" />
 
    <? endif;  ?>
  </div>
</div>
