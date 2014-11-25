<?php

/*

  type: layout

  name: Client invoices bar

  description: List the client invoices from WHMCS




*/

  ?>
  <style> .user-tab{
    font-size: 14px;
  }
 </style>
<?php 
    $client_producsts = whm_get_user_invoices();
    $whmuser_info = whm_get_user_info();
    $show_pay_all_link = false;
    $invoices = array();
?>
<?php if(is_array($client_producsts) and is_array($whmuser_info)): ?>
      <?php foreach(($client_producsts) as $item): ?>
      <?php if(is_array($item) and isset($item['id']) and isset($item['id'])): ?>
      <?php
          $invoices[] = $item;
          $show_pay_all_link = true;
      ?>
	<?php endif; ?>
	<?php endforeach; ?>
<?php if($show_pay_all_link): ?>
<?php
    $goto = "viewinvoice.php?id=".$item['id'];
    $goto = "clientarea.php?action=masspay&all=true";
    $goto = api_url('panel_user_link').'?goto='.urlencode($goto);
?>


    <div class="alert alert-danger" >
        You have <?php   print(count($invoices)); ?> invoices <a class="blue"  href="<?php   print($goto); ?>">Pay all</a></div>


<?php endif; ?>
<?php endif; ?>
