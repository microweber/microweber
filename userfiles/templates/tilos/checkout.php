
               <? include "header.php"; ?>       
<script type="text/javascript">
$(document).ready(function(){
    $(".select_qty").each(function(){
      for(var c=2; c<=50; c++){
        $(this).append("<option value='" + c+ "'>" + c + "</option>");
      }
    });
});
</script>

<div id="checkout">
    <h2 class="title"><img src="img/cartlogo.jpg" style="margin-top: -6px;" align="right" alt="" />Finish your order</h2>

    <div id="cart">
        <img src="img/hc.jpg" style="position: relative;top: 5px;" alt="" />&nbsp;&nbsp;You have 1 item in your cart

        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th width="320">Name of the product</th>
                    <th width="120">QTY</th>
                    <th width="70">Single price</th>
                    <th width="45">Remove</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="name_of_product">S-SUN 1W HIGH POWER STROBE</td>
                    <td class="product_qty">
                        <select class="select_qty">
                            <option value="1">1</option>
                        </select>
                    </td>
                    <td class="product_price">$54.70</td>
                    <td class="product_remove"><a href="#" class="remove_from_cart">&nbsp;</a></td>
                </tr>
            </tbody>
        </table>
        <div id="total">
            <span><strong>Total price:</strong></span>
            <b>$54.70</b>
        </div>

        <div class="c">&nbsp;</div>
        <div id="finish">
            <a href="#"><img src="img/cshopping.jpg" align="left" /></a>
            <a href="#"><img src="img/pvia.jpg" align="right" style="margin:16px -110px 0 0; " /></a>
            <strong>OR</strong>
        </div>

    </div><!-- /#cart -->



</div>


               <? include "footer.php"; ?>
