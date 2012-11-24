<? $is_shop = get_content('is_shop=y');  ?>

<? if($is_shop == false): ?>  
<? mw_create_default_content('shop') ?> 

<strong>Please click on settings and add new product.
</strong><? endif; ?>