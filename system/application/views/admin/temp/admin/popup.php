<table>
<?php foreach($order_info as $key=>$val):?>
<?php if($key != 'tracking_number'): ?>
	<tr>
		<td><?php echo $key?></td><td><?php echo $val?></td>
	</tr>
    <?php endif; ?>
    <?php if($key == 'tracking_number'): ?>
    <tr>
		<td>UPS Order responce</td><td><pre><?php print_r( explode('|',base64_decode($val)));?></pre></td>
	</tr>
     <?php endif; ?>
<?php endforeach;?>
</table>