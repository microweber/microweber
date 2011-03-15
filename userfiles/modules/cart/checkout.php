<?

$cart_items = get_cart_items();
$order_id = "ORD". date("ymdHis") . rand ();
 ?>

<div id="main">
<div class="box">
  <h2 class="boxtitle">
    <editable  global="true" field="module_cart_checkout_title<? print $params['module_id'] ?>"> Please complete your order </editable>
  </h2>
  <div class="box-content">
    <? if(empty($cart_items)): ?>
    <span class="cartico">
    <editable  global="true" field="module_cart_checkout_text_empty<? print $params['module_id'] ?>"> Your cart is empty </editable>
    </span>
    <? else: ?>
    <div class="richtext">
    
    
    
    
    
      <div class="cart_show_on_complete" style="display:none">
        <h1>Your order is placed. We will contact you with more info.</h1>
      </div>
      
      
      
      <div class="cart_hide_on_complete">
        <editable  global="true" field="module_cart_checkout_text_more<? print $params['module_id'] ?>"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. </editable>
      </div>
      
      
      
      
      
      
      
      
      <div class="c" style="padding-bottom: 12px;"></div>
      <div id="cart_checkout_placeholder"></div>
      <editable  global="true" field="module_cart_checkout_text2<? print $params['module_id'] ?>">
        <h4>Your information:</h4>
        <br />
        <p>All the fields in this form are mandatory</p>
      </editable>
      <div class="c" style="padding-bottom: 12px">&nbsp;</div>
      <script type="text/javascript">

 

    $(document).ready(function(){

         
    });

    </script>
      <?  if(user_id()  != false):  ?>
      <? $form_values = get_user(user_id()); ?>
      <? endif ;?>
      <form method="post" action="#" class="checkout_form cart_hide_on_complete">
        <div class="block">
          <label class="clabel">Names *</label>
          <span class="field">
          <input type="text" class="required" name="names" value="<? print user_name(user_id(), 'full'); ?>" style="width: 390px" />
          </span> </div>
        <div class="block">
          <label class="clabel">E-mail *</label>
          <span class="field">
          <input type="text" class="required-email" name="email" value="<? print $form_values['email']; ?>"  style="width: 390px" />
          </span> </div>
        <div class="block">
          <label class="clabel">Address: *</label>
          <span class="field">
          <input type="text" class="required" name="address"  value="<? print $form_values['custom_fields']['address']; ?>" style="width: 390px" />
          </span> </div>
        <div class="block">
          <label class="clabel">City: *</label>
          <span class="field">
          <input type="text" class="required" name="city" value="<? print $form_values['custom_fields']['city']; ?>"  style="width: 166px" />
          </span>
          <label class="clabel"   style="width: 105px;margin-right: 10px;">Post code: *</label>
          <span class="field">
          <input type="text" class="required" name="zip"   value="<? print $form_values['custom_fields']['zip']; ?>" style="width: 95px" />
          </span> </div>
        <div class="block">
          <label class="clabel">Phone: *</label>
          <span class="field">
          <input type="text" class="required" name="phone" value="<? print $form_values['custom_fields']['phone']; ?>" style="width: 114px" />
          </span> </div>
        <div class="formhr">&nbsp;</div>
        <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
        <div class="block" style="padding-bottom: 0">
          <label class="clabel">&nbsp;</label>
          <input type="checkbox" class="conf required" />
          <editable  global="true" field="module_cart_checkout_iagree<? print $params['module_id'] ?>"> <span class="terms">I agree with the <a href="<? print site_url('terms');?>">terms and conditions</a></span> </editable>
        </div>
      </form>
      <br />
      <span class="right cart_hide_on_complete"><strong>*</strong> - mandatory fields </span><br />
      <div class="c" style="padding-bottom: 15px;">&nbsp;</div>
      <a href="#" class="orderbtn right cart_hide_on_complete" onclick="mw.cart.complete_order('.checkout_form', '.cart_hide_on_complete', '.cart_show_on_complete')">Submit order</a>
      <? endif ?>
    </div>
    <!-- { box-content end  -->
  </div>
  <!--  box end } -->
</div>
<!-- /#main -->
