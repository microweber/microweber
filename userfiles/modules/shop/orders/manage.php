<?php only_admin_access(); ?>
<script>
    mw.require('forms.js',true);
	
	
	 mw.on.hashParam('search', function(){


  var field = mwd.getElementById('mw-search-field');

  if(!field.focused){ field.value = this; }

  if(this  != ''){
    $('#mw-admin-manage-orders-list').attr('keyword',this);
  } else {
    $('#mw-admin-manage-orders-list').removeAttr('keyword' );
  }

  $('#mw-admin-manage-orders-list').removeAttr('export_to_excel');


 mw.reload_module('#mw-admin-manage-orders-list', function(){
    mw.$("#mw-search-field").removeClass('loading');
 });


});




 


 

 $(document).ready(function(){
	 
    mw.$("#mw-admin-order-type-filter").change(function(){
        mw_admin_set_order_type_filter();
    });

  });

 function mw_admin_set_order_type_filter(){
	var v = $("#mw-admin-order-type-filter").val();
	
	
	if(v == 'carts'){
		$('.mw-admin-order-sort-carts').show();
		$('.mw-admin-order-sort-completed').hide();

		
	} else {
		$('.mw-admin-order-sort-carts').hide();
		$('.mw-admin-order-sort-completed').show();

	}
	
	
	  $('#mw-admin-manage-orders-list').attr('order-type',v );
	  $('#mw-admin-manage-orders-list').removeAttr('keyword' );
	  $('#mw-admin-manage-orders-list').removeAttr('order' );
	  mw.reload_module("#mw-admin-manage-orders-list"); 
	 
 }
 
</script>
<?php $is_orders = get_orders('count=1');


            ?>

<div class="mw-table-sorting-controller" style="width: 960px;">
	<h2 class="mw-side-main-title left" style="padding-top: 0;margin-right: 20px;"><span class="ico iorder-big"></span><span>
		<?php _e("Orders List"); ?>
		</span></h2>
	<select name="order_type" id="mw-admin-order-type-filter" class="mw-ui-simple-dropdown" autocomplete="off">
		<option value="completed">Completed orders</option>
		<option value="carts">Abandoned carts</option>
	</select>
	<div class="mw-table-sorting right mw-admin-order-sort-carts" style="display:none">
	
	<a class="mw-ui-btn" href="javascript:mw_admin_set_order_type_filter()">Refresh</a>
	
	</div>
	<?php  if($is_orders != 0){    ?>
	<div class="mw-table-sorting right mw-admin-order-sort-completed">
		<label>
			<?php _e("Sort By"); ?>
			:</label>
		<ul class="unselectable">
			<li><span data-sort-type="created_on" onclick="mw.tools.sort({id:'shop-orders', el:this});">
				<?php _e("Date"); ?>
				</span></li>
			<li><span data-sort-type="first_name" onclick="mw.tools.sort({id:'shop-orders', el:this});">
				<?php _e("Name(A-Z)"); ?>
				</span></li>
			<li><span data-sort-type="amount" onclick="mw.tools.sort({id:'shop-orders', el:this});">
				<?php _e("Ammout"); ?>
				</span></li>
		</ul>
	</div>
	<input
          style="width: 230px;margin-right: 30px;"
          type="text"
          onblur="mw.form.dstatic(event);"
          onfocus="mw.form.dstatic(event);"
          id="mw-search-field"
          class="mw-ui-searchfield right mw-admin-order-sort-completed"
          value="<?php _e("Search for orders"); ?>"
          data-default="<?php _e("Search for orders"); ?>"
          onkeyup="mw.form.dstatic(event);mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"
    />
	<?php  } ?>
	
	
	
	
</div>
<module type="shop/orders"  id="mw-admin-manage-orders-list"  />
