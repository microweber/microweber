

<div class="cart_hide_on_complete"><h1 class="font_size_18 pink_color cart_hide_on_complete">Завършване на поръчка</h1>
<br /> <br />
  <br />
  <p> Имате <a href="<? print page_link($shop_page['id']); ?>/view:cart"><strong><span class="items cart_items_qty"><? print get_items_qty() ; ?></span> артикула</strong></a> във вашата кошница на обща стойност <b><span class="cart_items_total"><? print get_cart_total()  ?></span> <?php print option_get('shop_currency_sign') ; ?></b> <br />
    <br />
    </p>
</div>
<br />

<h1 class="font_size_18 pink_color cart_show_on_complete" style="display:none">Вашата поръчка е завършена.</h1>





<mw module="cart/checkout" />
<div class="clener"></div>
