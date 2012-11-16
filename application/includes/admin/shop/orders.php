<script  type="text/javascript">
$(document).ready(function(){
 
/* 
 var source = new EventSource('<? print site_url('api/event_stream')?>');
source.onmessage = function (event) {
   
  mw.$('#mw-admin-manage-orders').html(event.data);
};*/

    mw.on.hashParam("vieworder", function(){
         mw_select_order_for_editing(this);
    });
 
 
 
 
 function mw_select_order_for_editing($p_id){
	 mw.$('#mw-admin-manage-orders').attr('data-order-id',$p_id);
	 mw.$('#mw-admin-manage-orders').attr('view','edit_order');

	 	 	// mw.$('#pages_edit_container').removeAttr('data-subtype', 'post');

  	 mw.load_module('shop/orders','#mw-admin-manage-orders');
}


});
</script>


<module type="shop/orders/manage"  id="mw-admin-manage-orders"  />
