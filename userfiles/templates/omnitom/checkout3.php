<table width="100%" border="0" align="center" cellpadding="10" cellspacing="10">
  <tr valign="top">
    <td>
    <?php $order = $this->core_model->getParamFromURL ( 'order' ); 
	CI::model ( 'cart' )->orderConfirm($order);
	?>
    <div class="richtext" align="center"><h1>Thank you!</h1>
    <br>
<br>
<h2>Your order has been confirmed!</h2>
<h5>(order id: <?php print $order; ?>)</h5>
<br>
<br>
<h3>We will contact you with more information about your items as soon as possible.</h3>

    
    
    </div>
    
    </td>
  </tr>
</table>
