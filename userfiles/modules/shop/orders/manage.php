<?php only_admin_access(); ?>
<script>
    mw.require('forms.js', true);

   $(mwd).ready(function(){
      mw.$("#mw-admin-order-type-filter").change(function(){
          mw_admin_set_order_type_filter();
      });
   });
   function mw_admin_set_order_type_filter(){
    	var v = mw.$("#mw-admin-order-type-filter").val();
    	if(v == 'carts'){
    		mw.$('.mw-admin-order-sort-carts').show();
    		mw.$('.mw-admin-order-sort-completed').hide();
    	} else {
    		mw.$('.mw-admin-order-sort-carts').hide();
    		mw.$('.mw-admin-order-sort-completed').show();
    	}
        mw.$('#mw-admin-manage-orders-list').attr('order-type', v);
        mw.$('#mw-admin-manage-orders-list').removeAttr('keyword');
        mw.$('#mw-admin-manage-orders-list').removeAttr('order');
        mw.reload_module("#mw-admin-manage-orders-list");
   }


ordersSort = function(obj){
    var group = mw.tools.firstParentWithClass(obj.el, 'mw-table-sorting');

    var table = mwd.getElementById(obj.id);

	var parent_mod = mw.tools.firstParentWithClass(table, 'module');

    var others = group.querySelectorAll('.mw-ui-btn'), i=0, len = others.length;
    for( ; i<len; i++ ){
        var curr = others[i];
        if(curr !== obj.el){

           $(curr).removeClass('ASC DESC active');
        }
    }
    obj.el.attributes['data-state'] === undefined ? obj.el.setAttribute('data-state', 0) : '';
    var state = obj.el.attributes['data-state'].nodeValue;
    var tosend = {}
    tosend.type = obj.el.attributes['data-sort-type'].nodeValue;
    if(state === '0'){
        tosend.state = 'ASC';
        obj.el.className = 'mw-ui-btn active ASC';
        obj.el.setAttribute('data-state', 'ASC');
    }
    else if(state==='ASC'){
        tosend.state = 'DESC';
        obj.el.className = 'mw-ui-btn active DESC';
        obj.el.setAttribute('data-state', 'DESC');
    }
    else if(state==='DESC'){
         tosend.state = 'ASC';
         obj.el.className = 'mw-ui-btn active ASC';
         obj.el.setAttribute('data-state', 'ASC');
    }
    else{
       tosend.state = 'ASC';
       obj.el.className = 'mw-ui-btn active ASC';
       obj.el.setAttribute('data-state', 'ASC');
    }

	if(parent_mod !== undefined){
		 parent_mod.setAttribute('data-order', tosend.type +' '+ tosend.state);
	     mw.reload_module(parent_mod);
	}
  }


</script>
<style>

.main-admin-row, .mw-simple-rotator{
  max-width: 1200px;
}

</style>
<?php $is_orders = get_orders('count=1'); ?>





<div class="mw-table-sorting-controller">
	<div class="section-header"><h2><span class="mw-icon-shopcart"></span><?php _e("Orders List"); ?></h2> </div>
	<div class="mw-ui-row" style="margin-bottom: 20px;">
        <div class="mw-ui-col">
            <select name="order_type" id="mw-admin-order-type-filter" class="mw-ui-field" autocomplete="off">
        		<option value="completed"><?php _e("Completed orders"); ?></option>
        		<option value="carts"><?php _e("Abandoned carts"); ?></option>
        	</select>
            <div class="mw-table-sorting right mw-admin-order-sort-carts" style="display:none">
        	    <span class="mw-ui-btn" onclick="mw_admin_set_order_type_filter();"><?php _e("Refresh"); ?></span>
        	</div>
        </div>
        <div class="mw-ui-col">
    	<?php  if($is_orders != 0){    ?>
        	<div class="mw-table-sorting right mw-admin-order-sort-completed">
                  <div class="mw-ui-btn-nav pull-right unselectable" style="margin-left: 10px;">
                  	<span class="mw-ui-btn" data-sort-type="created_on" onclick="ordersSort({id:'shop-orders', el:this});">
                  		<?php _e("Date"); ?>
                  	</span>
                  	<span class="mw-ui-btn" data-sort-type="first_name" onclick="ordersSort({id:'shop-orders', el:this});">
                  		<?php _e("Name(A-Z)"); ?>
                  	</span>
                  	<span class="mw-ui-btn" data-sort-type="amount" onclick="ordersSort({id:'shop-orders', el:this});">
                  		<?php _e("Ammout"); ?>
                  	</span>
                  </div>
                  <label class="pull-right" style="margin-top: 10px;"><?php _e("Sort By"); ?>:</label>
        	</div>
    	<?php  } ?>
        </div>
</div>

</div>
<module type="shop/orders"  id="mw-admin-manage-orders-list"  />
