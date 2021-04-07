<script  type="text/javascript">
$(document).ready(function(){

      Rotator = document.getElementById('orders-rotator');

      mw.admin.simpleRotator(Rotator);

    var ord = mw.url.getHashParams(location.hash).vieworder;
    if(typeof ord != 'undefined'){
        mw_select_order_for_editing(ord)
    }

      mw.on.hashParam("vieworder", function(pval){
          if(pval!=false){
            mw_select_order_for_editing(pval);
          }
          else{
            mw_select_order_for_editing(0);
          }
      });


      function mw_select_order_for_editing($p_id){
      	 if(parseInt($p_id) == 0){
            Rotator.go(0);
      	 }
           else {
            mw.tools.loading('#mw-order-table-holder')
            mw.$('#mw-admin-edit-order').attr('data-order-id',$p_id);
            mw.load_module('shop/orders/edit_order','#mw-admin-edit-order', function(){
              mw.tools.loading('#mw-order-table-holder', false)
              Rotator.go(1);
            });
      	 }
      }







});

function mw_export_shop_order(pid, iscart, e) {

    if (e) e.preventDefault();

    window.location = mw.settings.api_url + 'shop/export_orders?id=' + pid;

}

function mw_delete_shop_order(pid, iscart, e){
    if(e)e.preventDefault();
    var iscart = iscart || false;

    var r = confirm("<?php _ejs("Are you sure you want to delete this order"); ?>?");
    if (r == true) {

        $.post("<?php print api_url('delete_order') ?>", { id: pid,is_cart:iscart}, function(data) {
            mw.reload_module('shop/orders');
            mw.reload_module('shop/orders/admin');
        });

        return true;

    }
}
</script>
<?php  mw()->notifications_manager->mark_as_read('shop');  ?>
<div class="admin-section-container">
  <div class="mw-simple-rotator">
      <div class="mw-simple-rotator-container" id="orders-rotator">
        <module type="shop/orders/manage"  id="mw-admin-manage-orders"  />
        <div id="mw-admin-edit-order"></div>
      </div>
  </div>
</div>
