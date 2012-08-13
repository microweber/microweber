<script type="text/javascript">
$(document).ready(function(){
    $(".select_qty").each(function(){
      for(var c=2; c<=50; c++){
        $(this).append("<option value='" + c+ "'>" + c + "</option>");
      }
    });
});
</script>

<h1 class="title_product pink_color font_size_18">Преглед на поръчка</h1>
<br/>
<? $is_empty = get_items_qty() ; ?>
<? if($is_empty != 0): ?>
<div class="rounded_box">
  <div class="news_small_box">
    <!-- &nbsp;&nbsp;You have <? print get_items_qty() ; ?> item in your cart-->
    <mw module="cart/items" />
    <? 
				   $shop_page = array();
				   $shop_page['content_layout_name'] = 'shop';
				  
				  $shop_page=get_pages($shop_page);
				  $shop_page = $shop_page[0];
				//  var_dump($shop_page);
				  ?>
    <div class="clener"></div>
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
      <tr>
        <td><a class="pink_color" href="<? print page_link($shop_page['id']); ?>">Пазарувай още</a></td>
        <td><a class="rounded pink_btn" href="<? print page_link($shop_page['id']); ?>/view:checkout"> <span class="in1"> <span class="in2 min_w_120">Завършване на поръчка</span> </span></a></td>
      </tr>
    </table>
    <div class="clener"></div>
  </div>
  <div class="lt"></div>
  <div class="rt"></div>
  <div class="lb"></div>
  <div class="rb"></div>
</div>
<br />
<br />
<!-- /#cart -->
<? else : ?>
<? include "sidebar.php" ?>
<h1><a href="<? print page_link($shop_page['id']); ?>">Your cart is empty. Click here to go to the products page.</a></h1>
<? endif ?>
<div class="clener"></div>
 
