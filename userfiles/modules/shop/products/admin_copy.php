<?php //$rand = uniqid(); ?>
<?php $is_shop = get_content('is_shop=0');  ?>
<?php if(is_array($is_shop) and !empty($is_shop)): ?>

Add new product to shop
<?php else: ?>
<script type="text/javascript">
    function mw_make_new_shop{rand}(){
		
		mw.$('#add_shop_{rand}').attr('data-type', 'content/edit_page');
		mw.$('#add_shop_{rand}').attr('data-page-id', '0');
		mw.$('#add_shop_{rand}').attr('data-is-shop', 'y');
		
		mw.reload_module('#add_shop_{rand}');

		
	//  mw.load_module('content/edit_page','#add_shop_{rand}');
    }

</script> 
<a href="javascript:mw_make_new_shop{rand}()">Click here to create your online shop</a>
<div id="add_shop_{rand}"></div>
<?php endif; ?>
