<?

$data = array ();
							$data ['created_by'] = user_id();
							$data ['is_paid'] = 'y';
							$ord = $this->cart_model->ordersGet ( $data, $limit = false );
							
							
							//p($ord);

?>

<h2>Payment history</h2>
<br />
<? if(!empty($ord)): ?>
<? foreach($ord as $item): ?>
<div class="bluebox">
  <div class="blueboxcontent">
    <div class="richtext">
      <p>You paid <strong>$<? print floatval($item['amount']); ?></strong> on <? print ($item['created_on']); ?></p>
      <?php switch($item['to_table']):
case 'table_users': ?>
      <? $user_paid_for = get_user($item['to_table_id']); ?>
      <p>for the account of <? print user_name($item['to_table_id']); ?></p>
      <?php break;?>
      <?php default: ?>
      <? print $item['to_table'] ?>, <? print $item['to_table_id'] ?>
      <?php break;?>
      <?php endswitch;?>
      
      <!-- <p>Your account will expire on 12.12.2012.</p>--> 
    </div>
  </div>
</div>
<div class="c" style="padding-bottom: 15px;"></div>
<? endforeach;  ?>
<? else: ?>
<div class="bluebox">
  <div class="blueboxcontent">
    <div class="richtext"> Your payment history is empty. </div>
  </div>
</div>
<div class="c" style="padding-bottom: 15px;"></div>
<? endif; ?>
