<?php include TEMPLATE_DIR. "header.php"; ?>

  <script>

  $(document).ready(function(){
    $(window).bind('checkoutDone', function(e, data){
        if(!!data.success){
          mw.$('#cart_checkout').html('<div class="mw-ui-box mw-ui-box-content mw-ui-box-notification checkout-notification">'+data.success+'</div>');
        }
    });
  });

  </script>
  <div class="mw-wrapper">
    <div  class="edit" field="content" rel="content">

      <div  class="edit" field="checkout_page" rel="content">
        <div class="item-box" style="padding: 24px;">
        <h2 class="post-title">Complete your order</h2>
        <module type="shop/checkout" id="cart_checkout" /> </div>
      </div>
    </div>
  </div>

<?php include TEMPLATE_DIR.  "footer.php"; ?>
