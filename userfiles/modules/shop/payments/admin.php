<script  type="text/javascript">
 mw.require('options.js');
 </script>
<script  type="text/javascript">
  $(document).ready(function(){
    mw.options.form('.mw-set-payment-options');

    mw.tools.tabGroup({
       nav:'.mw-admin-side-nav a',
       tabs:'.mw-set-payment-options .otab'
    });

  });
</script>


<style>

.otab{
  display: none;
}

.mw-set-payment-options input[type='text'], .mw-set-payment-options textarea{
  width:300px;
}

 .mw-set-payment-options .mw-ui-select{
   width: 320px;
 }

.mw-set-payment-options .mw-ui-label {
    padding-bottom: 5px;
    padding-top: 10px;
}

.mw-set-payment-options .mw-ui-inline-selector li label.mw-ui-label{
  width: 75px;
  padding: 5px 0;
}

</style>



<?
$here = dirname(__FILE__).DS.'gateways'.DS;
$payment_modules = modules_list("cache_group=modules/global&dir_name={$here}");
// d($payment_modules);
?>

<div class="vSpace"></div>
<?
/**
 *
 */

?>
<div class="mw-admin-wrap">




    <div class="mw-o-box has-options-bar">

    <div class="mw-o-box-header" style="margin-bottom: 0;">
       <span class="ico ioptions"></span>
     <span>Options</span>

    </div>
    <div class="mw-o-box-content" style="padding: 0;">

    <div class="options-bar" style="margin-right: 0;">

         <div class="mw-admin-side-nav">

             <ul>
                <li><a class="active" href="javascript:;" style="padding: 6px;">Currency</a></li>
                <li><a href="javascript:;" style="padding: 6px;">Email confirmations</a></li>
                <li><a href="javascript:;" style="padding: 6px;">Payment providers</a></li>
             </ul>

         </div>


     </div>

     <div style="float: right;width: 745px;" class="mw-set-payment-options">


     <div class="otab" style="display: block">

     <h2>Currency settings</h2>
  <? ?><? $cur = get_option('currency', 'payments');  ?>
      <? $curencies = curencies_list(); ?>
      <? if(isarr($curencies )): ?>
      <div class="mw-ui-select"><select name="currency" class="mw-ui-field mw_option_field" data-option-group="payments" data-reload="mw_curr_rend">
        <? foreach($curencies  as $item): ?>
        <option  value="<? print $item[1] ?>" <? if($cur == $item[1]): ?> selected="selected" <? endif; ?>><? print $item[1] ?> <? print $item[3] ?> (<? print $item[2] ?>)</option>
        <? endforeach ; ?>
      </select></div>
      <? endif; ?>
      
      
      
<module type="shop/payments/currency_render" id="mw_curr_rend" />

 

      </div>

     <div class="otab">
      <h2>Send email to client when he orders</h2>
      <label class="mw-ui-check">
        <input name="order_email_enabled" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <? if(get_option('order_email_enabled', 'orders') == 'y'): ?> checked="checked" <? endif; ?> >
        <span></span><span>Yes</span></label>
      <label class="mw-ui-check">
        <input name="order_email_enabled" class="mw_option_field"     data-option-group="payments"  value="n"  type="radio"  <? if(get_option('order_email_enabled', 'orders') != 'y'): ?> checked="checked" <? endif; ?> >
        <span></span><span>No</span></label>
      <label class="mw-ui-label">Email subject</label>
      <input name="order_email_subject" class="mw-ui-field mw_option_field"    data-option-group="orders"  value="<? print get_option('order_email_subject', 'orders') ?>"  type="text" />
      <label class="mw-ui-label">Email content</label>
      <textarea class="mw-ui-field mw_option_field"   data-option-group="orders"  value="<? print get_option('order_email_content', 'orders') ?>"></textarea>
      <label class="mw-ui-label">From email</label>
      <input name="order_email_from" class="mw-ui-field mw_option_field"    data-option-group="orders"  value="<? print get_option('order_email_from', 'orders') ?>"  type="text" />
      <label class="mw-ui-label">From name</label>
      <input name="order_email_name" class="mw-ui-field mw_option_field"    data-option-group="orders"  value="<? print get_option('order_email_name', 'orders') ?>"  type="text" />

       </div>

      <div class="otab" >
      <h2>Payment providers </h2>
      <? if(isarr($payment_modules )): ?>


      <div class="mw_simple_tabs mw_tabs_layout_stylish">
            <ul style="margin: 0;" class="mw_simple_tabs_nav">
            <? foreach($payment_modules  as $payment_module): ?>
              <li><a class="" href="javascript:;"><? print $payment_module['name'] ?></a></li>
            <? endforeach ; ?>
            </ul>




      <? foreach($payment_modules  as $payment_module): ?>
      <div class="tab mw-o-box mw-o-box-content">
        <h2><? print $payment_module['name'] ?></h2>
        <ul class="mw-ui-inline-selector">
        <li><label class="mw-ui-label">Enabled:</label></li>
        <li><label class="mw-ui-check">
          <input name="payment_gw_<? print $payment_module['module'] ?>" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <? if(get_option('payment_gw_'.$payment_module['module'], 'payments') == 'y'): ?> checked="checked" <? endif; ?> >
          <span></span><span>Yes</span></label></li>
        <li><label class="mw-ui-check">
          <input name="payment_gw_<? print $payment_module['module'] ?>" class="mw_option_field"     data-option-group="payments"  value="n"  type="radio"  <? if(get_option('payment_gw_'.$payment_module['module'], 'payments') != 'y'): ?> checked="checked" <? endif; ?> >
          <span></span><span>No</span></label></li>

          </ul>
        <div class="mw-set-payment-gw-options" >
          <module type="<? print $payment_module['module'] ?>" view="admin" />
        </div>
      </div>
      <? endforeach ; ?>
      <? endif; ?>
       </div>
      </div>
    </div>
    </div>
    </div>
</div>
<div class="vSpace"></div>
