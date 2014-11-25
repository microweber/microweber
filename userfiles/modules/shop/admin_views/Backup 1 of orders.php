<script  type="text/javascript">
$(document).ready(function(){

      Rotator = mwd.getElementById('orders-rotator');

      $(window).bind('resize', function(){
     //    mw.$('.mw-simple-rotator-item').width(mw.$('.mw-simple-rotator').width());
         $(Rotator).stop()[0].go(1);
      })

      mw.tools.simpleRotator(Rotator);

      mw.on.hashParam("vieworder", function(){
          if(this!=false){
            mw_select_order_for_editing(this);
          }
          else{
            mw_select_order_for_editing(0);
          }
      });
      mw.$('.mw-simple-rotator-item', this).width(mw.$('.mw-simple-rotator').width());
      Rotator.ongo(function(){
        mw.$('.mw-simple-rotator-item', this).width(mw.$('.mw-simple-rotator').width());
      });

      function mw_select_order_for_editing($p_id){
      	 if(parseInt($p_id) == 0){
            Rotator.go(0);
      	 }
           else {
            mw.$('#mw-admin-edit-order').attr('data-order-id',$p_id);
            mw.load_module('shop/orders/edit_order','#mw-admin-edit-order', function(){
                Rotator.go(1);
            });
      	 }
      }

});

function mw_delete_shop_order($p_id,$is_cart){
    if($is_cart == undefined){
      $is_cart = false;
    }
     mw.tools.confirm("<?php _e("Are you sure you want to delete this order"); ?>?", function(){
        $.post("<?php print api_url('delete_order') ?>", { id: $p_id,is_cart:$is_cart} ,function(data) {
            mw.reload_module('shop/orders');
        });
     });
}
</script>
<?php  mw()->notifications_manager->mark_as_read('shop');  ?>
<div class="mw-simple-rotator">
    <div class="mw-simple-rotator-container" id="orders-rotator">
      <module type="shop/orders/manage"  id="mw-admin-manage-orders"  />
      <div id="mw-admin-edit-order"></div>
    </div>
</div>