<? $view_order = url_param('view_order');  ?>
<script>
  $(document).ready(function() {
    //$("#orders_tabs").tabs();
  });
  </script>

<div class="box radius">
  <div id="orders_tabs">
    <div class="shop_nav_main">
      <h2 class="box_title">Online Shop</h2>
      <ul class="shop_nav">
        <? if($view_order  == false):?>
        <li><a href="#tab=orders-list"><span>Orders list</span></a></li>
        <li><a href="#tab=options"><span>Options</span></a></li>
        <? else : ?>
        <li><a href="#" class="active"><span>Viewing order: <? print $view_order ?> </span></a></li>
        <li><a href="<? print site_url('admin/action:shop');?>"><span>Back to orders list</span></a></li>
        <? endif;  ?>
      </ul>
    </div>
    <div class="c">&nbsp;</div>
    <? if($view_order  == false):?>
    <div class="tab" id="orders-list">
      <mw module="admin/orders/list" />
    </div>
    <div class="tab" id="options">
      <mw module="admin/orders/options" />
    </div>
    <? else : ?>
    <mw module="admin/orders/edit_order" id="<? print $view_order ?>" />
    <? endif;  ?>
  </div>
</div>
