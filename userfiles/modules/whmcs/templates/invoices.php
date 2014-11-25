<?php

/*

  type: layout

  name: Client invoices

  description: List the client invoices from WHMCS




*/

  ?>
<?php 
 

  $client_producsts = whm_get_user_invoices();
 
$whmuser_info = whm_get_user_info();
 $show_pay_all_link = false;
  ?>
<?php if(is_array($client_producsts) and is_array($whmuser_info)): ?>

<table class="table table-bordered">
	<thead>
	<th>#</th>
		<th>total</th>
		<th>view</th>
			</thead>
		<?php foreach(($client_producsts) as $item): ?>
		<?php if(is_array($item) and isset($item['id']) and isset($item['id'])): ?>
	<tr>
		<td> #
			<?php   print($item['id']); ?>
			-
			<?php
		$status = strtolower($item['status']);
		
		 ?>
			<?php if($status == 'paid'): ?>
			<span class="label label-success">
			<?php   print($item['status']); ?>
			</span>
			<?php elseif($status == 'unpaid'): ?>
			<span class="label label-important">
			<?php
			$show_pay_all_link = true;
			  print($item['status']); ?>
			</span>
			<?php elseif($status == 'cancelled'): ?>
			<span class="label label-default">
			<?php   print($item['status']); ?>
			</span>
			<?php else: ?>
			<span class="label label-info">
			<?php   print($item['status']); ?>
			</span>
			<?php endif; ?></td>
		<td><?php   print($item['total']); ?></td>
		<td><?php $goto = "viewinvoice.php?id=".$item['id'];

 $goto = api_url('panel_user_link').'?goto='.urlencode($goto);

 
       ?>
			<?php if($status == 'unpaid'): ?>
			<a class="btn btn-default btn-primary"  href="<?php   print($goto); ?>" target="_blank">Pay invoice</a>
			<?php else: ?>
			<a class="btn btn-default"  href="<?php   print($goto); ?>" >View invoice</a>
			<?php endif; ?></td>
	</tr>
	<?php endif; ?>
	<?php endforeach; ?>
</table>


<?php if($show_pay_all_link): ?>
<?php $goto = "viewinvoice.php?id=".$item['id'];

  $goto = "clientarea.php?action=masspay&all=true";
                        
						
					 

$goto = api_url('panel_user_link').'?goto='.urlencode($goto);

 
       ?>
  
<a class="btn btn-default btn-primary"  href="<?php   print($goto); ?>">Pay all invoices</a>
 
<?php endif; ?>




<?php endif; ?>
