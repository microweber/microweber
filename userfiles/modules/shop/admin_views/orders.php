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
	 
	 if(parseInt($p_id) == 0){
		  mw.$('#mw-admin-manage-orders').show();
		  mw.$('#mw-admin-edit-order').hide();
	 } else {
		  mw.$('#mw-admin-edit-order').show();
		  mw.$('#mw-admin-manage-orders').hide();
		  
		  
		   mw.$('#mw-admin-edit-order').attr('data-order-id',$p_id);
	// mw.$('#mw-admin-edit-order').attr('view','edit_order');

	 	 	// mw.$('#pages_edit_container').removeAttr('data-subtype', 'post');

  	 mw.load_module('shop/orders/edit_order','#mw-admin-edit-order');
	 }
	 
	 
	
}


});
</script>

<module type="shop/orders/manage"  id="mw-admin-manage-orders"  />
<div id="mw-admin-edit-order"></div>
