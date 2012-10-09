<? $rand = uniqid(); ?>
<? $is_shop = get_content('is_shop=y');  ?>
<? if(is_array($is_shop) and !empty($is_shop)): ?>

Add new product to shop
<? else: ?>
<script type="text/javascript">
    function mw_make_new_shop<? print $rand ?>(){
		
		$('#add_shop_<? print $rand  ?>').attr('data-type', 'content/edit_page');
		$('#add_shop_<? print $rand  ?>').attr('data-page-id', '0');
		$('#add_shop_<? print $rand  ?>').attr('data-is-shop', 'y');
		
		mw.reload_module('#add_shop_<? print $rand  ?>');

		
	//  mw.load_module('content/edit_page','#add_shop_<? print $rand  ?>');
    }

</script> 
<a href="javascript:mw_make_new_shop<? print $rand ?>()">Click here to create your online shop</a>
<div id="add_shop_<? print $rand ?>"></div>
<? endif; ?>
